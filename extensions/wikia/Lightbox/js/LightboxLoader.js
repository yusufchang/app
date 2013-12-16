/*global Lightbox:true, LightboxTracker:true*/

(function(window, $){

var LightboxLoader = {
	// cached thumbnail arrays and detailed info
	cache: {
		articleMedia: [], // Article Media
		relatedVideos: [], // Related Video
		latestPhotos: [], // Latest Photos from DOM
		wikiPhotos: [], // Back fill of photos from wiki
		details: {}, // all media details
		share: {},
		to: 0
	},
	inlineVideoLinks: $(),	// jquery array of inline video links
	lightboxLoading: false,
	inlineVideoLoading: [],
	videoInstance: null,
	pageAds: $('#TOP_RIGHT_BOXAD'), // if more ads start showing up over lightbox, add them here
	defaults: {
		// start with default modal options
		id: 'LightboxModal',
		className: 'LightboxModal',
		width: 970, // modal adds 30px of padding to width
		noHeadline: true,
		topOffset: 25,
		height: 628,
		videoHeight: 360,
		onClose: function() {
			// Reset lightbox
			$(window).off('.Lightbox');
			// bugid-64334 and bugid-69047
			Lightbox.openModal.find('.video-media').children().remove();
			LightboxLoader.lightboxLoading = false;
			// Update history api (remove "?file=" from URL)
			Lightbox.updateUrlState(true);
			// Reset carousel
			Lightbox.current.thumbs = []; /* global Lightbox */
			Lightbox.current.thumbTypesAdded = [];
			Lightbox.to = LightboxLoader.cache.to;
			// Reset Ad Flags
			Lightbox.ads.adMediaProgress = [];
			Lightbox.ads.adMediaShown = 0;
			Lightbox.ads.adMediaShownSinceLastAd = 0;
			Lightbox.ads.adIsShowing = false;
			// Re-show box ad
			LightboxLoader.pageAds.css('visibility','visible');
			// Reset tracking
			Lightbox.clearTrackingTimeouts();
			// If a video uses a timeout for tracking, clear it
			if ( LightboxLoader.videoInstance ) {
				LightboxLoader.videoInstance.clearTimeoutTrack();
			}
			window.LightboxIsOpen = false;
			window.lightboxIsLoading = false;
		}
	},
	videoThumbWidthThreshold: 400,
	init: function() {
		var that = this,
			article = $('#WikiaArticle'),
			videos = $('#RelatedVideosRL'),
			photos = $('#LatestPhotosModule'),
			comments = $('#WikiaArticleComments');

		// Bind click event to initiate lightbox
		article.add(photos).add(videos).add(comments)
			.off('.lightbox')
			.on('click.lightbox', '.lightbox, a.image', function(e) {

				var $this = $(this),
					$thumb = $this.children('img').first(),
					fileKey = $thumb.attr('data-image-key') || $thumb.attr('data-video-key'),
					parent,
					isVideo;

				if( $this.hasClass('link-internal') || $this.hasClass('link-external') || $thumb.attr('data-shared-help') || $this.hasClass( 'no-lightbox' ) ) {
					return;
				}

				e.preventDefault();

				if($this.closest(article).length) {
					parent = article;
				} else if($this.closest(videos).length) {
					parent = videos;
				} else if($this.closest(photos).length) {
					parent = photos;
				} else if($this.closest(comments).length) {
					parent = comments;
				}

				var trackingInfo = {
					target: $this,
					parent: parent
				};

				// Handle edge cases

				// Allow links to open lightbox without a thumbnail. The link itself must contain data-image-key. Used in RelatedVideos.
				if($this.hasClass('lightbox-link-to-open')) {
					fileKey = $this.attr('data-image-key') || $this.attr('data-video-key');
				// TODO: refactor wikia slideshow
				} else if($this.hasClass('wikia-slideshow-popout')) {
					var $slideshowImg = $this.parents('.wikia-slideshow-toolbar').siblings('.wikia-slideshow-images-wrapper').find('li:visible').find('img').first();
					fileKey = $slideshowImg.attr('data-image-name') || $slideshowImg.attr('data-video-name');
				}

				if(!fileKey) {
					// might be old/cached DOM.  TODO: delete this when cache is flushed
					fileKey = $this.attr('data-image-name') || $this.attr('data-video-name');
					fileKey = fileKey ? fileKey.replace(/ /g, '_') : fileKey;
					LightboxLoader.handleOldDom(5);
				}

				if(!fileKey) {
					LightboxLoader.handleOldDom(5);
					return;
				}

				// Display video inline, don't open lightbox
				isVideo = $this.children('.Wikia-video-play-button').length;
				if(isVideo && $thumb.width() >= that.videoThumbWidthThreshold && !$this.hasClass('force-lightbox')) {
					var clickSource = window.wgWikiaHubType ? LightboxTracker.clickSource.HUBS : LightboxTracker.clickSource.EMBED;
					LightboxLoader.displayInlineVideo($this, $thumb, fileKey, clickSource);
					return;
				}

				that.loadLightbox(fileKey, trackingInfo);

			});

		// TODO: refactor wikia slideshow (BugId:43483)
		article.on('click.lightbox', '.wikia-slideshow-images .thumbimage, .wikia-slideshow-images .wikia-slideshow-image', function(e) {
			e.preventDefault();
			$(this).closest('.wikia-slideshow-wrapper').find('.wikia-slideshow-popout').click();
		});

	},

	/**
	 * @param {String} mediaTitle The name of the file to be loaded in the Lightbox
	 * @param {Object} trackingInfo Any info we've already gathered for tracking purposes.  Will be fed to Lightbox.getClickSource for processing
	 */
	loadLightbox: function(mediaTitle, trackingInfo) {
		// restore inline videos to default state, because flash players overlaps with modal
		LightboxLoader.removeInlineVideos();
		LightboxLoader.lightboxLoading = true;

		// Hide box ad so there's no z-index issues
		LightboxLoader.pageAds.css('visibility','hidden');

		// Display modal with default dimensions
		var openModal = $('<div>').makeModal(LightboxLoader.defaults);
		openModal.find('.modalContent').startThrobbing();

		var lightboxParams = {
			key: mediaTitle,
			modal: openModal
		};

		$.extend(lightboxParams, trackingInfo);

		var deferredList = [];
		if(!LightboxLoader.assetsLoaded) {
			deferredList.push($.loadMustache());

			var resources = [
				$.getSassCommonURL('/extensions/wikia/Lightbox/css/Lightbox.scss'),
				window.wgExtensionsPath + '/wikia/Lightbox/js/Lightbox.js'
			];

			deferredList.push($.getResources(resources));

			var deferredTemplate = $.Deferred();
			$.nirvana.sendRequest({
				controller:	'Lightbox',
				method:		'lightboxModalContent',
				type:		'GET',
				format: 'html',
				data: {
					lightboxVersion: window.wgStyleVersion,
					userLang: window.wgUserLanguage // just in case user changes language prefs
 				},
				callback: function(html) {
					LightboxLoader.templateHtml = html;
					deferredTemplate.resolve();
				}
			});

			deferredList.push( deferredTemplate );
		}

		deferredList.push(LightboxLoader.getMediaDetailDeferred({fileTitle: mediaTitle}));	// NOTE: be careful with this, look below where it says LASTINDEX

		$.when.apply(this, deferredList).done(function() {
			LightboxLoader.assetsLoaded = true;
			Lightbox.initialFileDetail = arguments[arguments.length - 1];	// LASTINDEX: index is last-index due to how deferred resolve works in mulitiple deferred objects
			Lightbox.makeLightbox(lightboxParams);
		});

	},
	displayInlineVideo: function(target, targetChildImg, mediaTitle, clickSource) {
		var self = this;

		if($.inArray(mediaTitle, LightboxLoader.inlineVideoLoading) > -1) {
			return;
		}

		LightboxLoader.inlineVideoLoading.push(mediaTitle);

		LightboxLoader.getMediaDetail({
			fileTitle: mediaTitle,
			height: targetChildImg.height(),
			width: targetChildImg.width()
		}, function(json) {
			var	embedCode = json['videoEmbedCode'],
				inlineDiv = $('<div class="inline-video"></div>').insertAfter(target.hide());

			require(['wikia.videoBootstrap'], function (VideoBootstrap) {
				self.videoInstance = new VideoBootstrap(inlineDiv[0], embedCode, clickSource);
			});

			// save references for inline video removal later
			LightboxLoader.inlineVideoLinks = target.add(LightboxLoader.inlineVideoLinks);
			LightboxTracker.inlineVideoTrackingTimeout = setTimeout(function() {
				LightboxTracker.track(Wikia.Tracker.ACTIONS.VIEW, 'video-inline', null, {title:json.title, provider: json.providerName, clickSource: clickSource});
			}, 1000);

			LightboxLoader.inlineVideoLoading.splice($.inArray(mediaTitle, LightboxLoader.inlineVideoLoading), 1);
		},
		true); // Don't cache the media details
	},

	removeInlineVideos: function() {
		clearTimeout(LightboxTracker.inlineVideoTrackingTimeout);
		LightboxLoader.inlineVideoLinks.show().next().remove();
	},

	getMediaDetail: function(mediaParams, callback, nocache) {
		var title = mediaParams['fileTitle'];

		if(!nocache && LightboxLoader.cache.details[title]) {
			callback(LightboxLoader.cache.details[title]);
		} else {
			$.nirvana.sendRequest({
				controller: 'Lightbox',
				method: 'getMediaDetail',
				type: 'get',
				format: 'json',
				data: mediaParams,
				callback: function(json) {
					// Don't cache videos played inline because width will be off for lightbox version bugid-42269
					if(!nocache) {
						LightboxLoader.cache.details[title] = json;
					}
					callback(json);
				}
			});
		}
	},

	getMediaDetailDeferred: function(mediaParams) {
		var deferred = $.Deferred();
		LightboxLoader.getMediaDetail(mediaParams, function(json) {
			deferred.resolve(json);
		});
		return deferred;
	},

	loadFromURL: function() {
		var fileTitle = window.Wikia.Querystring().getVal('file'),
			openModal = $('#LightboxModal');

		// Check if there's a file param in URL
		if(fileTitle) {
			// If Lightbox is already open, update it
			if(openModal.length) {
				LightboxLoader.getMediaDetail({fileTitle: fileTitle}, function(data) {
					Lightbox.current.key = data.title.replace(/ /g, '_');
					Lightbox.current.type = data.mediaType;

					Lightbox.setCarouselIndex();
					Lightbox.openModal.carousel.find('li').eq(Lightbox.current.index).click();
				});

			// Open new Lightbox
			} else {
				// set a fake parent for carouselType
				var trackingInfo = {
					parent: $('#WikiaArticle'),
					clickSource: LightboxTracker.clickSource.SHARE
				}
				LightboxLoader.loadLightbox(fileTitle, trackingInfo);
			}
		// No file param, if there's an open modal, close it
		} else {
			if(openModal.length) {
				openModal.closeModal();
			}
		}
	},
	isOldDom: null,
	/*
	 * @param {integer} type Value map: { itemClick:1, articleMedia:2, relatedVideos:3, latestPhotos:4, fromClick:5 }
	 */
	handleOldDom: function(type) {
		if(LightboxLoader.isOldDom === null) {
			$().log("Send old DOM tracking", "Lightbox");
			LightboxTracker.track(Wikia.Tracker.ACTIONS.VIEW, 'old-dom', type, null, 'internal');
		}
		LightboxLoader.isOldDom = true;
	}

};

LightboxTracker = {
	inlineVideoTrackingTimeout: 0,
	// @param data - any extra params we want to pass to internal tracking
	// Don't add willy nilly though... check with Jonathan.
	track: function(action, label, value, data, method) {
		Wikia.Tracker.track({
			action: action,
			category: 'lightbox',
			label: label || '',
			trackingMethod: method || 'internal',
			value: value || 0
		}, data);
	},

	// Constants for tracking the source of a click
	clickSource: {
		RV: 'relatedVideos',
		LP: 'latestPhotos',
		EMBED: 'embed',
		SEARCH: 'search',
		SV: 'specialVideos',
		LB: 'lightbox',
		SHARE: 'share',
		HUBS: 'hubs',
		OTHER: 'other',
		TOUCHSTORM: 'touchStorm'
	}
};
	window.LightboxLoader = LightboxLoader;
	window.LightboxTracker = LightboxTracker;


$(function() {
	if (window.wgEnableLightboxExt) {
		LightboxLoader.init();
		LightboxLoader.loadFromURL();
	}

	// Load leap motion JS
	$.getScript( 'http://js.leapmotion.com/0.2.0/leap.min.js', function() {

		var controller = new Leap.Controller({
				enableGestures: true
			} ),
			$window = $( window ),
			$wrapper = $( 'body' ),
			$finger = $( '<div>' )
				.attr( 'id', 'finger' )
				.appendTo( $wrapper ),
			width = $window.width(),
			height = $window.height(),
			swipeDirection,
			canUpdateLightbox = true;


		function leapToScene( frame, leapPos ){

			var iBox = frame.interactionBox,
				top = iBox.center[1] + iBox.size[1]/ 2,
				left = iBox.center[0] - iBox.size[0]/ 2,
				x = leapPos[0] - left,
				y = leapPos[1] - top;

			x /= iBox.size[0];
			y /= iBox.size[1];

			x *= width;
			y *= height;

			return [ x, -y ];
		}

		function afterUpdate() {
			canUpdateLightbox = false;
			setTimeout( function() {
				canUpdateLightbox = true;
			}, 500 );
		}

		controller.on( 'frame' , function( frame ){

			var showFinger = false,
			// get the first hand we see
				hand = frame.hands[0];

			if( hand ) {
				// grab the first finger we see
				var finger = hand.fingers[0];

				if( finger ) {
					showFinger = true;
					// and get its position on the screen
					var fingerPos = leapToScene( frame, finger.tipPosition );

					$finger.css( 'left', fingerPos[0] + 'px' )
						.css( 'top', fingerPos[1] + window.scrollY + 'px' );


					if( frame.gestures.length ) {

						if( frame.fingers.length === 1 && ( frame.gestures[0].type === 'screenTap' || frame.gestures[0].type === 'keyTap' ) ) {
							// TODO: this is no longer working - it's getting an element below it
							var $elem = $( document.elementFromPoint( fingerPos[0], fingerPos[1] ) );

							console.log( $elem );

							var $anchor = $elem.closest( 'a.image' ) || $elem.closest( 'a.lightbox' );

							if( $anchor.length ) {
								//$anchor.addClass( 'selected' );
								$anchor.click();
							} else if( $elem.is( '.blackout' ) ) {
								$elem.click();
							}
						}


						if( frame.fingers.length === 2 ) {
							//console.log( frame.gestures );
							var gesture = frame.gestures[0];

							//gesture && console.log( gesture.type + ' ' + gesture.state );

							if( window.LightboxIsOpen && canUpdateLightbox && gesture && ( /*gesture.type === 'swipe' ||*/ gesture.type === 'circle' ) && ( gesture.radius > 10 ) ) {
								if( gesture.state === 'stop' ) {
									if( swipeDirection === 'left' ) {
										//console.log( 'go left' );
										console.log( 'lightbox is loading' );
										window.lightboxIsLoading = true;
										$( '#LightboxPrevious' ).click();
									} else if( swipeDirection === 'right' ) {
										//console.log( 'go right' );
										console.log( 'lightbox is loading' );
										window.lightboxIsLoading = true;
										$( '#LightboxNext' ).click();
									}
									swipeDirection = null;
									afterUpdate();

								} else if( gesture.state === 'update' ) {
									// swipe gesture wasn't working out so well
									/*if( gesture.type === 'swipe' ) {
										//Classify swipe as either horizontal or vertical
										var isHorizontal = Math.abs( gesture.direction[0] ) > Math.abs( gesture.direction[1] );

										//Classify as right-left or up-down
										if( isHorizontal ){
											if( gesture.direction[0] > 0 ){
												swipeDirection = 'right';
											} else {
												swipeDirection = 'left';
											}
										} else { //vertical
											if( gesture.direction[1] > 0 ){
												swipeDirection = 'up';
											} else {
												swipeDirection = 'down';
											}
										}
									} else if( gesture.type === 'circle' ) {*/
										gesture.pointable = frame.pointable( gesture.pointableIds[0] );
										var direction = gesture.pointable.direction;

										// make sure it's not a tiny/unintended circle by checking the radius
										if( direction ) {
											var normal = gesture.normal;
											clockwise = Leap.vec3.dot( direction, normal ) > 0;
											if( clockwise ) {
												swipeDirection = 'right';
											} else {
												swipeDirection = 'left';
											}
										}
									//}
								}
							}
						}
					}
				}
			}

			if( showFinger ) {
				$finger.show();
			} else {
				$finger.hide();
			}

		} );

		// scrolling from https://github.com/hdragomir/wave-to-scroll/blob/gh-pages/leapscroll.js
		(function (Leap) {
			"use strict";

			var treshold = 0.7;
			var amplifier_x = 7;
			var amplifier_y = -7;
			var compare_to = null;
			Leap && Leap.loop(function (frame) {

				// Only allow scrolling if window is active
				/*if( !document.hasFocus() ) {
					return;
				}*/

				if (!frame.valid || frame.pointables.length < 4 || frame.hands.length !== 1) {
					return;
				}
				if (compare_to) {
					var t = compare_to.translation(frame),
						mx = t[0],
						my = t[1];
					Math.abs(mx) > treshold || (mx = 0);
					Math.abs(my) > treshold || (my = 0);
					(mx || my) && window.scrollBy(mx * amplifier_x, my * amplifier_y);
				}
				compare_to = frame;
			});
		} (typeof Leap !== "undefined" ? Leap : null));


		controller.connect();
		window.controller = controller;
	} );
} );



})(this, jQuery);
