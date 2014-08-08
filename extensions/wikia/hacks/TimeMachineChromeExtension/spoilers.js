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
		if ( $link.attr( 'href' ).indexOf('wikia.com') != -1 ) {
			insertControls( $result, $link );
		}
	} );
};

/**
 * Inserts Time Machine controls for links to Wikia
 *
 * @param {jQuery} $result Result entry on SRP containing link to Wikia
 * @param {jQuery} $link Link to Wikia
 */
function insertControls( $result, $link ) {
	$.when( getShowData( $link ) ).then( function( showData ) {
		var i, seasonNumber,
			$wrapper = $( '<div class="WikiaTimeMachine"></div>' ),
			$season = $( '<select class="WikiaSeason"></select>' ),
			$episode = $( '<select class="WikiaEpisode"></select>' );

		//Season
		$season
			.append( '<option value="0">Choose a season</opiton>' )
			.on( 'change', { 'showData': showData, '$episode': $episode }, onSeasonChange );

		for ( i = 0; i < showData.seasons; i++ ) {
			seasonNumber = i + 1;
			$season.append( '<option value="' + seasonNumber + '">' + seasonNumber + '</option>');
		}

		//Episode
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
		subdomain = href.slice( href.indexOf( '://' ) + 3, href.indexOf( '.wikia.com' ) ),
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
		showData = {
			'name': '',
			'id': '',
			'seasons': '',
			'episodes': {}
		},
		dfd = $.Deferred();

	$.get( 'http://services.tvrage.com/feeds/search.php?show=' + $link.text(), function( doc ) {
		$show = $( doc ).find( 'show' ).first();
		showData.name = $show.find( 'name' ).text();
		showData.id = $show.find( 'showid' ).text();
		showData.seasons = $show.find( 'seasons' ).text();

		$.get( 'http://services.tvrage.com/feeds/episode_list.php?sid=' + showData.id, function( doc ) {
			$episodeList = $( doc ).find( 'Show Episodelist' );

			// Iterate through season list
			$episodeList.find('Season').each( function() {
				$season = $( this );
				seasonNumber = $season.attr('no');
				showData.episodes[ seasonNumber ] = [];

				// Iterate through a season's episode list
				$season.find( 'episode' ).each( function( i ) {
					$episode = $( this );
					showData.episodes[ seasonNumber ].push( [
						i + 1,
						$episode.find( 'title' ).text().replace( '-', ' ' ),
						new Date( $episode.find( 'airdate' ).text() ).getTime()
					] );
				} );
			} );
			//return showData;
			dfd.resolve( showData );
		} );
	} );
	return dfd.promise();
};
