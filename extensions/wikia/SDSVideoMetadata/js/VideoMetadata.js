require(['jquery', 'wikia.mustache', 'wikia.loader', 'JSMessages'], function($, mustache, loader, msg) {

	var cachedSelectors = {},
		cachedTemplates = {},
		charCountForSuggestions = 1, // minimal number of characters in input field to trigger suggestions dropdown
		videoPlayerPosition = null;

	function showSuggestionsDropdown($dropdown) {
		$dropdown.removeClass('hidden');
	}

	function hideSuggestionsDropdown($dropdown) {
		$dropdown.addClass('hidden');
		// will be needed when suggestion are ready
		//$dropdown.find('li').remove();
	}

	function createSuggestionsDropdown(eventTarget) {
		var $target = $(eventTarget).parent(),
			data = {
				createNewBtnMsg: msg('sdsvideometadata-vc-create-new-item')
			},
			html = mustache.render(cachedTemplates.suggestionsDropdown, data);

		$target.append(html);

		// will be needed when suggestion are ready
		//loadSuggestions($target.children('.suggestions-dropdown'));
	}

	function loadSuggestions($dropdown) { // load suggestions TEMPORARY!!!!
		var suggestions = [
				{
					objectName: 'Doom',
					objectParam: '2002.12.01',
					objectId: '1234567890',
					imgURL: '#'
				},
				{
					objectName: 'Doom',
					objectParam: '2002.12.01',
					objectId: '1234567890',
					imgURL: '#'
				},
				{
					objectName: 'Doom',
					objectParam: '2002.12.01',
					objectId: '1234567890',
					imgURL: '#'
				},
				{
					objectName: 'Doom',
					objectParam: '2002.12.01',
					objectId: '1234567890',
					imgURL: '#'
				},
				{
					objectName: 'Doom',
					objectParam: '2002.12.01',
					objectId: '1234567890',
					imgURL: '#'
				}
			],
			html = '',
			i;
		for (i = 0; i < suggestions.length; i += 1) {
			suggestions[i].propName = $dropdown.siblings('input').attr('id');
			suggestions[i].removeMsg = msg('sdsvideometadata-vc-remove-item');

			html += mustache.render(cachedTemplates.referenceItem, suggestions[i]);
		}

		$dropdown.children('ul').append(html);

	}

	function createRefItem($target) {
		var $list = $target.parents('.suggestions-dropdown').siblings('.reference-list'),
			$input = $list.siblings('input'),
			tamplateData = {
				objectName: $input.val(),
				pos: $list.children(':last').data('pos') + 1 || 0,
				imgURL: '',
				propName: $input.attr('name')
			},
			html = mustache.render(cachedTemplates.referenceItem, tamplateData);

		$list.append(html);

		if ($list.hasClass('hidden')) {
			$list.removeClass('hidden');
		}
	}

	function addRefItem($target) {
		var $list = $target.parents('.suggestions-dropdown').siblings('.reference-list'),
			tamplateData = {
				objectName: $target.children('.object-name').text(),
				objectParam: $target.children('.object-param').text(),
				objectId: $target.children('.object-id').val(),
				pos: $list.children(':last').data('pos') + 1 || 0,
				imgURL: ''
			},
			html = mustache(cachedTemplates.referenceItem, tamplateData);

		$list.append(html);

		if ($list.hasClass('hidden')) {
			$list.removeClass('hidden');
		}
	}

	function removeRefItem($target) {
		var $list = $target.parents('.reference-list');
		$target.parent().remove();
		if ($list.children().length === 0) {
			$list.addClass('hidden');
		}

	}

	function chooseClipType(event) { // show form part for type specific properties
		var $target = $(event.target),
			targetValue = $target.val(),
			targetClass = '.' + targetValue,

		// cache selectors
			propertiesWrapper = cachedSelectors.typeMDProperties,
			propertiesFormFields = propertiesWrapper.find('input, select, textarea');

		// show details for the chosen type
		if(targetValue !== '') {
			propertiesFormFields.attr('disabled', 'disabled');
			propertiesWrapper.find(targetClass).find('input, select, textarea').removeAttr('disabled');
			propertiesWrapper.children(':not(legend)').addClass('hidden').filter(targetClass).removeClass('hidden');
			propertiesWrapper.removeClass('hidden');
		} else {
			propertiesFormFields.attr('disabled', 'disabled');
			propertiesWrapper.addClass('hidden');
		}
	}

	function simpleValidation() { // Temporary method to prevent errors on PHP side when sending empty form
		if (cachedSelectors.typeSelect.val() !== '') {
			cachedSelectors.saveButton.removeAttr('disabled');
		} else {
			cachedSelectors.saveButton.attr('disabled', 'disabled');
		}
	}

	function setObjTypeForEdit() { 	// Temporary method for setting video object type in edit mode
		var type = cachedSelectors.typeSelect.data('type');
		if (type !== '') {
			var typeSelector = 'option[value="' + type + '"]';
			cachedSelectors.typeSelect.children(typeSelector).attr('selected', 'selected');
			cachedSelectors.typeSelect.trigger('change');
		}
	}

	function setVideoPlayerPosition() {
		if ($(window).scrollTop() >= videoPlayerPosition) {
			cachedSelectors.videoPlayer.addClass('fixed');
		} else {
			cachedSelectors.videoPlayer.removeClass('fixed');
		}
	}

	/**********************************************************
	  Initializing Function for Video Metadata form interface
	**********************************************************/

	function init() {

		// chache selectors
		cachedSelectors.form = $('#VMDForm');
		cachedSelectors.typeSelect = $('#vcType');
		cachedSelectors.typeMDProperties = $('#VMDSpecificMD');
		cachedSelectors.saveButton = $('#VMDFormSave');
		cachedSelectors.videoPlayer = $('#VMD-player-wrapper > div');

		// attach handlers
		cachedSelectors.form.on('input', '.suggestions', function(event){
			var $target = $(event.target),
				targetVal = $target.val(),
				$dropdown = $target.siblings('.suggestions-dropdown');

			if (targetVal.length >= charCountForSuggestions) {
				if ($dropdown.length > 0) {
					//loadSuggestions($dropdown);
					showSuggestionsDropdown($dropdown);

				} else {
					createSuggestionsDropdown(event.target);
					showSuggestionsDropdown($dropdown);

				}
			} else {
				hideSuggestionsDropdown($dropdown);
			}
		});
		cachedSelectors.form.on('blur', '.suggestions', function(event){
			var $target = $(event.target),
				$dropdown = $target.siblings('.suggestions-dropdown');
			if ($dropdown.length > 0) {
				$target.val('');
				hideSuggestionsDropdown($dropdown);
			}
		});
		cachedSelectors.form.on('mousedown', '.suggestions-dropdown .reference-item', function(event){
			var $target = $(event.currentTarget),
				$dropdown = $target.siblings('.suggestions-dropdown');
			addRefItem($target);
			$target.val('');
			hideSuggestionsDropdown($dropdown);
		});
		cachedSelectors.form.on('mousedown', '.suggestions-dropdown .create-new-btn', function(event){
			var $target = $(event.currentTarget),
				$dropdown = $target.siblings('.suggestions-dropdown');
			createRefItem($target);
			$target.val('');
			hideSuggestionsDropdown($dropdown);
		});
		cachedSelectors.form.on('click', '.reference-list .remove-item', function(event){
			event.preventDefault();
			removeRefItem($(event.target));
		});
		cachedSelectors.typeSelect.on('change', function(event) {
			chooseClipType(event);
			simpleValidation();
		});

		// block accidental submit on enter
		cachedSelectors.form.on('keydown', function(event){
			if (event.which === 13) {
				event.preventDefault();
			}
		});

		// lock video position when scrolling
		videoPlayerPosition = cachedSelectors.videoPlayer.offset().top;
		var throttled = $.throttle( 100, setVideoPlayerPosition);
		$(window).on('scroll', throttled);

		// set object type in edit mode
		setObjTypeForEdit();
	}

	/**********************************************
	  Load templates and and initialize interface
	**********************************************/

	$(function() {
		loader({
			type: loader.MULTI,
			resources: {
				mustache: 'extensions/wikia/SDSVideoMetadata/templates/suggestions_dropdown.mustache,extensions/wikia/SDSVideoMetadata/templates/SDSVideoMetadataController_referenceItem.mustache',
				messages: 'VMD-messages'
			}
		}).done(function(packagesData) {
			cachedTemplates.suggestionsDropdown = packagesData.mustache[0];
			cachedTemplates.referenceItem = packagesData.mustache[1];
			init();
		});
	});
});