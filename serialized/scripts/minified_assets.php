<?php
if ( defined( 'MEDIAWIKI' ) ) {
	exit( 1 );
}

require_once( dirname( __FILE__ ) . '/../../maintenance/commandLine.inc' );
define( 'ASSET_ARCHIVE_DIR', '/home/nelson/asset-bak' ); // TODO: what's a good default for this?

if (isset($argv[1])) {
	assetDir($argv[1]);
}

$preProcessedDataDir = dirname( __FILE__ ) . '/../../resources/preProcessed';
$serializedOutputFile = dirname( __FILE__ ) . '/../' . AssetsManagerBaseBuilder::SERIALIZED_FILE;
$wgMinifyMethod = AssetsManagerBaseBuilder::MINIFY_METHOD_JS_UGLIFYJS;
$wgAssetsManagerAllowedPreProcessedAssets = false;

$hashes = array();
$config = new AssetsConfig();
$request = new FauxRequest( [
	'type' => 'group',
	'params' => [
		'minify' => true,
	]
] );

if ( !is_dir( $preProcessedDataDir ) ) {
	mkdir( $preProcessedDataDir, 0755 );
}

// build up group files
$targetGroups = $config->getGroupNames();
foreach ( $targetGroups as $groupName ) {
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
}

// build up individual files
$fileList = [ ];
findAssetFiles( $IP, $fileList );

foreach ( array_unique( $fileList ) as $file ) {
	$filePath = "{$IP}/{$file}";
	list($hash) = explode(" ", shell_exec( "md5sum {$filePath}" ));
	$targetOut = "{$preProcessedDataDir}/{$hash}";

	if ( !file_exists( assetArchive($hash) ) ) {
		$dataOut = AssetsManagerBaseBuilder::minifyJS( trim( file_get_contents( $filePath ) ) );

		if (empty($dataOut)) {
			echo "ERROR: empty data result for {$file}\n";
		} elseif ( file_put_contents( $targetOut, $dataOut ) ) {
			copy( $targetOut, assetArchive( $hash ) );
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