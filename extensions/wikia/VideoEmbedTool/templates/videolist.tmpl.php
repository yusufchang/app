<?php

global $wgRequest;

$width = 250;
$padding = 5;

$timestamp = wfTimestampNow();

$thumbs = [];
foreach ( $items as $item ) {
	$title = $item['title'];
	$file = wfFindFile( $title );
	if ( !$file ) {
		continue;
	}

	$thumbs[htmlspecialchars($title)] = $file->transform( [ 'width' => $width - $padding ] )->toHtml();
}

if ( !empty( $thumbs ) ) :
?>
<div id="VET-carousel-wrapper" class="VET-carousel-wrapper show">
	<div class="VET-suggestions-wrapper" id="VET-suggestions-wrapper"
	     style="padding:7px <?=$padding ?>px; background-color:#bbb; border: 1px solid #888">
		<h2 style="padding: 2px 5px 7px <?= $padding ?>px">Add a relevant video to this page</h2>
		<div id="VET-suggestions" class="VET-suggestions" style="width:100%;">
			<div>
				<ul class="carousel">
	<? foreach ( $thumbs as $title => $thumb ) : ?>
		<? 	$videoMarkup = "[[File:$title|thumb|left|300px]]"; ?>
					<li class="title-thumbnail" style="width: <?= $width ?>px;padding-right: 10px">
						<?= $thumb ?>
						<form action="?action=edit" method="POST" >
							<input type="submit" style="height:16px;padding-bottom:3px;" value="Add Video"/>
							<input type="hidden" name="bodytextPrepend" value="<?= $videoMarkup ?>"/>

							<input type="hidden" value="" name="wpSection" />
							<input type="hidden" value="<?= $timestamp ?>" name="wpStarttime" />
							<input type="hidden" value="<?= $timestamp ?>" name="wpEdittime" />
							<input type="hidden" value="" name="wpScrolltop" id="wpScrolltop" />
							<input type="hidden" name="wpTextbox1" value="---originalcontent---"/>
							<input type="hidden" name="wpSummary" value="Add relevant video: <?=$title?>" id="wpSummary" />
							<input name="wpAutoSummary" type="hidden" value="" />
							<input type="hidden" value="<?=$wgRequest->getVal('token')?>" name="wpEditToken" />
						</form>
					</li>
	<? endforeach ?>
				</ul>
			</div>
		</div>
	</div>
</div>
<? endif;