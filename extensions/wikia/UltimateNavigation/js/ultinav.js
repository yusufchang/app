(function(window,$,undefined){
	var $body = $('body');

	$.nirvana.getJson('UltimateNavigation','config',function(json){
		init(json);
	},function(){
		$ultinav.text('Error: Could not load configuration');
	});

	function initTabs( $el ) {
		var $tabs = $el.find('.tab[data-target]'),
			$targets = $el.find('.tab-target[data-target-id]');
		$tabs.removeClass('selected');
		$($tabs[0]).addClass('selected');
		$targets.hide();
		$($targets[0]).show();

		$tabs.click(function(ev){
			var $tab = $(ev.target).closest('.tab'),
				target_id = $tab.data('target');
			$tabs.removeClass('selected');
			$tab.addClass('selected');
			$.each($targets,function(i,v){
				var $e = $(v);
				$e[$e.data('target-id') == target_id?'show':'hide']();
			});
		})
	}

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
		console.log('calling qtip()',$el);
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
				target: $el, // Use the mouse position as the position origin
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
					api.set('content.text', html);
					initTabs($(api.elements.content));
				})
				.fail(function(xhr, status, error) {
					api.set('content.text', status + ': ' + error);
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
		var link = $link[0], path = decodeURI(link.pathname),
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


	var curElement = false, timer = false;
	$body.click(function(ev){
		if ( !ev.shiftKey || !ev.altKey ) {
			return;
		}
		var $link = $(ev.target).closest('a');
		if ( $link.length ) {
			ev.preventDefault();
			ev.stopPropagation();
			handleLinkHover($link);
		}
	});

/*
	$body.mouseover(function(ev){
		var current = false;
		console.log('mouseover[1]',ev.target);
		var $link = $(ev.target).closest('a');
		console.log('mouseover[2]',$link);
		clearTimeout(timer);
		timer = false;
		if ( $link.length ) {
			timer = setTimeout(function(){
				handleLinkHover($link);
			},500);
		}
	});
	$body.mouseout(function(ev){
		console.log('mouseleave',ev.target);
		clearTimeout(timer);
		timer = false;
	});
*/

})(window,window.jQuery);
