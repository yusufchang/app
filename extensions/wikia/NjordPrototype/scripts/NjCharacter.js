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
				'no-edit-state',
				'upload-state'
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
		$titleEditBtn = $('.mom-character-module .title-edit-btn'),
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
				width: '650px',
			}),
				//modal selectors
				$modal = $('.modalContent'),
				$modalUploadBtn = $('.modalContent .mom-character-modal .upload-btn'),
				$modalDiscardBtn = $('.modalContent .mom-character-modal .discard-btn'),
				$modalSaveBtn = $('.modalContent .mom-character-modal .save-btn'),
				$modalUploadFld = $('.modalContent .mom-character-modal input[type=file]'),
				$modalForm = $('.modalContent .mom-character-modal .modal-form'),
				$modalUpload = $('.modalContent .mom-character-modal .modal-upload'),
				$modalUploadArea = $('.modalContent .mom-character-modal .upload'),
				$modalUploadOverlay = $('.modalContent .mom-character-modal .overlay'),
				$modalUploadMask = $('.modalContent .mom-character-modal .upload-mask'),
				$modalImage = $('.modalContent .mom-character-modal .character-image')
				;
			//add modal events
			$modalUploadBtn.on('click', function () {
				$modalUploadFld.click();
			});
			$modalDiscardBtn.one('click', function () {
				modal.closeModal();
			});
			$modalSaveBtn.on('click', function () {
				$modal.startThrobbing();
				var formData = $modalForm.serialize();
				//add filename
				formData += '&filename=' + $modalImage.data('filename');
				console.info(formData);
				//TODO: save new element
				$.nirvana.sendRequest({
					controller: 'NjordCharacterController',
					method: 'save',
					type: 'POST',
					data: formData,
					callback: function (data) {
						$modal.stopThrobbing();
					},
					onErrorCallback: function () {
						$modal.stopThrobbing();
						modal.closeModal();
						$.showModal($.msg('error'), $.msg('unknown-error'));
					},
				});
			});
			$modalUploadFld.on('change', function () {
				if ($modalUploadFld[0].files.length) {
					var fd = new FormData();
					fd.append('file', $modalUploadFld[0].files[0]);
					uploadImage(fd, $modalUpload, $modalImage);
					//reset input
					$modalUploadFld.wrap('<form>').closest('form').get(0).reset();
					$modalUploadFld.unwrap();
				}
			});
			$modalUploadArea.on('dragenter', function () {
				$modalUploadOverlay.show();
				$modalUploadMask.show();
				return false;
			});
			$modalUploadMask.on('dragleave', function (e) {
				$modalUploadOverlay.hide();
				$modalUploadMask.hide();
				e.stopImmediatePropagation();
				return false;
			});
			$modalUploadMask.on('dragend', function () {
				return false;
			});
			$modalUploadMask.on('drop', function (e) {
				$modalUploadOverlay.hide();
				$modalUploadMask.hide();
				e.preventDefault();
				var fd = new FormData();
				if (e.dataTransfer.files.length) {
					//if file is uploaded
					fd.append('file', e.dataTransfer.files[0]);
					uploadImage(fd, $modalUpload, $modalImage);
				} else if (e.dataTransfer.getData('text/html')) {
					//if url
					var $img = $(e.dataTransfer.getData('text/html'));
					if (e.target.src !== $img.attr('src')) {
						fd.append('url', $img.attr('src'));
						uploadImage(fd, $modalUpload, $modalImage);
					}
				}
			});
		},
		onImageUploaded = function (data, $target, $image) {
			if (data.isOk) {
				$image.bind('load', function () {
					$image.unbind('load');
					States.setState($target, 'upload-state');
					//TODO: set image in data
					$target.stopThrobbing();
				});
				$image.data('filename', data.filename);
				$image.attr('src', data.url);
			} else {
				$.showModal($.msg('error'), data.errMessage);
				$target.stopThrobbing();
			}
		},
		uploadImage = function (formdata, $target, $image) {
			$target.startThrobbing();
			$.nirvana.sendRequest({
				controller: 'NjordCharacterController',
				method: 'upload',
				type: 'POST',
				data: formdata,
				callback: function (data) {
					onImageUploaded(data, $target, $image);
				},
				onErrorCallback: function () {
					$.showModal($.msg('error'), $.msg('unknown-error'));
					$target.stopThrobbing();
				},
				processData: false,
				contentType: false
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
		onDragDisabled = function () {
			return false;
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

			//turn off browser image handling
			$body.on('dragover', onDragDisabled).on('dragend', onDragDisabled).on('drop', onDragDisabled);
		};

	//fire up if logged user
	if (window.wgUserName) {
		init();
	}
})(window, jQuery);