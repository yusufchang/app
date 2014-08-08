define('annotations.views.button', ['thumbnails.templates.mustache'], function (formTemplate) {
	'use strict';

	function Button(options) {
		this.$el = options.$el;
		this.formTemplate = formTemplate.annotationForm;
		this.$formHolder = $('#annotation-form-holder');
		this.submitAdded = false;
		this.title = window.location.href.split('File:')[1];
		this.render();
	}

	Button.prototype.render = function () {
		var $button = $('<button class="annotation-control big">Annotate</button>');

		$('.video-page-caption .video-views').before($button);
		this.$button = $button;
		this.bindEvents();
	};

	Button.prototype.bindEvents = function() {
		if (!window.ooyalaPlayerInstance) {
			$(window).on('play.ooyala', $.proxy(this.init, this));
		} else {
			this.init();
		}
	};

	Button.prototype.init = function () {
		var self = this,
			time;
		this.player = window.ooyalaPlayerInstance;

		this.$button.on('click', function () {
			time = self.player.getPlayheadTime();
			if ( !time ) {
				alert('you must start the video before you can annotate');
				return;
			}
			self.player.pause();
			self.addForm(time);
		});
	};

	Button.prototype.addForm = function (time) {
		this.addSumbit();

		var html = Mustache.render(this.formTemplate, {
			startTime: time
		});
		this.$formHolder.append(html);
	};

	Button.prototype.addSumbit = function () {
		var $submit,
			self = this;
		if ( this.submitAdded ) {
			return;
		}

		$submit = $('<button id="submit-annotation-form">Submit</button>');
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
				message: message
			});
		});

		$.nirvana.sendRequest({
			controller: 'VideoAnnotation',
			method: 'index',
			type: 'POST',
			format: 'json',
			data: {
				videoTitle: self.title,
				annotation: data
			}
		}).done(function () {
			console.log(arguments);
		});
	};

	return Button;
});