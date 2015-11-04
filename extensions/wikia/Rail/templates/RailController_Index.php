<? if ( $isAside ): ?>
	<aside>
<? endif; ?>
	<div id="WikiaRail" class="WikiaRail<?= !empty($isGridLayoutEnabled) ? ' grid-2' : '' ?>">

		<?php
		// sort in reverse order (highest priority displays first)
		krsort($railModuleList);

		// render all our rail modules here
		foreach ($railModuleList as $priority => $callSpec) {
			echo F::app()->renderView(
				$callSpec[0], // controller
				$callSpec[1], // method
				$callSpec[2]  // method's params
			);
		}
		?>

		<!-- ADEN-2587 - not meant for production BEGIN -->
		<div id="NATIVE_TABOOLA_RAIL">
			<div id="Taboola_NATIVE_TABOOLA_RAIL">
				<div id="taboola-right-rail-thumbnails"></div>
				<script type="text/javascript">
					window._taboola = window._taboola || [];
					_taboola.push({
						mode: 'thumbnails-rr',
						container: 'taboola-right-rail-thumbnails',
						placement: 'Right Rail Thumbnails - Gaming',
						target_type: 'mix'
					});
				</script>
			</div>
		</div>
		<!-- ADEN-2587 - not meant for production END -->

		<? if ($loadLazyRail): ?>
			<div class="loading"></div>
		<? endif ?>

	</div>
<? if ( $isAside ): ?>
	</aside>
<? endif; ?>
