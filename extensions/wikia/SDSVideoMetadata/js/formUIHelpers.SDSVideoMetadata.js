var VMDFormUI = {
	cachedSelectors: {},
	init: function() {
		var that = this;
		this.cachedSelectors.form = $('#VMDForm');
		this.cachedSelectors.typeSelect = $('#vcType');
		this.cachedSelectors.typeMDProperties = $('#VMDSpecificMD');
		this.cachedSelectors.saveButton = $('#VMDFormSave');
		this.cachedSelectors.nameField = $('#vcTitle');

		// attach handlers
		this.cachedSelectors.form.on('click', 'button.add', function(event) {
			event.preventDefault();
			that.addListItem(event);
		});
		this.cachedSelectors.form.on('click', 'button.remove', function(event) {
			event.preventDefault();
			that.removeListItem(event);
		});

		// TODO: this if prevent some strange behavior when pressing enter on different input filed (triggers other buttons in form). Find the root of this problem, solve and remove this handlers!!!
		this.cachedSelectors.form.on('keydown', 'input[type="text"]', function(event) {
			if (event.which == 13) {
				event.preventDefault();
			}
		});
		this.cachedSelectors.form.on('keydown', ' li input[type="text"]', function(event) {
			that.listEnterKeyHelper(event);
		});

		this.cachedSelectors.typeSelect.on('change', function(event) {
			that.chooseClipType(event);
			that.simpleValidation();
		});

		this.cachedSelectors.nameField.on('blur', $.proxy(this.simpleValidation, this));

	},

	// add new blank input field for reference list type properties
	addListItem: function(event) {
		var lastListElement = $(event.target).prev().children().last();

		lastListElement.clone().insertBefore(lastListElement).find('.remove').removeClass('hidden');
		lastListElement.find('input').val('').focus();
	},
	// remove selected reference in the list
	removeListItem: function(event) {
		var selectedRefObj = $(event.target).parent(),
			focusPoint = selectedRefObj.siblings().last().find('input');

		selectedRefObj.remove();
		focusPoint.focus();
	},
	// use 'enter' key to quickly move through lists or add new list items
	listEnterKeyHelper: function(event) {
		if (event.which == 13) {
			var $target = $(event.target),
				$nextField = $target.parent().next().find('input');

			if ($nextField.length > 0) {
				$nextField.focus();
			} else {
				$target.parents('ul').siblings('button.add').click();
			}
		}
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
			propertiesWrapper.children().addClass('hidden').filter(targetClass).removeClass('hidden');
			propertiesWrapper.removeClass('hidden');
		} else {
			propertiesFormFields.attr('disabled', 'disabled');
			propertiesWrapper.addClass('hidden');
		}
	},
	// Temporary method to prevent errors on PHP side when sending empty form
	simpleValidation: function() {
		if (this.cachedSelectors.typeSelect.val() !== '' && this.cachedSelectors.nameField.val() !== '') {
			this.cachedSelectors.saveButton.removeAttr('disabled');
		} else {
			this.cachedSelectors.saveButton.attr('disabled', 'disabled');
		}
	}

};

$(function() {
	VMDFormUI.init();
});