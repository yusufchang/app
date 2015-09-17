var current = 0,
	images = $('.image-tabber'),
	lastElem = images.length-1,
	cleanClassName = 'pi-item pi-image image-tabber';

images[current].className += " active";

$('.next').click(function() {
	images[current].className = cleanClassName;
	current === lastElem ? current = 0 : current++;
	images[current].className += " active";
});

$('.prev').click(function() {
	images[current].className = cleanClassName;
	current === 0 ? current = lastElem : current--;
	images[current].className += " active";
});