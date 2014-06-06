define('wikia.intMaps.deleteMap', ['jquery', 'wikia.intMap.createMap.utils'], function($, utils) {
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
							classes: ['primary'],
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
		},
		$deleteMapButton = $('#intMapsDeleteMap'),
		$mapId = $('iframe.wikia-interactive-map').data('mapId'),
		url = 'wikia.php?controller=WikiaInteractiveMaps&method=deleteMap';
		debugger;

	function deleteMap() {
		event.preventDefault();
		modal.deactivate();
		var $form = $('<form method="post" action="'+ url + '"></form>');
		$('<input type="hidden" name="mapId">').val($mapId).appendTo($form);
		debugger;
		$form.submit();
	}

	function triggerDeleteMapModal() {
		if (window.wgUserName === null) {
			window.UserLoginModal.show({
				origin: 'wikia-int-map-create-map',
				callback: function() {
					loadModal();
				}
			});
		} else {
			loadModal();
		}
	}

	function init() {
		require( [ 'wikia.ui.factory' ], function( uiFactory ) {
			uiFactory.init( [ 'modal' ] ).then( function( uiModal ) {
				uiModal.createComponent( modalConfig, function( _modal ) {
					modal = _modal;
					modal.bind('delete', deleteMap);
					modal.show();
				});
			});
		});
	}

	return {
		init: init
	}
});