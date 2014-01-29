/*!
 * VisualEditor ContentEditable WikiaBlockVideoNode tests.
 */

QUnit.module( 've.ce.WikiaBlockVideoNode', ve.wikiaTest.utils.disableDebugModeForTests() );

/* Tests */

QUnit.test( 'HTMLDOM -> Linmod -> NodeView + attribute changes', function ( assert ) {
	ve.wikiaTest.utils.media.runNodeViewTransactionTests(
		assert,
		'block',
		'mw:Video',
		function( documentNode ) {
			return new ve.ce.WikiaBlockVideoNode( documentNode.getChildren()[0] );
		}
	);
} );