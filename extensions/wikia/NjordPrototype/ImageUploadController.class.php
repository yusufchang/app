<?php

class ImageUploadController {

	/**
	 * Save previously uploaded image
	 * @param string $imageName
	 */
	public static function save( $imageName, $fileName ) {
		$stash = RepoGroup::singleton()->getLocalRepo()->getUploadStash();
		$tempFile = $stash->getFile( $imageName );
		$file = new LocalFile( $fileName, RepoGroup::singleton()->getLocalRepo() );

		$status = $file->upload( $tempFile->getPath(), '', '' );
		if ( $status->isOK() ) {
			//clean up stash
			$stash->removeFile( $image );
			return $file;
		}
		return false;
	}

	public static function uploadFromUrl( $url ) {
		$uploader = new UploadFromUrl();
		$uploader->initializeFromRequest( new FauxRequest(
			[
				'wpUpload' => 1,
				'wpSourceType' => 'web',
				'wpUploadFileURL' => $url
			],
			true
		) );
		$status = $uploader->fetchFile();
		if ( $status->isGood() ) {
			return [ 'status' => true, 'file' => self::getStashFile( $uploader->getTempPath() ) ];
		} else {
			return [ 'status' => false, 'error' => self::getUploadUrlErrorMessage( $status ) ];
		}
	}

	protected static function getStashFile( $path ) {
		$stash = RepoGroup::singleton()->getLocalRepo()->getUploadStash();
		$stashFile = $stash->stashFile( $path );
		return $stashFile;
	}

	protected static function getUploadUrlErrorMessage( $status ) {
		return "";
	}

	public static function uploadFromFile(WebRequest $webRequest) {
		$uploader = new UploadFromFile();
		$uploader->initialize( $webRequest->getFileName( 'file' ), $webRequest->getUpload( 'file' ) );
		$verified = $uploader->verifyUpload();
		if ( $verified[ 'status' ] == 0 || $verified[ 'status' ] == 10 ) {
			return [ 'status' => true, 'file' => self::getStashFile( $uploader->getTempPath() ) ];
		} else {
			return [ 'status' => false, 'error' => self::getUploadFileErrorMessage( $uploader, $verified ) ];
		}
	}

	protected static function getUploadFileErrorMessage( UploadFromFile $uploader, $verified ) {
		$errorReadable = $uploader->getVerificationErrorCode( $verified[ 'status' ] );
		return wfMessage( $errorReadable )->parse();
	}
}