require( ['jquery'], function($) {
	var modal,
		modalConfig = {
			vars: {
				id: 'intMapsDeleteMapModal',
				size: 'small',
				content: $.msg('wikia-interactive-maps-delete-map-prompt'),
				title: $.msg('wikia-interactive-maps-delete-map-title'),
				buttons: [
					{
						vars: {
							value: 'Delete',
							data: [
								{
									key: 'event',
									value: 'delete'
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
		event.preventDefault();
		modal.deactivate();
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
			onErrorCallback: function() {
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
						modal.bind('delete', deleteMap);
						modal.bind('mapDeleted', showSuccess);
						modal.bind('error', showError);
						modal.show();
					});
				});
			});
		} else {
			modal.show();
		}
	}

	function showError() {
		modal.activate();
		modal.setContent($.msg('wikia-interactive-maps-delete-map-error'));
	}

	function showSuccess() {
		modal.activate();
		modal.setContent($.msg('wikia-interactive-maps-delete-map-success'));
		modal.$element.find('button').attr('disabled', 'true');
		setTimeout(function(){
			window.location.href = 'www.google.com';
			modal.trigger('close');
		}, 2000);
	}

	$('body').click('#intMapsDeleteMap', function() {
		triggerDeleteMapModal();
	});
});