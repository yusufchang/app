require( ['jquery'], function($) {
	var modal,
		modalConfig = {
			vars: {
				id: 'intMapsDeleteMapModal',
				size: 'small', // size of the modal
				content: 'Do you really want to delete the map?', // content
				title: 'Delete Map', // title
				buttons: [  // buttons in the footer
					{
						vars: {
							value: 'Delete',
							data: [
								{
									key: 'event',
									value: 'delete',
								}
							]
						}
					},
					{
						vars: {
							value: 'Cancel',
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

	function deleteMap(mapId) {
		$.nirvana.sendRequest({
			controller: 'WikiaInteractiveMaps',
			method: 'deleteMap',
			format: 'json',
			data: {mapId: mapId},
			callback: function(response) {
				var data = response.result;
				if (data) {
					modal.trigger('mapDeleted');
				} else {
					modal.trigger('error');
				}
			},
			onErrorCallback: function(response) {
				modal.trigger('error');
			}
		});
	}

	function triggerDeleteMapModal() {
		if (!modal) {
			require( [ 'wikia.ui.factory' ], function( uiFactory ) {
				uiFactory.init( [ 'modal' ] ).then( function( uiModal ) {
					uiModal.createComponent( modalConfig, function( _modal ) {
						modal = _modal;
						// bind the Save button to this anon. function
						modal.bind( 'delete', function( event ) {
							event.preventDefault();
							deleteMap();
						});
						modal.bind('mapDeleted', showSuccess);
						modal.bind('error', showError);
						modal.bind('close', cleanUpError);
						showDeleteMapModal(modal);
					});
				});
			});
		}
	}

	function showDeleteMapModal(modal) {
		modal.show();
		modal.deactivate();
		modal.activate();
	}

	function showError() {
		modal.$errorContainer
			.html($.msg('wikia-interactive-maps-delete-map-error'))
			.removeClass('hidden');
	}

	function cleanUpError() {
		modal.$errorContainer
			.html('')
			.addClass('hidden');
	}

	function showSuccess() {
		modal.setContent($.msg('wikia-interactive-maps-delete-map-success'));
		modal.$element.find('button').attr('disabled', 'true');
		setTimeout(function(){
			window.location.href = 'www.google.com';
			modal.trigger('close');
		}, 2000);
	}


	$('body').click('#intMapsDeleteMap', function(event) {
		triggerDeleteMapModal();
	});
});