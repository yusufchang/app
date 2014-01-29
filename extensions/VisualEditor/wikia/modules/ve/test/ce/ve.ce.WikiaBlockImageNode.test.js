/*!
 * VisualEditor ContentEditable WikiaBlockImageNode tests.
 */

QUnit.module( 've.ce.WikiaBlockImageNode', ve.wikiaTest.utils.disableDebugModeForTests() );

/* Tests */

QUnit.test( 'HTMLDOM -> Linmod -> NodeView + attribute changes', function ( assert ) {
	ve.wikiaTest.utils.media.runNodeViewTransactionTests(
		assert,
		'block',
		'mw:Image',
		function( documentNode ) {
			return new ve.ce.WikiaBlockImageNode( documentNode.getChildren()[0] );
		}
	);
} );