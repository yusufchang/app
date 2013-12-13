var ChatInviteModal = function() {
	$.get( window.wgScript + '?action=ajax&rs=ChatAjax&method=GetUsersToInvite', {}, function( data ) {
		console.log(data);
		if (!data.usersToInvite || !data.usersToInvite.length) {
			// hack - no users = no modal
			return;
		}
		require( [ 'wikia.ui.factory' ], function( uiFactory ) {
			uiFactory.init( [ 'modal' ] ).then( function( uiModal ) {
				var inviteModalConfig = {
					type: 'default',
					vars: {
						id: 'ChatInviteModal',
						size: 'small',
						content: data.template,
						title: 'Select user to invite to chat',
						buttons: [
							{
								vars: {
									value: $.msg( 'chat-invite-modal-button-ok' ),
									classes: [ 'normal', 'primary' ],
									data: [
										{
											key: 'event',
											value: 'ok'
										}
									]
								}
							},
							{
								'vars': {
									'value': $.msg( 'chat-ban-modal-button-cancel' ),
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

				uiModal.createComponent( inviteModalConfig, function( inviteModal ) {

					inviteModal.bind( 'ok', function ( event ) {
						event.preventDefault();

						$.nirvana.sendRequest(
							{
								controller: 'ChatRailController',
								method: 'InviteUser',
								type: 'POST',
								format: 'json',
								data: {
									username: $('#ChatUserToInvite').val()
								},
								callback: function(response) {
									if (response.status == true) {
										window.GlobalNotification.show(response.message, 'confirm');
									}
									inviteModal.trigger('close');
								}
							}
						);

					});

					inviteModal.show();
				});
			});
		});
	});
};
