define('annotations.views.button', ['thumbnails.templates.mustache'], function (formTemplate) {
	'use strict';

	function Button(options) {
		this.$el = options.$el;
		this.formTemplate = formTemplate.annotationForm;
		this.$formHolder = $('#annotation-form-holder');
		this.submitAdded = false;
		this.title = window.location.href.split('File:')[1];
		this.bindEvents();
	}

	Button.prototype.bindEvents = function() {
		if (!window.ooyalaPlayerInstance) {
			$(window).on('play.ooyala', $.proxy(this.init, this));
		} else {
			this.init();
		}
	};

	Button.prototype.renderButtons = function () {
		var $button = $('<button class="annotation-control big">Annotate</button>')
				.attr('disabled', true),
			$startPause = $('<button class="annotation-start-pause big secondary" data-action="play">Start</button>');

		$('.video-page-caption .video-views').before($button);
		$startPause.insertBefore($button);
		this.$startPause = $startPause;
		this.$button = $button;
	};

	Button.prototype.init = function () {
		var self = this,
			time,
			messageBus;

		this.player = window.ooyalaPlayerInstance;
		this.renderButtons();
		this.renderForm();

		this.$button.on('click', function () {
			time = self.player.getPlayheadTime();
			if ( !time ) {
				alert('you must start the video before you can annotate');
				return;
			}
			self.player.pause();
			self.addForm(time);
		});

		// handle play/pause button interaction
		messageBus = this.player.mb;
		messageBus.subscribe(window.OO.EVENTS.PLAYING, 'annotations', function () {
			self.$startPause.html('Pause')
				.data('action', 'pause');
			self.$button.attr('disabled', false);
		});
		messageBus.subscribe(window.OO.EVENTS.PAUSED, 'annotations', function () {
			self.$startPause.html('Play')
				.data('action', 'play');
			self.$button.attr('disabled', true);
		});

		this.$startPause.on('click', function () {
			var $this = $(this),
				action = $this.data('action');

			if (action === 'play') {
				self.player.play();
			} else {
				self.player.pause();
			}
		});
	};

	Button.prototype.addForm = function (time, duration, message) {
		this.addSumbit();

		var html = Mustache.render(this.formTemplate, {
			startTime: time,
			duration: duration,
			message: message
		});
		this.$formHolder.append(html);
	};

	Button.prototype.addSumbit = function () {
		var $submit,
			self = this;
		if ( this.submitAdded ) {
			return;
		}

		$submit = $('<button id="submit-annotation-form" class="big">Submit</button>');
		this.$formHolder.after($submit);
		this.$submit = $submit;
		this.submitAdded = true;

		$submit.on('click', function () {
			self.submitForm();
		});
	};

	Button.prototype.submitForm = function () {
		var self = this,
			data = [];

		this.$formHolder.find('.form-item').each(function () {
			var $this = $(this),
				start = parseInt($this.find('.start-time').val()),
				duration = parseInt($this.find('.duration').val()),
				message = $this.find('.message').val(),
				end = start + duration;

			data.push({
				begin: Math.round(start),
				end: Math.round(end),
				msg: message
			});
		});

		$.nirvana.sendRequest({
			controller: 'VideoAnnotation',
			method: 'save',
			type: 'POST',
			format: 'json',
			data: {
				videoTitle: this.title,
				annotation: data
			}
		}).done(function () {
			window.GlobalNotification.show(data.msg, 'success');
		});
	};

	Button.prototype.renderForm = function () {
		var self = this;

		$.nirvana.sendRequest({
			controller: 'VideoAnnotation',
			method: 'index',
			type: 'POST',
			format: 'json',
			data: {
				videoTitle: this.title
			}
		}).done(function (data) {
			var i,
				annnotations;

			if (data.result !== 'ok') {
				window.GlobalNotification.show(data.msg, 'error');
				return;
			}

			annnotations = data.annotation;

			for (i=0; i < annnotations.length; i++) {
				self.addForm(
					annnotations[i].begin,
					annnotations[i].end - annnotations[i].begin,
					annnotations[i].msg
				);
			}
		});

	};

	return Button;
});