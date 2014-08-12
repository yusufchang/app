$(function () {
	'use strict';

	var TimeMachine = {

		// Pretend-immutable
		COOKIE_NAME: 'time_machine',
		subdomain: window.location.hostname.split('.')[0],
		cookieDomain: window.location.hostname.split('.').slice(-2).join('.'),
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
			} else if (referrer && referrer.match(/google\.com$/)) {
				viewType = wikiData ? 'status' : 'activation';
			}

			$.nirvana.getJson('TimeMachine', 'index', {
				view: viewType
			})
				.done(function (data) {

					if (viewType === 'status') {
						TimeMachine.bar = $('<div>', {
							id: 'TimeMachine'
						})
							.addClass('status')
							.html(data.content)
							.insertAfter('#WikiaHeader')
							.find('.close').on('click', function () {
								TimeMachine.clearCookie();
								window.location.reload();
							});
						TimeMachine.insertControls(JSON.parse(data.showData), wikiData);
					} else if (viewType === 'activation') {
						TimeMachine.bar = $('<div>', {
							id: 'TimeMachine'
						})
							.addClass('activation')
							.html(data.content)
							.insertAfter('header#WikiaPageHeader');

						TimeMachine.insertControls(JSON.parse(data.showData), wikiData);
					}
				});
		},

		/**
		 * Inserts Time Machine controls for links to Wikia
		 *
		 */
		insertControls: function (showData, wikiData) {
			var i, seasonNumber,
				$season = $('#TimeMachine .WikiaSeason'),
				$episode = $('#TimeMachine .WikiaEpisode'),
				seasonSelected = false,
				selectedText = '',
				curSeason,
				child;

			//Season
			$season
				.append('<option value="0">Choose a season</opiton>')
				.on('change', {
					'showData': showData,
					'$episode': $episode
				}, TimeMachine.onSeasonChange);

			for (i = 0; i < showData.seasons; i++) {
				seasonNumber = i + 1;

				selectedText = '';
				curSeason = wikiData ? wikiData.season : -1;
				if (seasonNumber === curSeason) {
					selectedText = ' selected';
					seasonSelected = true;
				} else {
					selectedText = '';
				}
				$season.append(
					'<option value="' + seasonNumber + '"' + selectedText + '>' +
					seasonNumber +
					'</option>');
			}

			//Episode
			$episode
				.on('change', {
					'$season': $season
				}, TimeMachine.onEpisodeChange)
				.hide();

			if (seasonSelected) {
				$season.change();
				if (wikiData.episode) {
					child = wikiData.episode + 1;
					$episode.find(':nth-child(' + child + ')').prop('selected', true);
				}
			}
		},

		clearCookie: function () {
			var cookieObject = JSON.parse($.cookie(TimeMachine.COOKIE_NAME));

			delete cookieObject[TimeMachine.subdomain];

			$.cookie(TimeMachine.COOKIE_NAME, JSON.stringify(cookieObject), {
				'domain': TimeMachine.cookieDomain,
				'path': '/'
			});
		},

		/**
		 * Handler for the episode dropdown change event
		 *
		 * @param {jQuery.Event} e
		 */
		onEpisodeChange: function (e) {
			var timestamp = e.target.options[e.target.options.selectedIndex].value,
				seasonIndex = e.data.$season[0].options.selectedIndex,
				episodeIndex = e.target.options.selectedIndex,
				tmCookie;

			tmCookie = JSON.parse($.cookie('time_machine')) || {};
			tmCookie[TimeMachine.subdomain] = {
				'timestamp': timestamp,
				'season': seasonIndex,
				'episode': episodeIndex
			};

			$.cookie('time_machine', JSON.stringify(tmCookie), {
				path: '/',
				domain: TimeMachine.cookieDomain
			});
			document.location.reload();
		},

		/**
		 * Handler for the season dropdown change event
		 *
		 * @param {jQuery.Event} e
		 */
		onSeasonChange: function (e) {
			var seasonNumber = e.target.options.selectedIndex,
				episodes = e.data.showData.episodes[seasonNumber],
				i;

			e.data.$episode.empty();

			if (seasonNumber === 0) {
				e.data.$episode.hide();
			} else {
				//Load appropriate options
				e.data.$episode
					.append('<option>Choose an episode</opiton> ')
					.show();

				for (i = 0; i < episodes.length; i++) {
					e.data.$episode.append('<option value="' + episodes[i][2] + '">' + episodes[i][1] + '</option>');
				}
			}
		}
	};

	TimeMachine.init();

});
