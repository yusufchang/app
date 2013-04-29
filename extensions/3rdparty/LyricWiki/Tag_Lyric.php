<?php

#
# Simple lyric parser extension for mediawiki.
# Written by Trevor Peacock, 1 June 2006
# version 0.2.1
# Tested on MediaWiki 1.6devel, PHP 5.0.5 (apache2handler)
#
# developed to support the notation of lyrics in mediawiki.
# see http://lyrics.wikia.com/User:TrevorP/Notation
#
# Features:
#  * Allows basic lyric notation
#  * Optional CSS styling embedded in every page
#  * CSS styling not embedded in meta tage, rather @import-ed from extension file
#
# To install, copy this file into "extensions" directory, and add
# the following line to the end of LocalSettings.php
# (above the  ? >  )
#
#   require("extensions/lyric.php");
#

################################################################################
# Functions
#
# This section has no configuration, and can be ignored.
#

require_once 'extras.php';

################################################################################
# Extension Credits Definition
#
# This section has no configuration, and can be ignored.
#

if(isset($wgScriptPath))
{
$wgExtensionCredits["parserhook"][]=array(
  'name' => 'Lyric Extension',
  'version' => '0.2.1',
  'url' => 'http://wiki.peacocktech.com/wiki/LyricExtension',
  'author' => '[http://about.peacocktech.com/trevorp/ Trevor Peacock]',
  'description' => 'Adds features allowing easy notation of lyrics in mediawiki' );
}

################################################################################
# Lyric Render Section
#
# This section has no configuration, and can be ignored.
#
# This section renders <lyric> tags. It forces a html break on every line,
# and styles the section with a css id.
# this id can either be in the mediawiki css files, or defined by the extension
#

if(isset($wgScriptPath))
{
	#Instruct mediawiki to call LyricExtension to initialise new extension
	$wgExtensionFunctions[] = "lyricTag";
	$wgHooks['ParserFirstCallInit'][] = "lyricTag_InstallParser";
	$wgHooks['BeforePageDisplay'][] = "lyricTagCss";

	// Use this pre-existing Wikia-specific hook to apply the index policy changes after the defaults are set (which comes after parsing).
	$wgHooks['AfterViewUpdates'][] = "efApplyIndexPolicy";
}

#Install extension
function lyricTag()
{
	// Keep track of whether this is the first <lyric> tag on the page - this is to prevent too many Ringtones ad links.
	global $wgFirstLyricTag;
	$wgFirstLyricTag = true;
}

function lyricTag_InstallParser( Parser $parser ) {
	#install hook on the element <lyric>
	$parser->setHook("lyric", "renderLyricTag");
	$parser->setHook("lyrics", "renderLyricTag");
	return true;
}

function lyricTagCss(OutputPage $out)
{
	$css = <<<DOC
.lyricbox
{
	padding: 1em 1em 0;
	border: 1px solid silver;
	color: black;
	background-color: #ffffcc;
}
.lyricsbreak{
	clear:both;
}
DOC
;
	$out->addScript("<style type='text/css'>/*<![CDATA[*/\n".$css."\n/*]]>*/</style>");

	return true;
}

function renderLyricTag($input, $argv, Parser $parser)
{
	wfProfileIn( __METHOD__ );

	#make new lines in wikitext new lines in html
	$transform = str_replace(array("\r\n", "\r","\n"), "<br/>", trim($input));

	$isInstrumental = (strtolower(trim($transform)) == "{{instrumental}}");

	// If appropriate, build ringtones links.
	GLOBAL $wgFirstLyricTag, $wgLyricTagDisplayRingtone;
	$ringtoneLink = "";

	// For whatever reason, the links were not showing up after page-edits.
	// It seems that the parser is called multiple-times when saving a page-edit.
	$wgFirstLyricTag = true;

	$retVal = "";
	// NOTE: we put the link here even if wfAdPrefs_doRingtones() is false since ppl all share the article-cache, so the ad will always be in the HTML.
	// If a user has ringtone-ads turned off, their CSS will make the ad invisible.
	if( !empty( $wgLyricTagDisplayRingtone ) && $wgFirstLyricTag ){
		GLOBAL $wgExtensionsPath;
		$imgPath = "$wgExtensionsPath/3rdparty/LyricWiki";
		$artist = $parser->mTitle->getDBkey();
		$colonIndex = strpos("$artist", ":");
		$songTitle = $parser->mTitle->getText();
		$artistLink = $artist;
		$songLink = $songTitle;
		if($colonIndex !== false){
			$artist = substr($artist, 0, $colonIndex);
			$songTitle = substr($songTitle, $colonIndex+1);

			$artistLink = str_replace(" ", "+", $artist);
			$songLink = str_replace(" ", "+", $songTitle);
		}
		$artistLink = str_replace("_", "+", $artistLink);
		$songLink = str_replace("_", "+", $songLink);
		$href = "<a href='http://www.ringtonematcher.com/co/ringtonematcher/02/noc.asp?sid=WILWros&amp;artist=".urlencode($artistLink)."&amp;song=".urlencode($songLink)."' rel='nofollow' target='_blank'>";
		$ringtoneLink = "<div class='rtMatcher'>";
		$ringtoneLink.= "$href<img src='" . $imgPath . "/phone_left.gif' alt='phone' width='16' height='17'/> ";
		$ringtoneLink.= "Send \"$songTitle\" Ringtone to your Cell";
		$ringtoneLink.= " <img src='" . $imgPath . "/phone_right.gif' alt='phone' width='16' height='17'/></a>";
		$ringtoneLink.= "</div>";
		$wgFirstLyricTag = false;
	}

	#parse embedded wikitext
	$transform = $parser->parse($transform, $parser->mTitle, $parser->mOptions, false, false)->getText();

	$retVal.= lyrictag_getNoscriptTag();
	$retVal.= "<div class='lyricbox'>";
	$retVal.= ($isInstrumental?"":$ringtoneLink); // if this is an instrumental, just a ringtone link on the bottom is plenty.
	$retVal.= lyrictag_obfuscateText($transform);
	$retVal.= $ringtoneLink;
	$retVal.= "<div class='lyricsbreak'></div>\n"; // so that we can have stuff in the box (like videos & awards) even if the lyrics are short.
	$retVal.= "</div>";

	wfProfileOut( __METHOD__ );
	return $retVal;
}

////
// Returns the HTML for a noscript  tag which will hide the lyrics if javascript is disabled and give a message to the end-user explaining what happened.
////
function lyrictag_getNoscriptTag(){
    return "<noscript><div class='gracenote-header'>You must enable javascript to view this page.  This is a requirement of our licensing agreement with music LyricFind.</div>".
        "<style type='text/css'>".
        ".lyricbox{display:none !important;}".
        "</style>".
        "</noscript>\n";
} // end lyrictag_getNoscriptTag()

function lyrictag_obfuscateText($text){
    require_once __DIR__ . '/utf8ToUnicode.php';

    // Copy-protection: encode the contents of each line.  Will not encode anything inside of "<" and ">" characters (because that would break any HTML).
    $LINE_BREAK = "<br />"; // this is the format in which it comes out of the parser.
    $LT_UNICODE = 60;
    $GT_UNICODE = 62;
    $lines = explode($LINE_BREAK, $text);
    $lyrics = "";
    $isInsideTag = false;
    foreach($lines as $oneLine){
        $charsFromLyrics = utf8ToUnicode($oneLine);
        foreach($charsFromLyrics as $unicodeValue){
            if($isInsideTag){
                $unicodeAsArray = array($unicodeValue); // assigned so it can be passed by reference.
                $lyrics .= unicodeToUtf8($unicodeAsArray);
                if($GT_UNICODE == $unicodeValue){
                    $isInsideTag = false;
                }
            } else {
                if($LT_UNICODE == $unicodeValue){
                    $lyrics .= "<";
                    $isInsideTag = true;
                } else {
                    $lyrics .= "&#$unicodeValue;";
                }
            }
        }
        $lyrics .= $LINE_BREAK;
    }

    # Prevent over-encoding of special HTML-encoded characters.
    # TODO: Is it safe to just make sure all /&([0-9a-zA-Z]{2,4});/ are put back to normal text?
    $lyrics = str_replace( "&#38;&#110;&#98;&#115;&#112;&#59;", "&nbsp;", $lyrics );
    $lyrics = str_replace( "&#38;&#35;&#49;&#54;&#48;&#59;", "&#160;", $lyrics); // fb#42619
    $lyrics = str_replace( "&#38;&#97;&#109;&#112;&#59;", "&amp;", $lyrics); // rt#35365
    $lyrics = str_replace( "&#38;&#103;&#116;&#59;", "&gt;", $lyrics ); // fb#16034
    $lyrics = str_replace( "&#38;&#108;&#116;&#59;", "&lt;", $lyrics );

    return substr($lyrics, 0, strlen($lyrics) - strlen($LINE_BREAK));
} // end lyrictag_obfuscateText()

/**
 * The parser tag may have set a parser option (which gets cached in the parser-cache) indicating that
 * this page should have a certain index policy.
 *
 * @param WikiPage $wikiPage
 */
function efApplyIndexPolicy($wikiPage){
	global $wgUser;
	wfProfileIn( __METHOD__ );

	$parserOptions = $wikiPage->makeParserOptions( $wgUser );
	$parserOutput = $wikiPage->getParserOutput( $parserOptions );
	if(is_object($parserOutput)){
		$indexPolicy = $parserOutput->getIndexPolicy();
		if(!empty($indexPolicy)){
			global $wgOut;
			$wgOut->setIndexPolicy($indexPolicy);
		}
	}

	wfProfileOut( __METHOD__ );
	return true;
} // end efApplyIndexPolicy()
