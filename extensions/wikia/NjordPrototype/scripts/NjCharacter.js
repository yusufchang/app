(function (window, $) {
	'use strict';

	var
	//const

	//helpers
		States = {
			list: [
				'zero-state',
				'filled-state',
				'edit-state',
				'no-edit-state'
			],
			clearState: function ($element) {
				$element.removeClass(this.list.join(' '));
			},
			setState: function ($element, $state) {
				if (this.list.indexOf($state) >= 0) {
					this.clearState($element);
					$element.addClass($state);
				}
			}
		},
		data = {
			oTitle: null,
			title: null,
		},
	//selectors
		$titleEditFld = $('.mom-character-module .mc-title'),
		$titleDataFld = $('.mom-character-module .title-text'),
		$titleWrap = $('.mom-character-module .title-wrap'),
		$titleEditElement = $('.mom-character-module .edit-box'),
		$titleEditBtn = $('.mom-character-module .edit-box .title-edit-btn'),
		$titleSaveBtn = $('.mom-character-module .edit-box .save-btn'),
		$titleDiscardBtn = $('.mom-character-module .edit-box .discard-btn'),
		$characterAddBtn = $('.mom-character-module .add-btn'),
	//functions
		saveTitle = function () {
			$titleEditElement.startThrobbing();
			$.nirvana.sendRequest({
				controller: 'NjordCharacterController',
				method: 'saveModuleTitle',
				type: 'POST',
				data: {
					'moduletitle': data.title
				},
				callback: function (r) {
					if (r.success) {
						$titleDataFld.text(data.oTitle = data.title);
						if (data.oTitle === "") {
							States.setState($titleWrap, 'zero-state');
						} else {
							States.setState($titleWrap, 'filled-state');
						}
					} else {
						revertTitle();
					}
					$titleEditElement.stopThrobbing();
				},
				onErrorCallback: function () {
					revertTitle();
					$titleEditElement.stopThrobbing();
				}
			});
		},
		revertTitle = function () {
			data.title = data.oTitle;
			$titleEditFld.text(data.oTitle);
			$titleDataFld.text(data.oTitle);
			if (data.oTitle === "") {
				States.setState($titleWrap, 'zero-state');
			} else {
				States.setState($titleWrap, 'filled-state');
			}
		},
		editTitle = function () {
			States.setState($titleWrap, 'edit-state');
			$titleEditFld.text(data.title);
			placeCaretAtEnd( $titleEditFld.get(0) );
		},
		addCharacter = function () {
			var modal = $.showModal('Add an Character', $('.modal-wrap').html(), {
				height: '38vw',
				width: '55vw',
			});
			$('.modalContent .mom-character-modal .discard-btn').one('click', function () {
				modal.closeModal();
			});
		},
		onFocus = function () {
			var $this = $(this);
			$this.data('before', $this.html());
			return $this;
		},
		onInput = function () {
			var $this = $(this);
			if ($this.data('before') !== $this.html()) {
				$this.data('before', $this.html());
				$this.trigger('change');
			}
			return $this;
		},
		onPaste = function (e) {
			var $this = $(this);
			window.setTimeout(function () {
				$this.html($this.text());
				placeCaretAtEnd($this.get(0));
			}, 1);
			return $this;
		},
		onChange = function (event) {
			var target = $(event.target);
			data.title = $titleEditFld.text();
			if (typeof target !== 'undefined' && target.caret === 'function') {
				var caretSave = target.caret();
				$titleEditFld.text($titleEditFld.text());
				target.caret(caretSave);
			}
		},
		placeCaretAtEnd = function (el) {
			el.focus();
			if (typeof window.getSelection != "undefined"
				&& typeof document.createRange != "undefined") {
				var range = document.createRange();
				range.selectNodeContents(el);
				range.collapse(false);
				var sel = window.getSelection();
				sel.removeAllRanges();
				sel.addRange(range);
			} else if (typeof document.body.createTextRange != "undefined") {
				var textRange = document.body.createTextRange();
				textRange.moveToElementText(el);
				textRange.collapse(false);
				textRange.select();
			}
		},
		initData = function () {
			data.oTitle = data.title = $titleDataFld.text();
		},
		init = function () {
			initData();
			//add events
			$titleSaveBtn.on('click', saveTitle);
			$titleDiscardBtn.on('click', revertTitle);
			$titleEditBtn.on('click', editTitle);
			$titleEditFld.on('focus', onFocus)
				.on('blur keyup paste input', onInput)
				.on('paste', onPaste)
				.on('change', onChange);

			$characterAddBtn.on('click', addCharacter);
		};

	//fire up if logged user
	if (window.wgUserName) {
		init();
	}
})(window, jQuery);