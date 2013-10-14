<?php
global $optionsWithArgs;

$optionsWithArgs = [
	'assetDir',
	'statsFile',
];

if ( defined( 'MEDIAWIKI' ) ) {
	exit( 1 );
}

require_once( dirname( __FILE__ ) . '/../../maintenance/commandLine.inc' );
define( 'ASSET_ARCHIVE_DIR', '/home/nelson/asset-bak' ); // TODO: what's a good default for this?

if (isset($options['assetDir'])) {
	assetDir($options['assetDir']);
}

$statsFile = null;
if (isset($options['statsFile'])) {
	$statsFile = $options['statsFile'];
	file_put_contents($statsFile, '');
}

$preProcessedDataDir = dirname( __FILE__ ) . '/../../resources/preProcessed';
$serializedOutputFile = dirname( __FILE__ ) . '/../' . AssetsManagerBaseBuilder::SERIALIZED_FILE;
$wgMinifyMethod = AssetsManagerBaseBuilder::MINIFY_METHOD_JS_UGLIFYJS;
$wgAssetsManagerAllowedPreProcessedAssets = false;

$hashes = array();
$config = new AssetsConfig();

$params = 'minify=true';
if ($statsFile) {
	$params .= '&forceprofile=1';
}

$request = new FauxRequest( [
	'type' => 'group',
	'params' => $params,
] );

if ( !is_dir( $preProcessedDataDir ) ) {
	mkdir( $preProcessedDataDir, 0755 );
}

// build up group files
$targetGroups = $config->getGroupNames();
foreach ( $targetGroups as $groupName ) {
	if ($config->getGroupType($groupName) != AssetsManager::TYPE_JS) {
		continue;
	}

	$request->setVal( 'oid', $groupName );
	$builder = new AssetsManagerGroupBuilder( $request );
	$content = $builder->getContent();
	$hash = md5( $content );
	$targetOut = "{$preProcessedDataDir}/{$hash}";
	$dataOut = trim( $content );

	if ( strlen( $dataOut ) == 0 ) {
		echo "ERROR writing $groupName: 0 length!\n";
	} elseif ( file_put_contents( $targetOut, $dataOut ) ) {
		$hashes[ $groupName ] = $hash;
	}

	if ($statsFile) {
		logStats($statsFile, $groupName, $builder->profilerData());
	}
}

// build up individual files
$fileList = [ ];
findAssetFiles( $IP, $fileList );

foreach ( array_unique( $fileList ) as $file ) {
	$filePath = "{$IP}/{$file}";
	list($hash) = explode(" ", shell_exec( "md5sum {$filePath}" ));
	$targetOut = "{$preProcessedDataDir}/{$hash}";

	if ( !file_exists( assetArchive($hash) ) ) {
		$originalContent = trim(file_get_contents( $filePath ));

		if (empty($originalContent)) {
			echo "ERROR: file has 0 length: {$file}\n";
			continue;
		}

		$dataOut = AssetsManagerBaseBuilder::minifyJS( trim( $originalContent ) );

		if (empty($dataOut)) {
			echo "ERROR: empty data result for {$file}\n";
		} elseif ( file_put_contents( $targetOut, $dataOut ) ) {
			copy( $targetOut, assetArchive( $hash ) );
			prepareAndLogStats($statsFile, $file, $originalContent, $dataOut);
			$hashes[ $file ] = $hash;
		}
	} elseif ( copy( assetArchive( $hash ), $targetOut ) ) {
		$hashes[ $file ] = $hash;
	} else {
		echo "ERROR: unable to complete asset {$file}\n";
	}
}

file_put_contents( $serializedOutputFile, serialize( $hashes ) );

function findAssetFiles( $directory, &$fileList ) {
	$newFiles = glob( "{$directory}/**.js" );
	array_walk( $newFiles, function ( &$file ) {
		global $IP;
		$file = str_replace( "{$IP}/", '', $file );
	} );

	$fileList = array_merge( $fileList, $newFiles );
	$dirs = glob( "{$directory}/*", GLOB_ONLYDIR );

	foreach ( $dirs as $dir ) {
		findAssetFiles( $dir, $fileList );
	}
}

function assetArchive( $md5 ) {
	return assetDir() . "/{$md5}";
}

function assetDir($dir=null) {
	static $assetDir = null;

	if ($assetDir === null) {
		echo "setting assetDir to ".ASSET_ARCHIVE_DIR."\n";
		$assetDir = ASSET_ARCHIVE_DIR;
	}

	if ($dir !== null && is_dir($dir) ) {
		echo "setting assetDir to {$dir}\n";
		$assetDir = $dir;
	}

	return $assetDir;
}

function logStats($statsFile, $header, $stats) {
	file_put_contents($statsFile, "{$header}\n", FILE_APPEND);
	foreach ($stats as $stat) {
		file_put_contents($statsFile, "\t{$stat}\n", FILE_APPEND);
	}
}

function prepareAndLogStats($statsFile, $file, $original, $new) {
	$oldSize = intval(strlen($original) / 1024);
	$newSize = intval(strlen($new) / 1024);

	$compressedContent = gzcompress($new);
	$compressedSize = strlen($compressedContent) > 1024 ? intval(strlen($compressedContent) / 1024)."kb" : strlen($compressedContent)."B";

	$log = [
		"Original Size: {$oldSize}kb",
		"Minified Size: {$newSize}kb",
		"Minification Ratio: ".intval( ( 1 - ( $newSize / $oldSize ) ) * 100 ) . "%",
		"Compressed Size: {$compressedSize}",
	];

	logStats($statsFile, $file, $log);
}