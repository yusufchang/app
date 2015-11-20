(function( mw, $) {
	if ( mw.loader && typeof mw.loader.use == 'undefined' ) {
		mw.loader.use = function(/*arguments*/) {
			// TODO: Log this call as depracated
			return mw.loader.using.apply(mw.loader, arguments);
		}
	}
})(window.mediaWiki, jQuery);
