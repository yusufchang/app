<!doctype html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">
<head>

<meta http-equiv="Content-Type" content="<?= $mimeType ?>; charset=<?= $charset ?>">
<?php if ( BodyController::isResponsiveLayoutEnabled() ) : ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
<?php else : ?>
	<meta name="viewport" content="width=1200">
<?php endif ?>
<?= $headLinks ?>

<title><?= $pageTitle ?></title>

<!-- CSS injected by skin and extensions -->
<?= $cssLinks ?>

<?
	/*
	Add the wiki and user-specific overrides last.  This is a special case in Oasis because the modules run
	later than normal extensions and therefore add themselves later than the wiki/user specific CSS is
	normally added. See Skin::setupUserCss()
	*/
?>
<? if ( !empty( $wg->OasisLastCssScripts ) ): ?>
	<? foreach( $wg->OasisLastCssScripts as $src ): ?>
		<link rel="stylesheet" href="<?= $src ?>">
	<? endforeach ?>
<? endif ?>

<? /* RT #68514: load global user CSS (and other page specific CSS added via "SkinTemplateSetupPageCss" hook) */ ?>
<? if ( $pageCss ): ?>
	<style type="text/css"><?= $pageCss ?></style>
<? endif ?>

<?= $topScripts ?>
<?= $globalBlockingScripts; /*needed for jsLoader and for the async loading of CSS files.*/ ?>

<!-- Make IE recognize HTML5 tags. -->
<!--[if IE]>
	<script>/*@cc_on'abbr article aside audio canvas details figcaption figure footer header hgroup mark menu meter nav output progress section summary time video'.replace(/\w+/g,function(n){document.createElement(n)})@*/</script>
<![endif]-->

<? if ( !$jsAtBottom ): ?>
	<!--[if lt IE 8]>
		<script src="<?= $wg->ResourceBasePath ?>/resources/wikia/libraries/json2/json2.js"></script>
	<![endif]-->

	<!--[if lt IE 9]>
		<script src="<?= $wg->ResourceBasePath ?>/resources/wikia/libraries/html5/html5.min.js"></script>
	<![endif]-->

	<!-- Combined JS files and head scripts -->
	<?= $jsFiles ?>
<? endif ?>

<? if ( $displayAdminDashboard ): ?>
	<!--[if IE]><script src="<?= $wg->ResourceBasePath ?>/resources/wikia/libraries/excanvas/excanvas.js"></script><![endif]-->
<? endif ?>

<?= $headItems ?>

	<? if ( $share == 1 ): ?>
		<style>
			#share-button {
				background-color:white;
				border:1px solid black;
				padding:10px;
				position:absolute;
				top:-9999px;
				left:-9999px;
				z-index:9999;
				box-shadow:0 1px 3px rgba(0,0,0,.4);
			}
		</style>
	<? elseif ( $share == 2 ): ?>
		<link rel="stylesheet" href="/skins/oasis/js/SelectedShareTest/selection-sharer.css"/>
	<? elseif ( $share == 3 ): ?>
		<link rel="stylesheet" href="/skins/oasis/js/SelectedShareTest/main-style.css"/>
		<link rel="stylesheet" href="/skins/oasis/js/SelectedShareTest/tooltipster.min.css"/>
	<? endif ?>

</head>
<body class="<?= implode(' ', $bodyClasses) ?>"<?= $itemType ?>>
<? if ( BodyController::isResponsiveLayoutEnabled() ): ?>
	<div class="background-image-gradient"></div>
<? endif ?>

<?= $comScore ?>
<?= $quantServe ?>
<?= $googleAnalytics ?>
<?= $amazonDirectTargetedBuy ?>
<?= $dynamicYield ?>
<?= $ivw2 ?>
<div class="WikiaSiteWrapper">
	<?= $body ?>
	<div id="share-button"><button>Share!</button></div>

	<?php
		echo F::app()->renderView('Ad', 'Index', ['slotName' => 'GPT_FLUSH', 'pageTypes' => ['*']]);
		if (empty($wg->SuppressAds)) {
			echo F::app()->renderView('Ad', 'Index', ['slotName' => 'INVISIBLE_1', 'pageTypes' => ['corporate', 'all_ads']]);
			if (!$wg->EnableWikiaHomePageExt) {
				echo F::app()->renderView('Ad', 'Index', ['slotName' => 'INVISIBLE_2']);
			}
		}
		echo F::app()->renderView('Ad', 'Index', ['slotName' => 'SEVENONEMEDIA_FLUSH', 'pageTypes' => ['*']]);
	?>
</div>
<? if( $jsAtBottom ): ?>
	<!--[if lt IE 8]>
		<script src="<?= $wg->ResourceBasePath ?>/resources/wikia/libraries/json2/json2.js"></script>
	<![endif]-->

	<!--[if lt IE 9]>
		<script src="<?= $wg->ResourceBasePath ?>/resources/wikia/libraries/html5/html5.min.js"></script>
	<![endif]-->

	<!-- Combined JS files and head scripts -->
	<?= $jsFiles ?>
<? endif ?>

<script type="text/javascript">/*<![CDATA[*/ Wikia.LazyQueue.makeQueue(wgAfterContentAndJS, function(fn) {fn();}); wgAfterContentAndJS.start(); /*]]>*/</script>
<?php if ($wg->EnableAdEngineExt) { ?>
<script type="text/javascript">/*<![CDATA[*/ if (typeof AdEngine_trackPageInteractive === 'function') {wgAfterContentAndJS.push(AdEngine_trackPageInteractive);} /*]]>*/</script>
<?php } ?>
<?= $bottomScripts ?>
<?= $cssPrintLinks ?>

<? if ( $share == 1 ): ?>
	<script src="/skins/oasis/js/SelectedShareTest/SelectedShare.js"></script>
<? elseif ( $share == 2 ): ?>
	<script src="/skins/oasis/js/SelectedShareTest/selection-sharer.js"></script>
	<script>
		$('#WikiaArticle p').selectionSharer();
	</script>
<? elseif ( $share == 3 ): ?>
	<script src="/skins/oasis/js/SelectedShareTest/jquery.tooltipster.min.js"></script>
	<script src="/skins/oasis/js/SelectedShareTest/contentshare.js"></script>
<? endif ?>

</body>

<?= wfReportTime() . "\n" ?>
<?= F::app()->renderView('Ad', 'Config') ?>

</html>
