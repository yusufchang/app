<?
/**
 * @var $languageCode String
 * @var $languageDirection String
 * @var $pageTitle String
 * @var $allowRobots String
 * @var $mimeType String
 * @var $charSet String
 * @var $headLinks String
 * @var $cssLinks String
 * @var $globalVariablesScript String
 * @var $jsClassScript String
 * @var $headItems String
 * @var $bodyClasses String[]
 * @var $trackingCode String
 * @var $wikiaNavigation String
 * @var $pageContent String
 * @var $wikiaFooter String
 * @var $jsBodyFiles String
 * @var $jsExtensionPackages String
 * @var $topLeaderBoardAd String
 * @var $inContentAd String
 * @var $modalInterstitial String
 * @var $floatingAd String
 */
?>
<!DOCTYPE html>
<html lang=<?= $languageCode ;?> dir=<?= $languageDirection ;?>>
<head>
	<meta http-equiv=Content-Type content="<?= "{$mimeType};charset={$charSet}" ;?>">
	<?= $cssLinks ;?>
	<title><?= $pageTitle ;?></title>
	<?= $headLinks ;?>
	<?= $headItems ;?>
	<?= $globalVariablesScript ;?>
	<?= $jsClassScript ;?>
	<? if( !$allowRobots ): ?>
		<meta name=robots content='noindex, nofollow'>
	<? endif; ?>
	<meta name=HandheldFriendly content=true>
	<meta name=MobileOptimized content=width>
	<meta name=viewport content="initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
	<meta name=apple-mobile-web-app-capable content=yes>
	<? if ( !empty( $smartBannerConfig ) ) : ?>
		<? foreach( $smartBannerConfig as $name => $content ) : ?>
			<meta name="<?= $name ?>" content="<?= $content ?>">
		<? endforeach; ?>
	<? endif; ?>
</head>
<body class="<?= implode(' ', $bodyClasses) ?>">
<?= $wikiaNavigation ;?>
<?= $topLeaderBoardAd ;?>
<?= $pageContent ;?>
<?= $wikiaFooter ;?>
<div id=wkCurtain>&nbsp;</div>
<?= $toc; ?>
<?= $jsBodyFiles ;?>
<?= $jsExtensionPackages ?>
<?= $trackingCode ;?>
<script>
	function markInfobox($infobox) {
		var adHtml = '<div id="infobox-ad" style="width: 300px; height: 250px; background: #666; margin: 20px auto; color: #fff; font-size: 50px; text-align: center; line-height: 250px">ad</div>'
		$infobox.css('outline', 'red 1px solid');
		$infobox.after(adHtml);
	}

	var selectors = [
		'table.infobox',
		'div.bigTable',
		'#infoboxinternal'
	], i, len, $infobox;

	for (i = 0, len = selectors.length; i < len; i += 1) {
		$infobox = $(selectors[i]).first();
		if ($infobox.length === 1) {
			markInfobox($infobox);
			break;
		}
	}
</script>
</body>
</html>
