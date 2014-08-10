<?php
/**
 * Array (
 * [title] => Pure_Chess_Learning_the_Game
 * [fileTitle] => Pure Chess Learning the Game
 * [description] => New to chess? Pure Chess has you covered with a litany of tutorials. Category: Gameplay Keywords: Pure Chess Tags: pure-chess-learning-the-game, gameplay, games, ign, playstation-3, playstation-vita, pure-chess
 * [fileUrl] => http://video.armon.wikia-dev.com/wiki/File:Pure_Chess_Learning_the_Game
 * [thumbUrl] => http://static.armon.wikia-dev.com/__cb20120915000447/video151/images/thumb/a/a3/Pure_Chess_Learning_the_Game/160px-Pure_Chess_Learning_the_Game.jpg
 * [userName] =>
 * [userUrl] =>
 * [truncatedList] => Array ( )
 * [isTruncated] => 0
 * [timestamp] =>
 * [duration] => 66
 * [viewsTotal] => 0
 * [provider] => ign
 * [embedUrl] => http://www.ign.com/videos/2012/05/31/pure-chess-learning-the-game
 * [videoId] => a0906999ebda35f52ce278f4dbe8df15
 * [thumbnail] => <img>
 * [regionalRestrictions] =>
 * )

$file = $this->getVal( 'file' );
$width = $this->getVal( 'outerWidth' );
$url = $this->getVal( 'url' );
$align = $this->getVal( 'align' );
$thumbnail = $this->getVal( 'html' );
$caption = $this->getVal( 'caption' );


 */

$width = 250;
$padding = 5;

$thumbs = [];
foreach ( $items as $item ) {
	$title = $item['title'];
	$file = wfFindFile( $title );
	if ( !$file ) {
		continue;
	}

	$thumbs[] = $file->transform( [ 'width' => $width - $padding ] )->toHtml();
	//var_dump($thumbObject);die;
	//	$options = [
	//		'title' => $title,
	//		'alt' => $title,
	////		'img-class' => 'thumbimage',
	//		'align' => 'top',
	//		'outerWidth' => $width,
	//		'file' => $file,
	//		'url' => $file->getUrl(),
	//		'html' => $file->transform( ['width' => $width] )->toHtml(),
	//
	//
	////	    'outerWidth' => $width,
	////	    'url' => $item['fileUrl'],
	////	    'html' => $item['thumbnail'],
	////	    'caption' => $item['fileTitle'],
	//	    'filePageLink' => $file->getUrl(),
	//	];
	//
	//	$params = [
	//		'thumb' => $thumbObject,
	//		'options' => $options,
	//	];
}

if ( !empty( $thumbs ) ) :
	$videoMarkup = "[[File:$title|thumb|left|300px]]";
?>
<div id="VET-carousel-wrapper" class="VET-carousel-wrapper show">
	<div class="VET-suggestions-wrapper" id="VET-suggestions-wrapper"
	     style="padding:7px <?=$padding ?>px; background-color:#bbb; border: 1px solid #888">
		<h2 style="padding: 2px 5px 7px <?= $padding ?>px">Add a relevant video to this page</h2>
		<div id="VET-suggestions" class="VET-suggestions" style="width:100%;">
			<div>
				<ul class="carousel">
	<? foreach ( $thumbs as $thumb ) : ?>
					<li class="title-thumbnail" style="width: <?= $width ?>px;padding-right: 10px">
						<?= $thumb ?>
						<?//= F::app()->renderView( 'ThumbnailController', 'video', $params ); ?>
<!--						<figure>-->
<!--							<a href="http://video.armon.wikia-dev.com/wiki/File:Battleship_(2012)_-_Interview_Battleship_(2012)_-_Rihanna" class="video video-thumbnail small image lightbox " itemprop="video" itemscope="" itemtype="http://schema.org/VideoObject">-->
<!--								<img src="http://static.armon.wikia-dev.com/__cb20120525031307/video151/images/thumb/b/b0/Battleship_%282012%29_-_Interview_Battleship_%282012%29_-_Rihanna/250px-Battleship_%282012%29_-_Interview_Battleship_%282012%29_-_Rihanna.jpg" alt="Battleship (2012) - Interview Battleship (2012) - Rihanna" data-video-key="Battleship_(2012)_-_Interview_Battleship_(2012)_-_Rihanna" data-video-name="Battleship (2012) - Interview Battleship (2012) - Rihanna" width="160" height="90" itemprop="thumbnail">-->
<!--								<span class="duration" itemprop="duration">04:07</span>-->
<!--								<span class="play-circle"></span>-->
<!--								<meta itemprop="duration" content="PT04M07S">-->
<!--							</a>-->
<!--							<figcaption><strong>Battleship (2012) - Interview Battl...</strong></figcaption>-->
<!--						</figure>-->
<!--						<a href="http://video.armon.wikia-dev.com/wiki/File:Battleship_(2012)_-_Interview_Battleship_(2012)_-_Rihanna" title="Battleship (2012) - Interview Battleship (2012) - Rihanna" data-phrase="interview battleship" data-pos="">Add video</a>-->
						<form action="?action=edit" method="POST" >
							<input type="submit" style="height:16px;padding-bottom:3px;" value="Add Video"/>
							<input type="hidden" name="bodytextPrepend" value="<?= $videoMarkup ?>"/>
						</form>
					</li>
	<? endforeach ?>
				</ul>
			</div>
		</div>
	</div>
</div>
<? endif;