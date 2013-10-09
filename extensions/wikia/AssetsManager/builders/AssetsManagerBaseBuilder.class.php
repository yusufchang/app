<?php

/**
 * @author Inez Korczyński <korczynski@gmail.com>
 */

class AssetsManagerBaseBuilder {
	const MINIFY_METHOD_JS_UGLIFYJS = 'uglifyjs';
	const MINIFY_METHOD_LEGACY = 'legacy';

	const SERIALIZED_FILE = 'preprocessed-asset-hashes.ser';

	protected $mOid;
	protected $mType;
	protected $mParams;
	protected $mCb;
	protected $mNoExternals;
	protected $mForceProfile;
	protected $mProfilerData = array();

	protected $mContent;
	protected $mContentType;
	protected $mCacheMode = 'public';

	public function __construct(WebRequest $request) {
		$this->mType = $request->getText('type');
		$this->mOid = preg_replace( '/\?.*$/', '', $request->getText('oid') );
		parse_str(urldecode($request->getText('params')), $this->mParams);
		$this->mCb = $request->getInt('cb');

		if (!empty($this->mParams['noexternals'])) {
			$this->mNoExternals = true;
		}

		if (!empty($this->mParams['forceprofile'])) {
			$this->mForceProfile = true;
		}
	}

	public function getContent( $processingTimeStart = null ) {
		$minifyTimeStart = null;

		if ( !empty( $this->mContent ) && ( !isset( $this->mParams['minify'] ) || $this->mParams['minify'] == true ) ) {
			if ( $this->mForceProfile ) {
				$minifyTimeStart = microtime( true );
			}

			$newContent = self::getPreProcessedAsset($this->mOid);
			if (!$newContent) {
				if ( $this->mContentType == AssetsManager::TYPE_CSS ) {
					$newContent = $this->minifyCSS( $this->mContent );
				} elseif ( $this->mContentType == AssetsManager::TYPE_JS ) {
					$useYUI = $this->mOid == 'oasis_shared_js' || $this->mOid == 'rte';
					$newContent = self::minifyJS( $this->mContent, $useYUI );
				}
			}
		}

		if ( !empty( $newContent ) ) {
			if ( $this->mForceProfile ) {
				$timeEnd = microtime( true );

				if ( $processingTimeStart ) {
					$this->mProfilerData[] = "Processing time: " . intval( ( $timeEnd - $processingTimeStart ) * 1000 ) . "ms";
				}

				if ( $minifyTimeStart ) {
					$this->mProfilerData[] = "Minification time: " . intval( ( $timeEnd - $minifyTimeStart ) * 1000 ) . "ms";
				}

				$oldSize = intval( strlen( $this->mContent ) / 1024 );
				$newSize = intval( strlen( $newContent ) / 1024 );

				$this->mProfilerData[] = "Compressed Size: " . $newSize . "kb";
				$this->mProfilerData[] = "Compression Ratio: " . intval( ( 1 - ( $newSize / $oldSize ) ) * 100 ) . "%";

				$newContent = "/* " . implode( " | ", $this->mProfilerData ) . " */\n\n" . $newContent;
			}

			$this->mContent = $newContent;
		}

		return $this->mContent;
	}

	public function getCacheDuration() {
		global $wgResourceLoaderMaxage, $wgStyleVersion;
		if($this->mCb > $wgStyleVersion) {
			Wikia::log(__METHOD__, false, "shorter TTL set for {$this->mOid}", true);
			return $wgResourceLoaderMaxage['unversioned'];
		} else {
			return $wgResourceLoaderMaxage['versioned'];
		}
	}

	public function getCacheMode() {
		return $this->mCacheMode;
	}

	public function getContentType() {
		return $this->mContentType;
	}

	public function getVary() {
		return 'Accept-Encoding';
	}

	public static function minifyJS($content, $useYUI = false) {
		global $IP, $wgMinifyMethod;
		wfProfileIn(__METHOD__);

		$tempInFile = tempnam(sys_get_temp_dir(), 'AMIn');
		file_put_contents($tempInFile, $content);

		switch ($wgMinifyMethod) {
			case self::MINIFY_METHOD_JS_UGLIFYJS:
				$tempOutFile = tempnam( sys_get_temp_dir(), 'AMOut' );
				shell_exec("uglifyjs -m -c warnings=false -o {$tempOutFile} < {$tempInFile}");
				$out = file_get_contents($tempOutFile);
				unlink($tempOutFile);
				break;
			default:
				if ( $useYUI ) {
					$tempOutFile = tempnam( sys_get_temp_dir(), 'AMOut' );
					shell_exec( "nice -n 15 java -jar {$IP}/lib/vendor/yuicompressor-2.4.2.jar --type js -o {$tempOutFile} {$tempInFile}" );
					$out = file_get_contents( $tempOutFile );
					unlink( $tempOutFile );
				} else {
					$jsmin = "{$IP}/lib/vendor/jsmin";
					$out = shell_exec( "cat $tempInFile | $jsmin" );
				}
		}

		unlink($tempInFile);
		wfProfileOut(__METHOD__);
		return $out;
	}

	private function minifyCSS($content) {
		wfProfileIn(__METHOD__);
		$out = Minify_CSS_Compressor::process($content);
		wfProfileOut(__METHOD__);
		return $out;
	}

	protected static function getPreProcessedAsset( $oid ) {
		global $IP, $wgAssetsManagerAllowedPreProcessedAssets;

		if ( !$wgAssetsManagerAllowedPreProcessedAssets ) {
			return false;
		}

		static $preProcessedAssets = null;
		if ( !isset( $preProcessedAssets[ $oid ] ) ) {
			$preProcessedAssets = wfGetPrecompiledData( self::SERIALIZED_FILE );
		}

		if ( !isset( $preProcessedAssets[ $oid ] ) ) {
			return false;
		}

		$assetFile = "{$IP}/resources/preProcessed/{$preProcessedAssets[$oid]}";

		if ( !file_exists( $assetFile ) ) {
			return false;
		}

		return file_get_contents( $assetFile );
	}
}
