/*!
 * VisualEditor Wikia test utilities.
 *
 * TODO: determine what can be moved to ve.test.utils.js
 */

/**
 * @namespace
 * @ignore
 */
ve.wikiaTest = ( function () {
	var utils = {};

	/**
	 * Disables debug mode while tests are running.
	 *
	 * @method
	 * @static
	 * @returns {Object} Setup and teardown methods for module.
	 */
	utils.disableDebugModeForTests = function () {
		var debug;
		return {
			setup: function() {
				debug = ve.debug;
				ve.debug = false;
			},
			teardown: function() {
				ve.debug = debug;
			}
		};
	};

	/**
	 * Generates a string which describes an objects contents.
	 * @example 'key1: value1, key2: value2'
	 *
	 * @method
	 * @static
	 * @param {Object} obj The object to describe.
	 * @param {String} joinStr The string used to join keys and values.
	 * @param {String} pairStr The string used to join key/value pairs.
	 * @returns {String} The object description string.
	 */
	utils.getObjectDescription = function ( obj, joinStr, pairStr ) {
		var key,
			parts = [];

		joinStr = joinStr || ', ';
		pairStr = pairStr || ': ';

		for ( key in obj ) {
			parts.push( key + pairStr + obj[ key ] );
		}

		return parts.join( joinStr );
	};

	/**
	 * Creates the diff of an object based on value.
	 * Null values are ignored.
	 *
	 * @method
	 * @static
	 * @param {Object} first The base object.
	 * @param {Object} second The object to compare values from.
	 * @returns {Object} An object containing all the different values.
	 */
	utils.getObjectDiff = function ( first, second ) {
		var key,
			result = {},
			value;

		for ( key in second ) {
			value = second[ key ];
			if ( value !== null && first[ key ] !== value ) {
				result[ key ] = value;
			}
		}

		return result;
	};

	/**
	 * Generates all of the permutations from the given data to produce
	 * a set of test cases to use in unit tests.
	 *
	 * @method
	 * @static
	 * @param {Object} data An object containing arrays of possible values for each key.
	 * @returns {Array} An array of test case permutations.
	 */
	utils.getTestCases = function ( data ) {
		var i,
			j,
			k,
			l,
			testCases = [];

		// TODO: make this actually recursive
		for ( i = 0; i < data.align.length; i++ ) {
			for ( j = 0; j < data.height.length; j++ ) {
				for ( k = 0; k < data.type.length; k++ ) {
					for ( l = 0; l < data.width.length; l++ ) {
						testCases.push({
							align: data.align[ i ],
							height: data.height[ j ],
							type: data.type[ k ],
							width: data.width[ l ]
						});
					}
				}
			}
		}

		return testCases;
	};

	/**
	 * Uppercase the first letter in a string.
	 *
	 * @method
	 * @static
	 * @param {String} str The string.
	 * @returns {String} The string with the first letter uppercased.
	 */
	utils.ucFirst = function ( str ) {
		return str.charAt( 0 ).toUpperCase() + str.slice( 1 );
	};

	/* Media Utils */

	utils.media = {};

	/**
	 * Runs the QUnit tests for performing transaction changes on node views.
	 * Should be used inside of a QUnit.test()
	 *
	 * @method
	 * @static
	 * @param {Object} assert QUnit.assert object
	 * @param {String} displayType The node's display type: 'block' or 'inline'
	 * @param {String} rdfaType The node's RDFa type: 'mw:Image' or 'mw:Video'
	 * @param {Function} getNodeView A function that returns the proper node view from a document node
	 */
	utils.media.runNodeViewTransactionTests = function ( assert, displayType, rdfaType, getNodeView ) {
		var children, current, diff, htmlDocument, i, j, merged, nodeView,
			documentModel = new ve.dm.Document( [] ),
			media = ve.ce.wikiaExample.media,
			previous = {},
			getHtml = media[ displayType ][ rdfaType ].getHtml,
			testCases = utils.getTestCases( media.data.testCases[ displayType ][ rdfaType ] );

		for ( i = 0; i < testCases.length; i++ ) {
			current = testCases[i];
			diff = utils.getObjectDiff( previous, current );
			merged = ve.extendObject( {}, previous, current, true );

			if ( i === 0 ) {
				// TODO: use data directly instead of getting it from HTMLDOM
				htmlDocument = ve.createDocumentFromHtml(
					media.getHtmlDom( displayType, rdfaType, current )
				);
				documentModel = new ve.dm.Document( ve.dm.converter.getDataFromDom(
					htmlDocument,
					documentModel.getStore(),
					documentModel.getInternalList(),
					documentModel.getInnerWhitespace
				) );

				nodeView = getNodeView( documentModel.getDocumentNode() );
			} else {
				documentModel.commit( new ve.dm.Transaction.newFromAttributeChanges(
					documentModel, nodeView.getOffset(), diff
				) );
				nodeView.emit( 'teardown' );
			}

			nodeView.emit( 'setup' );

			assert.equalDomStructure(
				nodeView.$element,
				getHtml( merged ),
				'Attributes: ' + utils.getObjectDescription( diff )
			);

			previous = merged;
		}

		QUnit.expect( testCases.length );
	};

	// Exports
	return { 'utils': utils };
}() );
