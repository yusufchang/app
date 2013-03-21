var VideoMetadata = {
	cachedSelectors: {},
	cachedTemplates: {},
	charCountForSuggestions: 3,
	videoPlayerPosition: null,
	init: function() {
		var that = this;
		this.cachedSelectors.form = $('#VMDForm');
		this.cachedSelectors.typeSelect = $('#vcType');
		this.cachedSelectors.typeMDProperties = $('#VMDSpecificMD');
		this.cachedSelectors.saveButton = $('#VMDFormSave');
		this.cachedSelectors.videoPlayer = $('#VMD-player-wrapper > div');

		// load Mustache and templates
		$.when(
			$.loadMustache(),
			Wikia.getMultiTypePackage({
				mustache: 'extensions/wikia/SDSVideoMetadata/templates/suggestions_dropdown.mustache,extensions/wikia/SDSVideoMetadata/templates/SDSVideoMetadataController_referenceItem.mustache'
			})
		).done(function(libData, packagesData) {
			that.cachedTemplates.suggestionsDropdown = packagesData[0].mustache[0];
			that.cachedTemplates.referenceItem = packagesData[0].mustache[1];

			that.cachedSelectors.form.on('input', '.suggestions', function(event){
				var $target = $(event.target),
					targetVal = $target.val()
					$dropdown = $target.siblings('.suggestions-dropdown');

				if (targetVal.length >= that.charCountForSuggestions) {
					if ($dropdown.length > 0) {
						that.loadSuggestions($dropdown);
						that.showSuggestionsDropdown($dropdown);

					} else {
						that.renderSuggestions(event.target);
						that.showSuggestionsDropdown($dropdown);
					}
				}
			});
			that.cachedSelectors.form.on('blur', '.suggestions', function(event){
				var $dropdown = $(event.target).siblings('.suggestions-dropdown');
				if ($dropdown.length > 0) {
					that.hideSuggestionsDropdown($dropdown);
				}
			});
			that.cachedSelectors.form.on('click', '.suggestions-dropdown .reference-item', function(event){
				var $target = $(event.target);
				that.addRefItem($target);
				that.hideSuggestionsDropdown($target.parents('.suggestions-dropdown'));
			});

		});

		// attach handlers
		this.cachedSelectors.typeSelect.on('change', function(event) {
			that.chooseClipType(event);
			that.simpleValidation();
		});

		// lock video position when scrolling
		this.videoPlayerPosition = this.cachedSelectors.videoPlayer.offset().top;
		var throttled = $.throttle( 100, $.proxy(this.setVideoPlayerPosition, this));
		$(window).on('scroll', throttled);

		this.setObjTypeForEdit();
	},



	// show suggestions dropdown
	showSuggestionsDropdown: function($dropdown) {
		$dropdown.removeClass('hidden');
	},
	// hide suggestions dropdown
	hideSuggestionsDropdown: function($dropdown) {
		//$dropdown.addClass('hidden');
		$dropdown.find('li').remove();
		//$dropdown.find('.create-new-btn').addClass('hidden');
	},
	// render suggestions dropdown
	renderSuggestions: function(eventTarget) {
		var $target = $(eventTarget).parent(),
			data = {
			createNewBtnMsg: 'Create new'
		},
			html = $.mustache(this.cachedTemplates.suggestionsDropdown, data);

		$target.append(html);

		this.loadSuggestions($target.children('.suggestions-dropdown'));
	},
	// load suggestions TEMPORARY!!!!
	loadSuggestions: function($dropdown) {
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
			suggestions[i].pos = i;
			suggestions[i].propName = $dropdown.siblings('input').attr('id');
			suggestions[i].removeMsg = 'Remove';

			html += $.mustache(this.cachedTemplates.referenceItem, suggestions[i]);
		}

		$dropdown.children('ul').append(html);

	},
	// add reference item to list
	addRefItem: function($target) {
		var $list = $target.parents('.suggestions-dropdown').siblings('.reference-list');
		console.log($list);
		$list.append($target);
	},
	// show form part for type specific properties
	chooseClipType: function(event) {
		var $target = $(event.target),
			targetValue = $target.val(),
			targetClass = '.' + targetValue,

			// cache selectors
			propertiesWrapper = this.cachedSelectors.typeMDProperties,
			propertiesFormFields = propertiesWrapper.find('input, select, textarea');

		if(targetValue !== '') {
			propertiesFormFields.attr('disabled', 'disabled');
			propertiesWrapper.find(targetClass).find('input, select, textarea').removeAttr('disabled');
			propertiesWrapper.children(':not(legend)').addClass('hidden').filter(targetClass).removeClass('hidden');
			propertiesWrapper.removeClass('hidden');
		} else {
			propertiesFormFields.attr('disabled', 'disabled');
			propertiesWrapper.addClass('hidden');
		}
	},
	// Temporary method to prevent errors on PHP side when sending empty form
	simpleValidation: function() {
		if (this.cachedSelectors.typeSelect.val() !== '') {
			this.cachedSelectors.saveButton.removeAttr('disabled');
		} else {
			this.cachedSelectors.saveButton.attr('disabled', 'disabled');
		}
	},
	// Temporary method for setting video object type in edit mode
	setObjTypeForEdit: function() {
		var type = this.cachedSelectors.typeSelect.data('type');
		if (type === '') {
			return false;
		}
		var $type = 'option[value="' + type + '"]';
		this.cachedSelectors.typeSelect.children($type).attr('selected', 'selected');
		this.cachedSelectors.typeSelect.trigger('change');
	},
	// Method controlling video player position
	setVideoPlayerPosition: function() {
		if ($(window).scrollTop() >= this.videoPlayerPosition) {
			this.cachedSelectors.videoPlayer.addClass('fixed');
		} else {
			this.cachedSelectors.videoPlayer.removeClass('fixed');
		}
	}
};

$(function() {
	VideoMetadata.init();
});