<?php
/**
 * Internationalisation file for Special:CategoryIntersection extension.
 *
 * @addtogroup Extensions
 */

$messages = array();

$messages['en'] = array(
	'categoryintersection' => 'Category Intersection API demonstration',
	'categoryintersection-desc' => 'A front-end for the "categoryintersection" API function to show what it can use and assist in understanding it for actual use via the MediaWiki API',
	'categoryintersection-header-title' => 'About',
	'categoryintersection-header-body' => 'This page is designed to show the types of things that the CategoryIntersection API function can do and make it easy to learn how to use it.  For more information please see: $1',
	'categoryintersection-docs-linktext' => 'Category Intersection MediaWiki API documentation',
	'categoryintersection-form-title' => 'Try it out',
	'categoryintersection-form-submit' => 'Find matches',
	'categoryintersection-and' => '- and -',
	'categoryintersection-limit' => 'Limit',
	'categoryintersection-instructions-title' => 'Instructions',
	'categoryintersection-instructions' => 'Type in two categories (including the "Category:" part at the beginning) to see all articles which are in BOTH of those categories and to see an example URL of how that was obtained using the API.',
	'categoryintersection-results-title' => 'API Results',
	'categoryintersection-noresults' => 'No results. Please check that the category names are correct if you think there should be results.',
	'categoryintersection-query-used' => 'API query used:',
	'categoryintersection-footer-title' => 'Examples',
	'categoryintersection-footer-body' => 'Here are some example queries, just to show a few of the types of things that can be done with this API. There are numerous other cool possibilies though... Be creative!<br/><br/>The default examples are for LyricWiki. Please edit [[MediaWiki:categoryintersection-footer-examples]] to make examples for this wiki.',
	'categoryintersection-footer-examples' => '
Category:Artists_S
Category:Hometown/Sweden/Stockholm

Category:Artist
Category:Hometown/United_States/Pennsylvania/Pittsburgh

Category:Hometown/Germany/North_Rhine-Westphalia
Category:Genre/Rock

Category:Artists_S
Category:Genre/Rock
Category:Hometown/United_States/California

Category:Album
Category:Genre/Nerdcore_Hip_Hop

Category:Language/Simlish

Category:Label/Ultra_Records
Category:Hometown/Canada

Category:Genre/Hip_Hop
Category:Hometown/United_States/California
',
	'categoryintersection-summary' => 'Searched for categories: $1; limit: $2; number of results returned: $3',
);

$messages['de'] = array(
	'categoryintersection' => 'Seiten ermitteln, die sich in zwei gleichen Kategorien befinden',
	'categoryintersection-desc' => '<!-- not used anymore? -->A front-end for the "categoryintersection" API function to show what it can use and assist in understanding it for actual use via the MediaWiki API',
	'categoryintersection-header-title' => 'Über',
	'categoryintersection-header-body' => 'Diese Seite dient zur Veranschaulichung der Funktionalität um Seiten zu ermitteln, die sich in zwei gleichen Kategorien befinden. Sofern du mehr über die Hintergründe erfahren möchtest, siehe diese Dokumentation (englisch): $1',
	'categoryintersection-form-title' => 'Probiere es aus',
	'categoryintersection-form-submit' => 'Übereinstimmungen finden',
	'categoryintersection-and' => '- und -',
	'categoryintersection-instructions-title' => 'Anleitung',
	'categoryintersection-instructions' => 'Gib zwei Kategorien an ("Kategorie:" muss sich am Anfang deiner Eingabe befinden), um alle Artikel aufzulisten die sich in beiden angegebenen Kategorien befinden. Dies erzeugt ebenfalls eine API-URL.',
	'categoryintersection-results-title' => 'API-Resultate',
	'categoryintersection-noresults' => 'Keine Ergebnisse für diese Anfrage. Bitte stelle sicher, dass beide Kategorienamen korrekt angegeben sind.',
	'categoryintersection-query-used' => 'API-Abfrage:',
	'categoryintersection-footer-title' => 'Beispiele',
	'categoryintersection-footer-body' => 'Dies sind einige Beispiele, um die Möglichkeiten dieser Funktionalität zu veranschaulichen. Es gibt unzählige Möglichkeiten, sie zu nutzen... also sei kreativ!<br/><br/>Diese Standardbeispiele sind LyricWiki-spezifisch. Bitte bearbeite diese unter [[MediaWiki:categoryintersection-footer-examples]], um lokale Beispiele anzugeben.',
	'categoryintersection-summary' => 'Suchergebnisse für Seiten in den Kategorien "$1"; Limit "$2"; Gesamtanzahl der Suchergebnisse: $3',
);

$messages['fr'] = array(
	'categoryintersection' => 'Démonstration de Category Intersection de l’API',
	'categoryintersection-desc' => 'Un frontal pour la fonction « categoryintersection » de l’API pour montrer comment elle peut être utilisée et favoriser la compréhension de son utilisation actuelle via l’API MediaWiki.',
	'categoryintersection-header-title' => 'À propos',
	'categoryintersection-header-body' => "Cette page est conçue pour montrer les choses que la fonction CategoryIntersection de l’API peut faire et faciliter l'apprentissage de son utilisation. Pour plus d’informations, veuillez consulter : $1",
	'categoryintersection-docs-linktext' => "Documention de l'API MediaWiki Category Intersection",
	'categoryintersection-form-title' => 'Essayez',
	'categoryintersection-form-submit' => 'Trouver des correspondances',
	'categoryintersection-and' => '- et -',
	'categoryintersection-limit' => 'Limite',
	'categoryintersection-instructions' => 'Saisissez deux catégories (en incluant la partie « Category: » au début) pour voir tous les articles qui sont dans les deux catégories à la fois et voir une URL d’exemple montrant comment cela a été obtenu en utilisant l’API.',
	'categoryintersection-results-title' => 'Résultats',
	'categoryintersection-noresults' => 'Aucun résultat. Veuillez vérifier que les noms des catégories sont corrects si vous pensez que vous devriez obtenir des résultats.',
	'categoryintersection-query-used' => 'Requête de l’API utilisée :',
	'categoryintersection-footer-title' => 'Exemples',
	'categoryintersection-footer-body' => 'Voici quelques requêtes d’exemple, simplement pour montrer quelques petites choses qui peuvent être effectuées avec cette API. Il y a toutefois de nombreuses autres possibilités sympathiques... Soyez créatif !<br/><br/>Les exemples par défaut proviennent de LyricWiki veuillez modifier [[MediaWiki:categoryintersection-footer-examples]] pour prendre des exemples sur ce wiki.',
	'categoryintersection-summary' => 'Catégories recherchées : $1; limite: $2; nombre de résultats retournés : $3',
);

$messages['pl'] = array(
	'categoryintersection' => 'Demonstracja funkcji API CategoryIntersection',
	'categoryintersection-desc' => 'Interfejs dla funkcji API "categoryintersection", służący demonstracji jej możliwości we właściwym interfejsie wiki.',
	'categoryintersection-header-title' => 'Opis',
	'categoryintersection-header-body' => 'Ta strona służy przedstawieniu możliwości funkcji API CategoryIntersection i umożliwia łatwą naukę jej obsługi. Aby uzyskać więcej informacji zajrzyj na $1',
	'categoryintersection-docs-linktext' => 'Dokumentacja funkcji API CategoryIntersection',
	'categoryintersection-form-title' => 'Wypróbuj',
	'categoryintersection-form-submit' => 'Szukaj',
	'categoryintersection-and' => '- i -',
	'categoryintersection-instructions-title' => 'Instrukcja',
	'categoryintersection-instructions' => 'Wprowadź nazwy dwóch kategorii (włącznie z przedrostkiem "Kategoria:") aby zobaczyć wszystkie artykuły należące do obu kategorii i aby zobaczyć przykładowy adres URL, który wygenerował taki rezultat dzięki wykorzystaniu API.',
	'categoryintersection-results-title' => 'Wyniki API',
	'categoryintersection-noresults' => 'Brak wyników. Upewnij się, że podano poprawne nazwy kategorii.',
	'categoryintersection-query-used' => 'Użyta metoda API:',
	'categoryintersection-footer-title' => 'Przykłady',
	'categoryintersection-footer-body' => 'Poniżej znajdują się przykładowe zapytania, służące demonstracji funkcjonalności API. Istnieje więcej ciekawych możliwości... bądź kreatywny!<br/><br/> Domyślne przykłady stworzono z myślą o LyricWiki. Zmodyfikuj [[MediaWiki:categoryintersection-footer-examples]] aby dodać przykłady zapytań dla tej wiki.',
	'categoryintersection-summary' => 'Szukano kategorii: $1; limit: $2; liczba wyników: $3',
);

$messages['ru'] = array(
	'categoryintersection' => 'Демонстрация приложения Пересечения в категориях',
	'categoryintersection-desc' => 'Интерфейс приложения "categoryintersection" создан на основе API. Он демонстрирует возможности MediaWiki API и помогает понять, как его использовать.',
	'categoryintersection-header-title' => 'О функции',
	'categoryintersection-header-body' => 'Эта страница предназначена для того, чтобы продемонстрировать, что можно сделать с помощью функции CategoryIntersection API, и помочь понять, как её правильно использовать. Подробнее смотрите: $1',
	'categoryintersection-docs-linktext' => 'Документация MediaWiki API для Пересечения в категориях',
	'categoryintersection-form-title' => 'Попробуйте',
	'categoryintersection-form-submit' => 'Найти',
	'categoryintersection-and' => '- и -',
	'categoryintersection-limit' => 'Показать',
	'categoryintersection-instructions-title' => 'Инструкция',
	'categoryintersection-instructions' => 'Впишите в окошки две категории (включая само слово Категория в начале), чтобы увидеть все статьи, которые входят сразу в обе эти категории, и URL-ссылку на результат поиска.',
	'categoryintersection-results-title' => 'Результат',
	'categoryintersection-noresults' => 'Нет результатов. Пожалуйста, проверьте, что названия категорий написаны правильно, если вы считаете, что результаты поиска должны быть.',
	'categoryintersection-query-used' => 'API запрос:',
	'categoryintersection-footer-title' => 'Примеры',
	'categoryintersection-footer-body' => 'Вот примеры запросов, которые показывают, как можно использовать это API приложение. Существуют множество других вариантов... Будьте креативны!<br/><br/>По умолчанию даны примеры из LyricWiki. Пожалуйста, отредактируйте страницу [[MediaWiki:categoryintersection-footer-examples]], чтобы добавить примеры из своей вики.',
	'categoryintersection-summary' => 'Поиск по категориям: $1; показать: $2; найдено: $3',
);
