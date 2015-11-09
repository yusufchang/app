<!doctype html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">
<head>

<meta http-equiv="Content-Type" content="<?= $mimeType ?>; charset=<?= $charset ?>">
<?php if ( BodyController::isResponsiveLayoutEnabled() || BodyController::isOasisBreakpoints() ) : ?>
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

</head>
<body class="<?= implode(' ', $bodyClasses) ?>"<?= $itemType ?>>

<? if ( BodyController::isResponsiveLayoutEnabled() || BodyController::isOasisBreakpoints() ): ?>
	<div class="background-image-gradient"></div>
<? endif ?>

<?= $comScore ?>
<?= $quantServe ?>
<?= $googleAnalytics ?>
<?= $amazonMatch ?>
<?= $openXBidder ?>
<?= $dynamicYield ?>
<?= $ivw2 ?>
<?= $rubiconRtp ?>
<div class="WikiaSiteWrapper">
	<?= $body ?>

	<?php
		echo F::app()->renderView('Ad', 'Index', ['slotName' => 'GPT_FLUSH', 'pageTypes' => ['*']]);
		echo F::app()->renderView('Ad', 'Index', ['slotName' => 'TURTLE_FLUSH', 'pageTypes' => ['*']]);
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

<!-- ADEN-2591 not meant for production START -->
<script type="text/javascript">
	(function() {
		var infoboxes = document.getElementsByClassName('portable-infobox');
		Object.keys(infoboxes).forEach(function(key) {
			if( infoboxes[key] && infoboxes[key].classList ) {
				infoboxes[key].classList.add('p402_hide');
			}
		});
	})();
	(function() {
		var ARTICLE_URL = window.location.href;
		var CONTENT_ID = 'everything';
		document.write(
			'<scr'+'ipt '+
			'src="//survey.g.doubleclick.net/survey?site=_ltvovxgnp5p3wkkemdja6sd2wm'+
			'&amp;url='+encodeURIComponent(ARTICLE_URL)+
			(CONTENT_ID ? '&amp;cid='+encodeURIComponent(CONTENT_ID) : '')+
			'&amp;random='+(new Date).getTime()+
			'" type="text/javascript">'+'\x3C/scr'+'ipt>');
	})();
</script>
<!-- ADEN-2591 not meant for production END -->

</body>

<?= wfReportTime() . "\n" ?>
<?= F::app()->renderView('Ad', 'Config') ?>

</html>
