<section id="WikiaPage" class="WikiaPage<?= empty( $wg->OasisNavV2 ) ? '' : ' V2' ?>">
	<div id="WikiaPageBackground" class="WikiaPageBackground"></div>
	<div class="WikiaPageContentWrapper">
		<!-- ADEN-2591 not meant for production START -->
		<div class="p402_premium">
			<div id="WikiaArticle" class="WikiaArticle">
				<?= $bodytext ?>
			</div>
		</div>
		<script type="text/javascript">
			try { _402_Show(); } catch(e) {}
		</script>
		<!-- ADEN-2591 not meant for production END -->
	</div>
</section>
