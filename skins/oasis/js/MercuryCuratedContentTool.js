require(
	[
		'jquery',
		'wikia.window'
	],
	function ($, w) {
		$('#CuratedContentTool').click(function () {
			var iframe = '<iframe src="/main/edit?useskin=mercury" id="CuratedContentToolIframe" name="curated-content-tool" width="100%" height="500"></iframe>';

			require(['wikia.ui.factory'], function (uiFactory) {
				uiFactory.init(['modal']).then(function (uiModal) {
					var modalConfig = {
						vars: {
							id: 'CuratedContentToolModal',
							size: 'medium',
							title: 'Curated Content Tool',
							content: iframe
						}
					};

					uiModal.createComponent(modalConfig, function (mercuryModal) {
						mercuryModal.show();

						//require(['wikia.maps.pontoBridge'], function (pontoBridge) {
						//	pontoBridge.init(mercuryModal.$content.find('#wikiaInteractiveMapIframe')[0]);
						//});
					});
				});
			});
		});
	}
);
