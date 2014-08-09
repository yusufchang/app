document.addEventListener( 'DOMNodeInserted', verifySRP, false );
$( verifySRP );

/**
 * Verifies that the current page is a Google Search Results Page
 */
function verifySRP( e ) {
	if ( typeof e === 'function' ) {
		// Page load
		if ( $('#ires').length ) {
			scanSRP();
		}
	} else {
		// Mutation
		if ( $( e.target ).is( '#ires') ) {
			scanSRP();
		}
	}
};

/**
 * Scans the Search Results Page for links to Wikia
 */
function scanSRP() {
	var $link, $result, $results = $( '#ires li.g' );

	$results.each( function() {
		$result = $( this );
		$link = $result.find( 'h3 > a' ).first();

		if ( $link.length && $link.attr( 'href' ).indexOf('wikia.com') != -1 ) {
			redirectLinkToDev( $link );
			insertControls( $result, $link );
		}
	} );
};

function redirectLinkToDev( $link ) {
	$link.attr( 'href', prodToDev( $link.attr( 'href' ) ) );
}

/**
 * Inserts Time Machine controls for links to Wikia
 *
 * @param {jQuery} $result Result entry on SRP containing link to Wikia
 * @param {jQuery} $link Link to Wikia
 */
function insertControls( $result, $link ) {
	$.when( getShowData( $link ) ).then( function( showData ) {
		var i, seasonNumber,
			$wrapper = $( '<div class="WikiaTimeMachine"><p>Not all caught up? Read safely with Spoiler Guard.</p></div>' ),
			$season = $( '<select class="WikiaSeason"></select>' ),
			$episode = $( '<select class="WikiaEpisode"></select>' );

		// Demo hack
		if ( $link.attr( 'href' ).indexOf( 'breakingbad' ) !== -1 ) {
			return;
		}

		// Redact
		$result.find( '.st' )
			.css( {
				'background-color': '#545454',
				'opacity': '.5'
			} );

		// Season
		$season
			.append( '<option value="0">Choose a season</opiton>' )
			.on( 'change', { 'showData': showData, '$episode': $episode }, onSeasonChange );

		for ( i = 0; i < showData.seasons; i++ ) {
			seasonNumber = i + 1;
			$season.append( '<option value="' + seasonNumber + '">' + seasonNumber + '</option>');
		}

		// Episode
		$episode
			.on( 'change', { 'showData': showData, '$link': $link, '$season': $season }, onEpisodeChange )
			.hide();

		$wrapper.append( $season, $episode );
		$result.append( $wrapper );
	} );
};

/**
 * Handler for the episode dropdown change event
 *
 * @param {jQuery.Event} e
 */
function onEpisodeChange( e ) {
	var href = e.data.$link.attr('href'),
		subdomain = href.slice( href.indexOf( '://' ) + 3, href.indexOf( '.' ) ),
		timestamp = e.target.options[e.target.options.selectedIndex].value,
		seasonIndex = e.data.$season[0].options.selectedIndex,
		episodeIndex = e.target.options.selectedIndex;

	cookieData = {
		'subdomain': subdomain,
		'timestamp': timestamp,
		'season': seasonIndex,
		'episode': episodeIndex
	};

	//send message to background
	chrome.runtime.sendMessage(
		cookieData,
		function( response ) {
			document.location = href;
		}
	);
};

/**
 * Converts a production Wikia URL to a devbox URL
 *
 * @param {string} url The url to convert
 * @returns {string}
 */
function prodToDev( url ) {
	return url.replace( 'wikia', 'christian.wikia-dev' );
};

/**
 * Handler for the season dropdown change event
 *
 * @param {jQuery.Event} e
 */
function onSeasonChange( e ) {
	var seasonNumber = e.target.options.selectedIndex,
		episodes = e.data.showData.episodes[seasonNumber];

	e.data.$episode.empty();

	if ( seasonNumber == 0 ) {
		e.data.$episode.hide();
	} else {
		//Load appropriate options
		e.data.$episode
			.append( '<option>Choose an episode</opiton> ')
			.show();

		for ( i = 0; i < episodes.length; i++ ) {
			e.data.$episode.append( '<option value="' + episodes[i][2] + '">' + episodes[i][1] + '</option>' );
		}
	}
};

/**
 * Gets the structured data about the show
 *
 * @param {jQuery} $link Link to Wikia
 * @returns {jQuery.promise}
 */
function getShowData( $link ) {
	var $show, $episodeList, $season, seasonNumber, $episode,
		href = $link.attr('href'),
		subdomain = href.slice( href.indexOf( '://' ) + 3, href.indexOf( '.' ) ),
		dfd = $.Deferred();

	$.get( 'http://' + subdomain + '.christian.wikia-dev.com/wikia.php?controller=TimeMachine&method=index&format=json&view=activation', function( response ) {
		dfd.resolve( JSON.parse( response.showData ) );
	} );

	return dfd.promise();
};
