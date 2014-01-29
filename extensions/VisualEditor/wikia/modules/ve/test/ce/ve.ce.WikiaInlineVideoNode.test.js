/*!
 * VisualEditor ContentEditable WikiaInlineVideoNode tests.
 */

QUnit.module( 've.ce.WikiaInlineVideoNode', ve.wikiaTest.utils.disableDebugModeForTests() );

/* Tests */

QUnit.test( 'HTMLDOM -> Linmod -> NodeView + attribute changes', function ( assert ) {
	ve.wikiaTest.utils.media.runNodeViewTransactionTests(
		assert,
		'inline',
		'mw:Video',
		function( documentNode ) {
			return new ve.ce.WikiaInlineVideoNode( documentNode.getChildren()[0].getChildren()[0] );
		}
	);
} );