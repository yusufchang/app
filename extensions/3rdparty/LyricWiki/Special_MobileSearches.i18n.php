<?php
$messages = array();

$messages['en'] = array(
	'mobilesearches' => 'Mobile Searches',
	'mobilesearches-stats-header' => 'Please note that since successful responses get cached by Varnish, these numbers will show a high skew towards "not-found".',
	'mobilesearches-stats-timeperiod' => 'Time period',
	'mobilesearches-stats-numfound' => 'Num found',
	'mobilesearches-stats-numnotfound' => 'Not found',
	'mobilesearches-stats-period-today' => 'Today',
	'mobilesearches-stats-period-thisweek' => 'This week',
	'mobilesearches-stats-period-thismonth' => 'This month',
	'mobilesearches-header-requests' => 'Requests',
	'mobilesearches-header-artist' => 'Artist',
	'mobilesearches-header-song' => 'Song',
	'mobilesearches-header-looked-for' => 'Titles looked for',
	'mobilesearches-header-fixed' => 'Fixed',
	'mobilesearches-mark-as-fixed' => 'Mark a song as fixed (does a test first):',
	'mobilesearches-fixed' => 'Fixed',
	'mobilesearches-artist' => 'Artist:',
	'mobilesearches-song' => 'Song:',
	'mobilesearches-intro' => '<em>Once you have created a missing page, made a redirect for it, or otherwise fixed it so that it should 
			no longer be a failed request... type the artist and song name into the form at the top of the page and click the "Fixed" button and the SOAP webservice will test the song again. 
			If the song is then retrieved successfully, it will be removed from the failures list and the cache will be cleared 
			so that you can see the updated list right away.</em><br/>
			<br/>
			This page differs from [[Special:Soapfailures]] primarily in that it only shows the requests that came from the <a href=\'https://market.android.com/details?id=com.wikia.lyricwiki\'>LyricWiki Android app</a> and the LyricWiki iPhone app (once there is one).<br/>
			<br/>
			Discuss the [[LyricWiki_talk:SOAP|SOAP webservice]].

			<br/><br/>
',
);

$messages['de'] = array(
	'mobilesearches' => 'Mobile Suche',
	'mobilesearches-header-requests' => 'Anfragen',
	'mobilesearches-header-looked-for' => 'Bereits gesuchte Titel',
	'mobilesearches-header-fixed' => 'Korrigiert',
	'mobilesearches-fixed' => 'Korrigiert',
);

$messages['fr'] = array(
	'mobilesearches' => 'Recherches sur mobile',
	'mobilesearches-stats-timeperiod' => 'Période',
	'mobilesearches-stats-numfound' => 'Num. trouvé',
	'mobilesearches-stats-numnotfound' => 'Non trouvé',
	'mobilesearches-stats-period-today' => 'Aujourd’hui',
	'mobilesearches-stats-period-thisweek' => 'Cette semaine',
	'mobilesearches-stats-period-thismonth' => 'Ce mois',
	'mobilesearches-header-requests' => 'Requêtes',
	'mobilesearches-header-artist' => 'Artiste',
	'mobilesearches-header-song' => 'Chanson',
	'mobilesearches-header-looked-for' => 'Titres recherchés',
	'mobilesearches-header-fixed' => 'Entier',
	'mobilesearches-mark-as-fixed' => 'Marquer une chanson comme corrigée (effectue un test d’abord) :',
	'mobilesearches-fixed' => 'Entier',
	'mobilesearches-artist' => 'Artiste :',
	'mobilesearches-song' => 'Chanson :',
	'mobilesearches-intro' => '<em>Une fois que vous avez créé une page manquante, créez-lui une redirection afin que la requête n’échoue plus... Saisissez le nom de l’artiste et le titre de la chanson dans le formulaire en haut de la page et cliquez sur le bouton « Corrigé » et le webservice SOAP testera la chanson à nouveau.
Si la chanson est alors récupérée avec succès, elle sera retirée de la liste des échecs et le cache sera vidé afin que vous puissiez voir la liste tout de suite.</em><br/>
<br/>
Cette page diffère de [[Special:Soapfailures]] principalement du fait qu’elle n’affiche uniquement que les requêtes qui proviennent de l’<a href=’https://market.android.com/details?id=com.wikia.lyricwiki’>application Android LyricWiki</a> et l’application iPhone LyricWiki (quand il y en aura une).<br/>
<br/>
Discuter du [[LyricWiki_talk:SOAP|webservice SOAP]].
<br/><br/>',
);
