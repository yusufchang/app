<?php

class ContentReviewControllerTest extends WikiaBaseTest {
	public function setUp() {
		$this->setupFile = __DIR__ . '/../ContentReview.setup.php';
		parent::setUp();
	}

	/**
	 * @dataProvider rawPageHookProvider
	 * Test for \Wikia\ContentReview\Hooks::onRawPageViewBeforeOutput hook
	 */
	public function testRawPageHook( $params, $message ) {

		/* @var \Title $titleMock */
		$titleMock = $this->getMockBuilder( '\Title' )
			->disableOriginalConstructor()
			->setMethods( [ 'getArticleID', 'getLatestRevID', 'isJsPage' ] )
			->getMock();
		$titleMock->expects( $this->once() )
			->method( 'getArticleID' )
			->will( $this->returnValue( $params['pageId'] ) );
		$titleMock->expects( $this->once() )
			->method( 'getLatestRevID' )
			->will( $this->returnValue( $params['lastRevId'] ) );
		$titleMock->expects( $this->once() )
			->method( 'isJsPage' )
			->will( $this->returnValue( $params['isJsPage'] ) );

		$rawActionMock = $this->getMockBuilder( 'RawAction' )
			->disableOriginalConstructor()
			->setMethods( [] )
			->getMock();

		$rawActionMock->expects( $this->once() )
			->method( 'getTitle' )
			->will( $this->returnValue( $titleMock ) );
		$rawActionMock->expects( $this->once() )
			->method( 'getContentType' )
			->will( $this->returnValue( $params['contentType'] ) );

//		$this->mockGlobalVariable( 'wgUser', $userMock );

		$currentRevisionModelMock = $this->getMockBuilder( '\Wikia\ContentReview\Models\CurrentRevisionModel' )
			->disableOriginalConstructor()
			->setMethods( [ 'getTitle', 'getContentType' ] )
			->getMock();

		$currentRevisionModelMock->expects( $this->once() )
			->method( 'getLatestReviewedRevision' )
			->will( $this->returnValue( 'kamil' ) );

		$this->mockClass( 'CurrentRevisionModel', $currentRevisionModelMock );

		$result = \Wikia\ContentReview\Hooks::onRawPageViewBeforeOutput( $rawActionMock, $text );

		$this->assertEquals( $result, true, $message );
	}

	public function rawPageHookProvider() {
		return [
			[
				[
					'pageId' => 123,
					'lastRevId' => 567,
					'isJsPage' => true,
					'contentType' => 'sometype',
				],
				false,
				'Admins cannot edit MediaWiki JS pages',
			],
		];
	}
}
