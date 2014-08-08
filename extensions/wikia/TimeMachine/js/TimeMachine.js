$(function () {
	'use strict';

	var TimeMachine = {

		// Pretend-immutable
		COOKIE_NAME: 'time_machine',
		subdomain: window.location.hostname.split('.')[0],
		bar: false,

		init: function () {
			var data = $.cookie(this.COOKIE_NAME),
				timeMachineData,
				wikiData = '',
				viewType = '',
				referrer = document.referrer.split('/')[2];

			if (data) {
				timeMachineData = JSON.parse(data);
				wikiData = timeMachineData[TimeMachine.subdomain];
			}

			// If we have data for this wiki use the status view.  Otherwise use the
			// activation view.
			if (wikiData) {
				viewType = 'status';
			} else if ( referrer.match(/google\.com$/) ) {
				viewType = wikiData ? 'status' : 'activation';
			}

			$.nirvana.getJson('TimeMachine', 'index', { view: viewType })
				.done(function (data) {

					console.log('viewType == ' + viewType);
					if (viewType === 'status') {
						TimeMachine.bar = $('<div>', {id: 'TimeMachine'}).
							html(data.content).

							insertAfter('#WikiaHeader').
							find( '.close' ).on( 'click', function () {
								TimeMachine.clearCookie( window.location.hostname.split('.')[0] );
								window.location.reload();
							} );
						TimeMachine.insertControls();
					} else if (viewType === 'activation') {
						TimeMachine.bar = $('<div>', {id: 'TimeMachine'}).
							html(data.content).
							insertAfter('header#WikiaPageHeader');
					}
				});
		},

		/**
		 * Inserts Time Machine controls for links to Wikia
		 *
		 */
		insertControls: function () {
			$.when( TimeMachine.getShowData() ).then( function( showData ) {
				var i, seasonNumber,
					$season = $('#TimeMachine .WikiaSeason'),
					$episode = $('#TimeMachine .WikiaEpisode');

				//Season
				$season
					.append( '<option value="0">Choose a season</opiton>' )
					.on( 'change', { 'showData': showData, '$episode': $episode }, TimeMachine.onSeasonChange );

				for ( i = 0; i < showData.seasons; i++ ) {
					seasonNumber = i + 1;
					$season.append( '<option value="' + seasonNumber + '">' + seasonNumber + '</option>');
				}

				//Episode
				$episode
					.on( 'change', { '$season': $season }, TimeMachine.onEpisodeChange )
					.hide();
			});
		},

		clearCookie: function () {
			var cookies = document.cookie.split( ';' ),
				host = window.location.hostname.split( '.' ),
				newValue = '';

			for ( var i in cookies ) {
				// Search through all cookies for the cookie
				if ( cookies[i].trim().indexOf( this.COOKIE_NAME ) === 0 ) {
					// Convert the cookie value to an object
					var cookieObj = JSON.parse( cookies[i].substr( cookies[i].indexOf('=') + 1 ) );
					// Remove the setting for the specified wiki
					delete cookieObj[host[0]];
					// Convert the object back to a string
					newValue = JSON.stringify( cookieObj );
					break;
				}
			}

			// Set the new value of the cookie (might be "{}")
			document.cookie = this.COOKIE_NAME + '=' + newValue + ';path=/;domain=.' + host[host.length - 2] + '.' + host[host.length - 1];
		},

		/**
		 * Handler for the episode dropdown change event
		 *
		 * @param {jQuery.Event} e
		 */
		onEpisodeChange: function ( e ) {
			var timestamp = e.target.options[e.target.options.selectedIndex].value,
				seasonIndex = e.data.$season[0].options.selectedIndex,
				episodeIndex = e.target.options.selectedIndex,
				tmCookie;

			tmCookie = $.cookie('time_machine') || {};
			tmCookie[TimeMachine.subdomain] = {
				'timestamp': timestamp,
				'season': seasonIndex,
				'episode': episodeIndex
			};
			$.cookie('time_machine', JSON.stringify(tmCookie), {path: '/'});
			document.location.reload();
		},

		/**
		 * Handler for the season dropdown change event
		 *
		 * @param {jQuery.Event} e
		 */
		onSeasonChange: function ( e ) {
			var seasonNumber = e.target.options.selectedIndex,
				episodes = e.data.showData.episodes[seasonNumber];

			e.data.$episode.empty();

			if ( seasonNumber === 0 ) {
				e.data.$episode.hide();
			} else {
				//Load appropriate options
				e.data.$episode
					.append( '<option>Choose an episode</opiton> ')
					.show();

				for ( var i = 0; i < episodes.length; i++ ) {
					e.data.$episode.append( '<option value="' + episodes[i][2] + '">' + episodes[i][1] + '</option>' );
				}
			}
		},

		/**
		 * Gets the structured data about the show
		 *
		 * @returns {jQuery.promise}
		 */
		getShowData: function () {
			var $show, $episodeList, $season, seasonNumber, $episode,
				showData = {
					'name': '',
					'id': '',
					'seasons': '',
					'episodes': {}
				},
				dfd = $.Deferred();

			$.get( 'http://services.tvrage.com/feeds/search.php?show=' + TimeMachine.subdomain, function( doc ) {
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
								new Date( $episode.find( 'airdate' ).text() ).getTime() / 1000
							] );
						});
					});
					//return showData;
					dfd.resolve( showData );
				});
			});
			return dfd.promise();
		}
	};

	TimeMachine.init();

});
