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
			oCharacterData: [],
			characterData: []
		},
	//selectors
		$body = $('body'),
		$characterModule = $('.mom-character-module'),
		$characterList = $('.mom-character-module .items-list'),
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
						if (data.oTitle === '') {
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
			if (data.oTitle === '') {
				States.setState($titleWrap, 'zero-state');
			} else {
				States.setState($titleWrap, 'filled-state');
			}
		},
		editTitle = function () {
			States.setState($titleWrap, 'edit-state');
			$titleEditFld.text(data.title);
			placeCaretAtEnd($titleEditFld.get(0));
		},
		addCharacter = function () {
			require(['wikia.ui.factory'], function (uiFactory) {
				uiFactory.init(['modal']).then(function (uiModal) {
					var addCharacterModalConfig = getAddCharacterModalConfig();

					uiModal.createComponent(addCharacterModalConfig, function (addCharacterModal) {

						var $modal = addCharacterModal.$element,
							$modalUploadBtn = $modal.find('.upload-btn'),
							$modalUploadFld = $modal.find('input[type=file]'),
							$modalForm = $modal.find('.modal-form'),
							$modalUpload = $modal.find('.modal-upload'),
							$modalUploadOverlay = $modal.find('.overlay'),
							$modalUploadMask = $modal.find('.upload-mask'),
							$modalImage = $modal.find('.character-image'),
							$modalName = $modal.find('.character-name'),
							$modalLink = $modal.find('.character-link'),
							linkEdited = false;

						//add modal events
						$modalUploadBtn.on('click', function () {
							$modalUploadFld.click();
						});

						$modalName.on('keyup', function () {
							if (!linkEdited) {
								$modalLink.val($modalName.val());
							}
						});
						$modalLink.on('keyup', function () {
							linkEdited = true;
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
						$modalUpload.on('dragenter', function () {
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

						addCharacterModal.bind('save', function (event) {
							addCharacterModal.$element.startThrobbing();
							var formData = $modalForm.serialize();
							//add filename
							formData += '&cropposition=' + $modalImage.data('cropposition');
							formData += '&filename=' + $modalImage.data('filename');

							$.nirvana.sendRequest({
								controller: 'NjordCharacterController',
								method: 'addModuleItem',
								type: 'POST',
								data: formData,
								callback: function (response) {
									require(['wikia.mustache', 'wikia.loader'], function (mustache, loader) {
										loader({
											type: loader.MULTI,
											resources: {
												mustache: 'extensions/wikia/NjordPrototype/templates/NjordCharacter_item.mustache'
											}
										}).done(function (data) {
											var template = data.mustache[0],
												characterData = response.characterModel.contentSlots,
												character = characterData[characterData.length - 1];

											character.itemid = characterData.length;

											$characterList.append(mustache.render(template, character));
										});
									});

									addCharacterModal.$element.stopThrobbing();
									addCharacterModal.trigger('close');
								},
								onErrorCallback: function () {
									addCharacterModal.$element.stopThrobbing();
									showErrorModal($.msg('error'), $.msg('unknown-error'));
								}
							});
						});

						addCharacterModal.show();
					});
				});
			});
		},
		enableDragging = function ($target, $container) {
			//reset target position
			$target.css('top', '');
			$target.css('left', '');
			$target.removeClass('wide high');
			var ratio = $target.width() / $target.height(),
				contOffsetTop = $container.offset().top,
				contOffsetLeft = $container.offset().left,
				containment = [
					contOffsetLeft - $target.width() + $container.width(),
					contOffsetTop - $target.height() + $container.height(),
					contOffsetLeft,
					contOffsetTop
				];
			$target.addClass('drag-cursor');
			if (ratio > 1) {
				$target.addClass('wide');
			} else {
				$target.addClass('high');
			}
			$target.draggable({
				disabled: false,
				axis: ratio > 1 ? 'x' : 'y',
				containment: containment,
				drag: function () {
					var pos;
					if (ratio > 1) {
						pos = Math.abs($target.position().left) / $target.width();
					} else {
						pos = Math.abs($target.position().top) / $target.height();
					}
					$target.data('cropposition', pos);
				}
			});
		},
		onImageUploaded = function (data, $target, $image) {
			if (data.isOk) {
				$image.bind('load', function () {
					$image.unbind('load');
					States.setState($target, 'upload-state');
					//dragging for reposition
					enableDragging($image, $target);
					$target.stopThrobbing();
				});
				$image.data('filename', data.filename);
				$image.attr('src', data.url);
			} else {
				showErrorModal($.msg('error'), data.errMessage);
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
					showErrorModal($.msg('error'), $.msg('unknown-error'));
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
			if (typeof window.getSelection !== 'undefined' &&
				typeof document.createRange !== 'undefined') {
				var range = document.createRange();
				range.selectNodeContents(el);
				range.collapse(false);
				var sel = window.getSelection();
				sel.removeAllRanges();
				sel.addRange(range);
			} else if (typeof document.body.createTextRange !== 'undefined') {
				var textRange = document.body.createTextRange();
				textRange.moveToElementText(el);
				textRange.collapse(false);
				textRange.select();
			}
		},
		onDragDisabled = function () {
			return false;
		},
		removeCharacter = function (ev) {
			var $characterItem = $(this).closest('.item'),
				self = this;

			require(['wikia.ui.factory'], function (uiFactory) {
				uiFactory.init(['modal']).then(function (uiModal) {
					var removeCharacterModalConfig = getRemoveCharacterModalConfig();

					uiModal.createComponent(removeCharacterModalConfig, function (removeCharacterModal) {
						removeCharacterModal.bind('remove', function (event) {
							removeCharacterModal.$element.startThrobbing();
							var characterData = [];
							$('.item').each(function (i, item) {
								var $item = $(item);

								if ($item.data('itemid') !== $characterItem.data('itemid')) {
									characterData.push(
										{
											'itemid': $item.data('itemid'),
											'link': $item.data('link'),
											'image': $item.data('image'),
											'cropposition': $item.data('cropposition'),
											'title': $item.data('title'),
											'description': $item.data('description')
										});
								}
							});
							data.characterData = characterData;

							saveCharacterData().done(function () {
									$characterItem.remove();
									removeCharacterModal.$element.stopThrobbing();
									removeCharacterModal.trigger('close');
								}
							).fail(function () {
									data.characterData = data.oCharacterData;
									removeCharacterModal.$element.stopThrobbing();
									removeCharacterModal.trigger('close');
								});
						});

						removeCharacterModal.show();
					});
				});
			});
			ev.preventDefault();
		},
		saveCharacterData = function () {
			return (
				$.nirvana.sendRequest({
					controller: 'NjordCharacterController',
					method: 'saveModuleItems',
					type: 'POST',
					data: {
						'moduleitems': data.characterData
					}
				}));
		},
		initData = function () {
			initTitle();
			initCharacterData();
		},
		initTitle = function () {
			data.oTitle = data.title = $titleDataFld.text();
		},
		initCharacterData = function () {
			var characterData = [];
			$('.item').each(function (i, item) {
				var $item = $(item);
				characterData.push(
					{
						'itemid': $item.data('itemid'),
						'link': $item.data('link'),
						'image': $item.data('image'),
						'cropposition': $item.data('cropposition'),
						'title': $item.data('title'),
						'description': $item.data('description')
					});
			});
			data.oCharacterData = data.characterData = characterData;
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
			$characterModule.on('click', 'a.remove', removeCharacter);
		},
		getRemoveCharacterModalConfig = function () {
			var removeCharacterModalConfig = {
				vars: {
					id: 'RemoveCharacterModule',
					classes: ['character-modal'],
					size: 'small',
					content: 'Are you sure you want to remove this character?',
					title: 'Remove Character',
					closeButton: true,
					buttons: [
						{
							vars: {
								value: $.msg('cancel'),
								classes: ['primary', 'new-btn', 'inverse-btn'],
								data: [
									{
										key: 'event',
										value: 'close'
									}
								]
							}
						},
						{
							vars: {
								value: $.msg('ok'),
								classes: ['primary', 'new-btn', 'default-btn'],
								data: [
									{
										key: 'event',
										value: 'remove'
									}
								]
							}
						}
					]
				}
			};
			return removeCharacterModalConfig;
		},
		getAddCharacterModalConfig = function () {
			var addCharacterModalConfig = {
				vars: {
					id: 'AddCharacterModule',
					classes: ['character-modal'],
					size: 'medium',
					content: $('.mom-character-modal').html(),
					title: 'Add a Character',
					closeButton: true,
					buttons: [
						{
							vars: {
								value: 'Discard',
								classes: ['primary', 'new-btn', 'inverse-btn'],
								data: [
									{
										key: 'event',
										value: 'close'
									}
								]
							}
						},
						{
							vars: {
								value: 'Publish',
								classes: ['primary', 'new-btn', 'default-btn'],
								data: [
									{
										key: 'event',
										value: 'save'
									}
								]
							}
						}
					]
				}
			};
			return addCharacterModalConfig;
		},
		showErrorModal = function (title, message) {
			require(['wikia.ui.factory'], function (uiFactory) {
				uiFactory.init(['modal']).then(function (uiModal) {
					var errorModalConfig = getErrorModalConfig(title, message);

					uiModal.createComponent(errorModalConfig, function (errorModal) {
						errorModal.show();
					});
				});
			});
		},
		getErrorModalConfig = function (title, message) {
			var errorModalConfig = {
				vars: {
					id: 'Error',
					size: 'small',
					classes: ['character-modal'],
					content: message,
					title: title,
					closeButton: true,
					buttons: [
						{
							vars: {
								value: 'OK',
								classes: ['primary', 'new-btn', 'default-btn'],
								data: [
									{
										key: 'event',
										value: 'close'
									}
								]
							}
						}
					]
				}
			};
			return errorModalConfig;
		};
//fire up if logged user
	if (window.wgUserName) {
		init();
	}
})
(window, jQuery);
