<?php
abstract class SDSFormMapping {

	abstract public function newPandoraSDSObjectFromFormData( $formData );

	abstract public function newFormDataFromPandoraSDSObject( PandoraSDSObject $object );
}
