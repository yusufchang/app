<a name="comments"></a>
<h2 class="wikia_header"><?php echo wfMsg("blog-comments") ?></h2>
<div id="blog-comments" class="reset clearfix">
<?php
	if( count( $comments ) > 10 ):
		// show top input
?>
<div class="blog-comm-input reset clearfix">
	<form action="#" method="post" id="blog-comm-form-top">
		<!-- avatar -->
		<div class="blog-comm-input-avatar">
		<?php
			echo $avatar->getImageTag( 50, 50 );
		?>
		</div>
		<!-- textarea -->
		<div class="blog-comm-input-text">
		<textarea name="wpBlogComment" id="blog-comm-top"></textarea><br />
		<!-- submit -->
		<a href="#" name="wpBlogSubmit" id="blog-comm-submit-top" class="bigButton">
			<big><? echo wfMsg("blog-comment-post") ?></big>
			<small></small>
		</a>
		</div>
	</form>
</div>
<?php
	endif;

	if( ! $comments ):
		echo wfMsg( "blog-zero-comments" );
	else:
		echo "<ul id=\"blog-comments-ul\" >";
		foreach( $comments as $comment ):
?>
	<li>
		<div class="blog-comment">
		<a name="<?php echo isset( $comment["anchor"][2] ) ? $comment["anchor"][2] : "" ?>"></a>
		<div class="comment_avatar">
			<?php echo $comment["avatar"] ?>
		</div>
		<div class="comment">
			<div class="details">
				<strong><?php echo $comment["sig"] ?></strong>
				<span class="timestamp"><?php echo $comment["timestamp"] ?></span>
			</div>
			<?php
				echo $comment["text"];
			?>
		</div>
		</div>
		<br style="clear: both;" />
	</li>
<?php
		endforeach;
		echo "</ul>";
	endif; // comments
?>
<div class="blog-comm-input reset clearfix">
	<form action="#" method="post" id="blog-comm-form-bottom">
		<!-- avatar -->
		<div class="blog-comm-input-avatar">
		<?php
			echo $avatar->getImageTag( 50, 50 );
		?>
		</div>
		<!-- textarea -->
		<div class="blog-comm-input-text">
		<textarea name="wpBlogComment" id="blog-comm-bottom"></textarea><br />
		<!-- submit -->
		<a href="#" name="wpBlogSubmit" id="blog-comm-submit-bottom" class="bigButton">
			<big><? echo wfMsg("blog-comment-post") ?></big>
			<small></small>
		</a>
		</div>
	</form>
</div>

</div>
