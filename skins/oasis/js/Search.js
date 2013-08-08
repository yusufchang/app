var WikiaSearchApp = {
	searchForm: false,
	searchField: false,

	ads: false,

	track: Wikia.Tracker.buildTrackingFunction({
		trackingMethod: 'internal'
	}),

	init : function() {
		this.searchForm = $('#WikiaSearch');
		this.searchFormBottom = $('#search');
		this.searchField = this.searchForm.children('input[placeholder]');

		// RT #141437 - hide HOME_TOP_RIGHT_BOXAD when showing search suggestions
		this.ads = $("[id$='TOP_RIGHT_BOXAD']");

		if(!this.searchForm.hasClass('noautocomplete')) {
			this.searchField.bind({
				'suggestShow': $.proxy(this.hideAds, this),
				'suggestHide': $.proxy(this.showAds, this)
			});

			// load autosuggest code on first focus
			this.searchField.one('focus', $.proxy(this.initSuggest, this));
		}
	},

	hideAds: function() {
		this.ads.each(function() {
			$(this).children().css('margin-left', '-9999px');
		});
	},

	showAds: function() {
		this.ads.each(function() {
			$(this).children().css('margin-left', 'auto');
		});
	},

    loadDependencies: function() {
        var t = this;
        $.get( wgServer + wgScript + '?action=ajax&rs=getLinkSuggest&format=json&fetch=all', {}, function(data){
            t.predownloaded = data;
        }, 'json' );
        return $.loadJQueryAutocomplete();
    },

	// download necessary dependencies (AutoComplete plugin) and initialize search suggest feature for #search_field
	initSuggest: function() {

		$.when(
		    this.loadDependencies()
		).then($.proxy(function() {

            var onSelectFn = function(value, data, event) {
                var valueEncoded = encodeURIComponent(value.replace(/ /g, '_')),
                // slashes can't be urlencoded because they break routing
                    location = wgArticlePath.
                        replace(/\$1/, valueEncoded).
                        replace(encodeURIComponent('/'), '/');

                this.track({
                    eventName: 'search_start_suggest',
                    sterm: valueEncoded,
                    rver: 0
                });

                // Respect modifier keys to allow opening in a new window (BugId:29401)
                if (event.button === 1 || event.metaKey || event.ctrlKey) {
                    window.open(location);

                    // Prevents hiding the container
                    return false;
                } else {
                    window.location.href = location;
                }
            };

            if ( typeof(this.predownloaded) == "object" ) {
                this.searchField.autocomplete({
                    lookup: this.predownloaded.titles,
                    lookupMaxLengthToDisplay: 10,
                    onSelect: $.proxy(onSelectFn, this),
                    appendTo: '#WikiaSearch',
                    deferRequestBy: 150,
                    minLength: 1,
                    maxHeight: 1000,
                    selectedClass: 'selected',
                    width: '270px',
                    skipBadQueries: true // BugId:4625 - always send the request even if previous one returned no suggestions
                });
            }
            else {
                this.searchField.autocomplete({
                    serviceUrl: wgServer + wgScript + '?action=ajax&rs=getLinkSuggest&format=json',
                    onSelect: $.proxy(onSelectFn, this),
                    appendTo: '#WikiaSearch',
                    deferRequestBy: 150,
                    minLength: 1,
                    maxHeight: 1000,
                    selectedClass: 'selected',
                    width: '270px',
                    skipBadQueries: true // BugId:4625 - always send the request even if previous one returned no suggestions
                });
            }

		}, this));
	}
};

$(function() {
	WikiaSearchApp.init();
});
