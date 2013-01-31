var VMDFormUI = {
	init: function() {
		var that = this,
			$VMDForm = $('#VMDForm');

		// attach handlers
		$VMDForm.on('click', 'button.add', function(event) {
			event.preventDefault();
			that.addListItem(event);
		});
		$VMDForm.on('click', 'button.remove', function(event) {
			event.preventDefault();
			that.removeListItem(event);
		});
		// TODO: this if prevent some strange behavior when pressing enter on different input filed (triggers other buttons in form). Find the root of this problem, solve and remove this handlers!!!
		$VMDForm.on('keydown', 'input[type="text"]', function(event) {
			if (event.which == 13) {
				event.preventDefault();
			}
		});
		$VMDForm.on('keydown', ' li input[type="text"]', function(event) {
			that.listEnterKeyHelper(event);
		});

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
	}
};

$(function() {
	VMDFormUI.init();
});