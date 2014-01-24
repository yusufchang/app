/*!
 * VisualEditor DataModel Wikia example data sets.
 */

/**
 * @namespace
 * @ignore
 */
ve.dm.wikiaExample = ( function ( veDmExample ) {
	var domToDataCases = {
		'thumb image': {
			'body': '<figure typeof="mw:Image/Thumb" class="mw-halign-right foobar" data-mw=\'{"attribution":{"username":"Foo","avatar":"Foo.png"}}\'><a href="Foo"><img src="Bar" width="1" height="2" resource="FooBar"></a><figcaption>abc</figcaption></figure>',
			'data': [
				{
					'type': 'wikiaBlockImage',
					'attributes': {
						'type': 'thumb',
						'align': 'right',
						'href': 'Foo',
						'src': 'Bar',
						'width': 1,
						'height': 2,
						'resource': 'FooBar',
						'originalClasses': 'mw-halign-right foobar',
						'unrecognizedClasses': ['foobar'],
						'attribution': {
							'avatar': 'Foo.png',
							'username': 'Foo'
						}
					},
					'htmlAttributes': [ {
						'values': {
							'data-mw': '{"attribution":{"username":"Foo","avatar":"Foo.png"}}'
						}
					} ]
				},
				{ 'type': 'wikiaMediaCaption' },
				{ 'type': 'paragraph', 'internal': { 'generated': 'wrapper' } },
				'a', 'b', 'c',
				{ 'type': '/paragraph' },
				{ 'type': '/wikiaMediaCaption' },
				{ 'type': '/wikiaBlockImage' },
				{ 'type': 'internalList' },
				{ 'type': '/internalList' }
			]
		},
		'thumb video': {
			'body': '<figure typeof="mw:Video/Thumb" class="mw-halign-right foobar" data-mw=\'{"attribution":{"username":"Foo","avatar":"Foo.png"}}\'><a href="Foo"><img src="Bar" width="1" height="2" resource="FooBar"></a><figcaption>abc</figcaption></figure>',
			'data': [
				{
					'type': 'wikiaBlockVideo',
					'attributes': {
						'type': 'thumb',
						'align': 'right',
						'href': 'Foo',
						'src': 'Bar',
						'width': 1,
						'height': 2,
						'resource': 'FooBar',
						'originalClasses': 'mw-halign-right foobar',
						'unrecognizedClasses': ['foobar'],
						'attribution': {
							'avatar': 'Foo.png',
							'username': 'Foo'
						}
					},
					'htmlAttributes': [ {
						'values': {
							'data-mw': '{"attribution":{"username":"Foo","avatar":"Foo.png"}}'
						}
					} ]
				},
				{ 'type': 'wikiaMediaCaption' },
				{ 'type': 'paragraph', 'internal': { 'generated': 'wrapper' } },
				'a', 'b', 'c',
				{ 'type': '/paragraph' },
				{ 'type': '/wikiaMediaCaption' },
				{ 'type': '/wikiaBlockVideo' },
				{ 'type': 'internalList' },
				{ 'type': '/internalList' }
			]
		}
	};

	function createExampleDocument( name, store ) {
		return veDmExample.createExampleDocumentFromObject( name, store, ve.dm.wikiaExample );
	}

	function getDataFromHtmlDom( doc, htmlDom ) {
		return ve.dm.converter.getDataFromDom(
			ve.createDocumentFromHtml( htmlDom ), doc.getStore(), doc.getInternalList(), doc.getInnerWhitespace()
		);
	}

	// Exports
	return {
		'createExampleDocument': createExampleDocument,
		'domToDataCases': domToDataCases,
		'getDataFromHtmlDom': getDataFromHtmlDom
	};
}( ve.dm.example ) );