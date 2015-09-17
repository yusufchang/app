require([
	'wikia.window', 'wikia.document', 'wikia.tracker', 'jquery'
], function(win, doc, tracker, $) {

	var current = 0,
		cleanClassName = 'infobox-tabber-item',
		active = ' active',
		images = $('.' + cleanClassName),
		lastElem = images.length-1;

	if ( images[current] ) {
		images[current].className += active;
	}

	$('.prev, .next').click(handleArrowClick);

	function handleArrowClick() {
		images[current].className = cleanClassName;
		this.className === 'prev' ? handleNextArrow() : handlePrevArrow();
		images[current].className += active;
	}

	function handleNextArrow() {
		current === lastElem ? current = 0 : current++;
	}

	function handlePrevArrow() {
		current === 0 ? current = lastElem : current--;
	}
});