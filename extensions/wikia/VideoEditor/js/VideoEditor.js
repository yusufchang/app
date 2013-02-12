(function($, window) {

var VideoEditor = {
	init: function() {
		this.myPlayer = _V_("my_video_1");
		this.recordBtn = $('#record');
		this.recording = false;
		this.startTime = null;
		this.endTime = null;
		this.recordingMessage = "Recording started at {} seconds";
		this.finishedMessage = " and ended at {} seconds";
		
		this.myPlayer.ready( $.proxy(this.onVideoReady, this) );
	},
	onVideoReady: function() {	
		var that = this;
		
		// Bind video play event
		this.myPlayer.addEvent('play', function() {
			that.recordBtn.show();
		});
		
		// Bind record button event
		this.recordBtn.on('click', $.proxy(this.onRecordClick, this) );
		
		function resetClip() {
		
		}
		
		function submitClip() {
		
		}		
	},
	onRecordClick: function() {		
		if(!this.recording) {
			// Save start timestamp and flag that we're recording
			this.startTime = Math.round(this.myPlayer.currentTime());
			this.recording = true;
			
			// Update recording text
			this.recordBtn.text('Stop Recording');
			
			// Since we've made an action, show player controls briefly as feedback to user
			this.controlsFadeInThenOut();
			
			// Update response message
			this.recordingMessage = this.recordingMessage.replace("{}", this.startTime);
			GlobalNotification.show(this.recordingMessage, 'confirm');

		} else {
			// Save end timestamp and flag that we're done recording
			this.endTime = Math.round(this.myPlayer.currentTime());
			this.recording = false;

			// Update recording text
			this.recordBtn.hide().text('Start Recording');

			// Since we've made an action, show player controls briefly as feedback to user
			this.myPlayer.pause();
			this.controlsFadeInThenOut();
			
			// Update response message
			this.finishedMessage = this.recordingMessage + this.finishedMessage.replace("{}", this.endTime);
			GlobalNotification.show(this.finishedMessage, 'confirm');
		}
	},
	controlsFadeInThenOut: function() {
		var controls = this.myPlayer.controlBar;
		
		controls.fadeIn();
		
		setTimeout(function() {
			controls.fadeOut();
		}, 2000)
	}

};

$(function() {
	VideoEditor.init();
});

})(jQuery, this);





