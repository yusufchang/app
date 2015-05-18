(function() {
	'use strict';

	//// GLOBAL NAV
	//var globalNavProto = Object.create(HTMLElement.prototype);
	//globalNavProto.name = 'Global Nav';
	//
	//globalNavProto.createdCallback = function() {
	//	// import the template and retrieve a document fragment from it
	//	var clone,
	//		template = document.querySelector('#globalNavTemplate').content,
	//		contentArea = template.querySelector('.global-nav-content');
	//
	//	contentArea.innerHTML = this.innerHTML;
	//	clone = document.importNode(template, true);
	//
	//	// add our doc fragment to the DOM
	//	this.innerHTML = '';
	//	this.appendChild(clone);
	//
	//	createLogo();
	//	createSearch();
	//	createMenu();
	//};
	//
	//// register the custom element
	//document.registerElement('global-nav', {
	//	prototype: globalNavProto,
	//	extends: 'nav'
	//});

	// LOGO
	function createLogo() {
		var navLogoProto = Object.create(HTMLDivElement.prototype);
		navLogoProto.name = 'Nav Search';

		navLogoProto.createdCallback = function () {
			var template = document.querySelector('#logoTemplate').content,
				clone = document.importNode(template, true),
				anchor = clone.querySelector('a'),
				img = clone.querySelector('img');

			anchor.href = this.getAttribute('data-href');
			img.src = this.getAttribute('data-src');

			// add our doc fragment to the DOM
			this.innerHTML = '';
			this.appendChild(clone);
		};

		// register the custom element
		document.registerElement('nav-logo', {
			prototype: navLogoProto,
			extends: 'div'
		});
	}

	// SEARCH INPUT
	function createSearch() {
		var navSearchProto = Object.create(HTMLDivElement.prototype);
		navSearchProto.name = 'Nav Search';

		navSearchProto.createdCallback = function () {
			var template = document.querySelector('#searchTemplate').content,
				clone = document.importNode(template, true),
				input = clone.querySelector('input'),
				placeholder = this.getAttribute('data-placeholder');

			if (placeholder) {
				input.setAttribute('placeholder', placeholder);
			}

			// add our doc fragment to the DOM
			this.innerHTML = '';
			this.appendChild(clone);
		};

		// register the custom element
		document.registerElement('nav-search', {
			prototype: navSearchProto,
			extends: 'div'
		});
	}

	// MENU
	//function createMenu() {
	//	var navMenuProto = Object.create(HTMLDivElement.prototype);
	//	navMenuProto.name = 'Nav Menu';
	//
	//	navMenuProto.createdCallback = function () {
	//		var clone,
	//			template = document.querySelector('#navMenuTemplate').content,
	//			contentArea = template.querySelector('.nav-menu-content');
	//
	//		contentArea.innerHTML = this.innerHTML;
	//		clone = document.importNode(template, true);
	//
	//		// add our doc fragment to the DOM
	//		this.innerHTML = '';
	//		this.appendChild(clone);
	//	};
	//
	//	// register the custom element
	//	document.registerElement('nav-menu', {
	//		prototype: navMenuProto,
	//		extends: 'div'
	//	});
	//}

	createSearch();
	//createMenu();
})();
