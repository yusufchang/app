(function(window,$,undefined){
	var $body = $('body');

	$.nirvana.getJson('UltimateNavigation','config',function(json){
		init(json);
	},function(){
		$ultinav.text('Error: Could not load configuration');
	});

	var config;

	var Context = (function(){
		var properties = {};
		return {
			'set': function( key, value ) {
				properties[key] = value;
			},
			remove: function( key ) {
				delete properties[key]
			},
			getAll: function() {
				return $.extend({},properties);
			}
		}
	})();

	function init( json ) {
		config = json.config;

	}

	function initTooltip( $el, title, contentFn ) {
		$el.qtip({
			content: {
				title: title,
				text: function(event, api) {
					setTimeout(function(){
						contentFn(api);
					},0);
					/*
					$.ajax({ url: 'custom/content.html' })
						.done(function(html) {
							api.set('content.text', html)
						})
						.fail(function(xhr, status, error) {
							api.set('content.text', status + ': ' + error)
						});
					*/

					return 'Loading...';
				}
			},
			show: true,
			position: {
				target: 'mouse', // Use the mouse position as the position origin
				adjust: {
					// Don't adjust continuously the mouse, just use initial position
					mouse: false
				}
			},
			hide: {
				event: 'unfocus',
				leave: false,
				fixed: true
			}
		});
	}

	function initTooltipFrom( $el, title, method, name ) {
		initTooltip($el,title,function(api){
			var url = $.nirvana.getUrl({
				controller: 'UltimateNavigation',
				method: method,
				format: 'html',
				data: {
					name: name
				}
			});
			$.ajax({ url: url })
				.done(function(html) {
					api.set('content.text', html)
				})
				.fail(function(xhr, status, error) {
					api.set('content.text', status + ': ' + error)
				});
		})
	}

	function classifyUrl( url ) {
		var RE_CATEGORY = /^\/wiki\/Category:(.*)$/i,
			RE_USER = /^\/wiki\/User:(.*)$/i,
			RE_ARTICLE = /^\/wiki\/(.*)$/i,
			RE_SPECIAL_CONTRIBUTIONS = /^\/wiki\/Special:Contributions\/(.*)$/i,
			RE_SPECIAL = /^\/wiki\/Special:(.*)$/i,
			m;

		m = RE_CATEGORY.exec(url);
		if ( m ) {
			return [ 'category', m[1] ];
		}

		m = RE_USER.exec(url);
		if ( m ) {
			return [ 'user', m[1] ];
		}

		m = RE_SPECIAL_CONTRIBUTIONS.exec(url);
		if ( m ) {
			return [ 'user', m[1] ];
		}

		m = RE_SPECIAL.exec(url);
		if ( m ) {
			return [ 'special', m[1] ];
		}

		m = RE_ARTICLE.exec(url);
		if ( m ) {
			return [ 'article', m[1] ];
		}

		return false;
	}

	function handleLinkHover( $link ) {
		var link = $link[0], path = link.pathname,
			linkType = classifyUrl(path);

		if ( !linkType ) {
			console.log('link not classified',$link,path);
			return;
		}

		var type = linkType[0], target = linkType[1];

		console.log('link classfied',$link,type,target);
		switch (type) {
			case 'article':
				initTooltipFrom($link,'Article: '+target,'article',target);
				break;
			case 'user':
				initTooltipFrom($link,'User: '+target,'user',target);
				break;
			case 'category':
				initTooltipFrom($link,'Category: '+target,'category',target);
				break;
			case 'special':
				initTooltipFrom($link,'Special: '+target,'special',target);
				break;
		}
	}

	$body.mouseover(function(ev){
		console.log('mouseover[1]',ev.target);
		var $link = $(ev.target).closest('a');
		console.log('mouseover[2]',$link);
		if ( $link.length ) {
			handleLinkHover($link);
		}
	});

})(window,window.jQuery);
