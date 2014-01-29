/*!
 * VisualEditor DataModel Wikia example data sets.
 */

/**
 * @namespace
 * @ignore
 */
ve.dm.wikiaExample = ( function ( veDmExample ) {
	var media = {},
		slice = Array.prototype.slice;

	function assemble() {
		var args = slice.call( arguments ),
			i = 0,
			result = [];

		for ( ; i < args.length; i++ ) {
			result = result.concat( args[i] );
		}

		return result;
	}

	/* Linmod fragments */

	media.data = {
		'attributes': {
			'type': 'thumb',
			'align': 'right',
			'href': 'Foo',
			'src': 'Bar',
			'width': 1,
			'height': 2,
			'resource': 'FooBar',
			'originalClasses': 'mw-halign-right foobar',
			'unrecognizedClasses': [ 'foobar' ],
			'attribution': {
				'avatar': 'Foo.png',
				'username': 'Foo'
			}
		},
		'caption': [
			{ 'type': 'wikiaMediaCaption' },
			{ 'type': 'paragraph', 'internal': { 'generated': 'wrapper' } },
			'a', 'b', 'c',
			{ 'type': '/paragraph' },
			{ 'type': '/wikiaMediaCaption' }
		],
		'htmlAttributes': [ {
			'values': {
				'data-mw': '{"attribution":{"username":"Foo","avatar":"Foo.png"}}'
			}
		} ],
		'internalList': [
			{ 'type': 'internalList' },
			{ 'type': '/internalList' }
		]
	};

	/* Block media */

	media.block = {};

	/**
	 *
	 */
	media.getLinmod = function ( type, attributes ) {
		var linmod = [ {
			'type': type,
			'attributes': $.extend( {}, media.data.attributes, attributes ),
			'htmlAttributes': media.data.htmlAttributes
		} ];

		if ( /block/i.test( type ) ) {
			linmod.push( media.data.caption );
		}

		linmod = linmod.concat( [
			{ 'type': '/' + type },
			media.data.internalList
		] );

		return linmod;
	};

	// TODO: These should probably be built from the data above
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

	// Exports
	return {
		'createExampleDocument': createExampleDocument,
		'domToDataCases': domToDataCases,
		'media': media
	};
}( ve.dm.example ) );