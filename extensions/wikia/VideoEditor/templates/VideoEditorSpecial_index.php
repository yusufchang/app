<div id="VideoEditor" class="VideoEditor">

	<script src="http://vjs.zencdn.net/c/video.js"></script>

	<video id="my_video_1" class="video-js vjs-default-skin" controls
	  preload="auto" width="640" height="264" poster="http://images.liz.wikia-dev.com/__cb20130131235424/firefly/images/thumb/d/d0/Download.jpeg/212px-Download.jpeg"
	  data-setup="{}">
	  <!--source src="http://ec2-204-236-222-5.compute-1.amazonaws.com:8080/" type='video/asf'-->
	  <source src="http://ak.c.ooyala.com/ZlcDJhOTpjYhExL2Wsod_k7El-QV8-7j/DOcJ-FxaFrRg4gtDEwOjdqOjBrO_9XyT" type='video/m4v'>
	</video>

	<button id="reset">Reset</button>
	<button id="submit">Submit</button>
	<button id="record">Start Recording</button>
</div>


<div>
<script src='http://player.ooyala.com/v3/944ec2469d584f8b8469c70b19474c1e'></script>
<div id='ooyalaplayer' style='width:660px;height:371px'></div>
<div>
<button id="ooyala_playbutton">Play</button>
<button id="ooyala_pausebutton">Pause</button>
<button id="ooyala_seekbutton">Seek</button>
</div>
<script>
        var myPlayer = OO.Player.create('ooyalaplayer', 'h2Nzl2NjrV0bQWJfCOF9OWCR33CF6MAV',{ autoplay:true, initialTime:30});

        document.getElementById('ooyala_playbutton').onclick = function() {
         myPlayer.play();
        }

        document.getElementById('ooyala_pausebutton').onclick = function() {
         myPlayer.pause();
        }

        document.getElementById('ooyala_seekbutton').onclick = function() {
         myPlayer.seek(40);
        }

</script>
</div>