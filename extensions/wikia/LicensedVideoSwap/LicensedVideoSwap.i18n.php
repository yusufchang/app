<?php

/**
* Internationalisation file for the LicensedVideoSwap extension.
*
* @addtogroup Languages
*/

$messages = array();

$messages['en'] = array(
	'lvs-page-title' => 'Licensed Video Swap',
	'lvs-callout-header' => "We've found matches for videos on your wiki in Wikia Video. <br> Replacing your videos with videos from Wikia Video is a good idea because:",
	'lvs-callout-reason-licensed' => "Wikia Videos are '''licensed''' for our communities for use on your wikis",
	'lvs-callout-reason-quality' => "Wikia Videos are high '''quality'''",
	'lvs-callout-reason-collaborative' => "Wikia Videos are '''collaborative''' and can be '''used across multiple wikis'''",
	'lvs-callout-reason-more' => 'and more... we will be adding more features and ways to easily use and manage Wikia Videos. Stay tuned!',
	'lvs-instructions-header' => 'How to use this page',
	'lvs-instructions' => "Many of the videos you embed on your wikis become unavailable when they are removed or taken down for copyright violations. That's why Wikia has licensed thousands of videos for use on your wikis from several content partners. This Special page is an easy way for you to see if we have a licensed copy of the same or similar videos on your wikis. Please note that often the exact same video may have a different video thumbnail so it's best to review the videos before you make a decision. Happy swapping!",
	'lvs-button-keep' => 'Keep',
	'lvs-button-swap' => 'Swap',
	'lvs-more-suggestions' => '$1 more {{PLURAL:$1|suggestion|suggestions}}',
	'lvs-best-match-label' => 'Best Match from Wikia Video Library',
	'lvs-swap-video-success' => 'Congratulations! You have successfully swapped the existing video with the wikia video. You can check it via [[$1|Link]] [[$2|Undo]]',
	'lvs-keep-video-success' => 'You have chosen to keep your current video. The video will be removed from this list. [[$1|Undo]]',
	'lvs-restore-video-success' => 'You have restored the video to this list.',
	'lvs-error-permission' => 'you cannot swap this video.',
);

$messages['qqq'] = array(
	'lvs-page-title' => 'This is the page header/title (h1 tag) that is displayed at the top of the page.  This section is temporary and will go away after a certain number of views.',
	'lvs-callout-header' => 'This is some header text that encourages the user to replace unlicensed videos with videos licensed for use on Wikia.  This section is temporary and will go away after a certain number of views. There\'s an optional <br /> tag between the two sentences for purposes of making the header look nicer.',
	'lvs-callout-reason-licensed' => 'This is a bullet point that appears below lvs-callout-header. It explains that Wikia videos are licensed for use on Wikia. This section is temporary and will go away after a certain number of views.',
	'lvs-callout-reason-quality' => 'This is a bullet point that appears below lvs-callout-header.  This section is temporary and will go away after a certain number of views.',
	'lvs-callout-reason-collaborative' => 'This is a bullet point that appears below lvs-callout-header.  This section is temporary and will go away after a certain number of views.',
	'lvs-callout-reason-more' => 'This is a bullet point that appears below lvs-callout-header.  This section is temporary and will go away after a certain number of views.',
	'lvs-instructions-header' => 'This is the title of the section on how to use this page.',
	'lvs-instructions' => 'This is the text at the top of the Licensed Video Swap special page that explains to the user what this page is all about. The idea is that users can exchange unlicensed videos for videos licensed for use on Wikia.',
	'lvs-button-keep' => 'This is the text that appears on a button that, when clicked, will keep the non-licensed video as opposed to swapping it out for a licensed video.',
	'lvs-button-swap' => 'This is the text that appears on a button that, when clicked, will swap out a non-licensed video for a licensed video suggested from the wikia video library.',
	'lvs-more-suggestions' => 'This text will appear below a video that is a suggestion for a licensed version of a video that already exists on the wiki.  When clicked, this link will reveal more licensed possible replacements for the non-licensed video.',
	'lvs-best-match-label' => 'This text appears above the licensed video that is considered the best match for replacing a non-licensed video.',
	'lvs-swap-video-success' => 'This text appears after swapping out the video.
* $1 is a link to the file page
* $2 is a link to reverse the replacement',
	'lvs-keep-video-success' => 'This text appears after keeping the video.
* $1 is the title of the video
* $2 is a link to restore the video to the Special page again',
	'lvs-restore-video-success' => 'This text appears after restoring the video to the list.',
	'lvs-error-permission' => 'This text appears if user does not have permission to swap the video.',
);