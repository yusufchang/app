<?php
/**
 * Internationalisation file for Special:BatchMove extension.
 *
 * @addtogroup Extensions
 */

$messages = array();

$messages['en'] = array(
	'batchmove' => 'Batch Move Pages',
	'batchmove-header' => 'Move from [[$1]]:* to [[$2]]:*',
	'batchmove-success' => "Moved '''[[$1]]''' to '''[[$2]]'''",
	'batchmove-failed' => 'Failed to move [[$1]] to [[$2]], This page most likely needs to be merged manually',
	'batchmove-marked' => 'The page [[$1]] already exists, marked for manual merge.',
	'batchmove-skip' => 'Skipping page [[$1]].',
	'batchmove-confirm-msg' => 'Are you sure you want to move "$1":* to "$2":*?

This can possible be a large operation and will be difficult to reverse.',
	'batchmove-confirm' => 'Confirm',
	'batchmove-title' => 'Batch Move Pages',
	'batchmove-description' => 'This allows moving all pages with a specific prefix to a different prefix. Doing this manually is a herculean task, so this facility has provided.',
	'batchmove-from' => 'From',
	'batchmove-to' => 'To',
	'batchmove-reason' => 'Reason',
	'batchmove-preview' => 'Preview',
	'batchmove-preview-header' => '<i>This is only a preview, no action has been taken yet.</i>',
	'batchmove-complete' => "Batch move complete''",
);

$messages['de'] = array(
	'batchmove' => 'Verschiebung mehrerer Seiten',
	'batchmove-header' => 'Von [[$1]]:* zu [[$2]]:* verschieben',
	'batchmove-success' => "'''[[$1]]''' zu '''[[$2]]''' verschoben",
	'batchmove-failed' => '[[$1]] konnte nicht zu [[$2]] verschoben werden. Diese Seite muss manuell verschoben werden.',
	'batchmove-marked' => 'Die Seite [[$1]] existiert bereits, und wurde zum manuellen Verschieben vorgemerkt.',
	'batchmove-skip' => 'Überspringe Seite [[$1]].',
	'batchmove-confirm-msg' => 'Bist du sicher, dass du "$1":* nach "$2":* verschieben willst?

Dies könnte unter Umständen eine ganze Zeit lang dauern und das nur schwer rückgängig zu machen sein.',
	'batchmove-confirm' => 'Bestätigen',
	'batchmove-title' => 'Mehrere Seiten verschieben',
	'batchmove-description' => 'Dies erlaubt es dir, alle Seiten unter einem spezifischen Präfix zu einem neuen Präfix zu verschieben.',
	'batchmove-from' => 'von',
	'batchmove-to' => 'zu',
	'batchmove-reason' => 'Grund',
	'batchmove-preview' => 'Vorschau',
	'batchmove-preview-header' => '<i>Dies ist nur eine Vorschau, es wurde noch nichts geändert.</i>',
	'batchmove-complete' => "Verschiebung abgeschlossen''",
);

$messages['fr'] = array(
	'batchmove' => 'Renommage de pages en masse',
	'batchmove-header' => 'Renommer « [[$1]]:* » en « [[$2]]:* »',
	'batchmove-success' => "« '''[[$1]]''' » renommée en « '''[[$2]]''' »",
	'batchmove-failed' => 'Le renommage de « [[$1]] » en « [[$2]] » a échoué, cette page devra sûrement être fusionnée manuellement',
	'batchmove-marked' => 'La page « [[$1]] » existe déjà, marquée pour fusion manuelle.',
	'batchmove-skip' => 'La page « [[$1]] » a été ignorée.',
	'batchmove-confirm-msg' => 'Êtes-vous sûr(e) de vouloir renommer « $1:* » en « $2 :* » ?

Ce peut être ne grosse opération pour laquelle il sera difficile de revenir en arrière.',
	'batchmove-confirm' => 'Confirmer',
	'batchmove-title' => 'Renommage de pages en masse',
	'batchmove-description' => 'Cela permet de renommer tous les pages commençant par un préfixe particulier avec un autre préfixe. Effectuer cela à la main est une tâche herculéenne, c’est pourquoi cet outil a été élaboré.',
	'batchmove-from' => 'De',
	'batchmove-to' => 'En',
	'batchmove-reason' => 'Raison',
	'batchmove-preview' => 'Aperçu',
	'batchmove-preview-header' => '<i>Ce n’est qu’un aperçu, aucune action n’a encore été effectuée.</i>',
	'batchmove-complete' => 'Renommage en masse terminé',
);
