var WikiaSearchApp = {
	searchForm: false,
	searchField: false,
    initStarted: false,
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

            // load autosuggest on keydown if first focus was before this script loaded
			this.searchField.one('keydown', $.proxy(this.initSuggest, this));
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

    getAutocompleteOptions: function( mode ) {

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

        var autocompleteOptions = {
            onSelect: $.proxy(onSelectFn, this),
            appendTo: '#WikiaSearch',
            deferRequestBy: 150,
            minLength: 1,
            maxHeight: 1000,
            selectedClass: 'selected',
            width: '270px',
            skipBadQueries: true // BugId:4625 - always send the request even if previous one returned no suggestions
        };
        if ( mode == 'static' ) {
            autocompleteOptions.lookup = this.predownloaded.titles;
            autocompleteOptions.lookupMaxLengthToDisplay = 10;
        } else {
            autocompleteOptions.serviceUrl = wgServer + wgScript + '?action=ajax&rs=getLinkSuggest&format=json';
        }
        return autocompleteOptions;
    },

	// download necessary dependencies (AutoComplete plugin) and initialize search suggest feature for #search_field
	initSuggest: function() {

        if ( this.initStarted == true ) {
            return;
        }

        this.initStarted = true;
        $.when( $.get(wgServer + wgScript + '?action=ajax&rs=getLinkSuggest&format=json&fetch=all')).then( $.proxy( function(data){
            this.predownloaded = data;
            $.when( $.loadJQueryAutocomplete() ).then(
                $.proxy(function() {
                    if ( typeof(this.predownloaded) == "object" ) {
                        this.searchField.autocomplete( this.getAutocompleteOptions('static') );
                    }
                    else { // falback on old method
                        this.searchField.autocomplete( this.getAutocompleteOptions('ajax') );
                    }
                }, this)
            );
        }, this) );
	}
};

$(function() {
	WikiaSearchApp.init();
});
