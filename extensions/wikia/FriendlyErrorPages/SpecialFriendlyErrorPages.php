<?php
class SpecialFriendlyErrorPages extends UnlistedSpecialPage {
        function __construct() {
                parent::__construct( 'FriendlyErrorPages', 'friendlyerrorpages', true );
        }
 
        function execute( $par ) {
                global $wgUser;
                if( !$wgUser->isAllowed( 'friendlyerrorpages' ) ) {
                        throw new PermissionsError( 'friendlyerrorpages' );
                }
                $request = $this->getRequest();
                $output = $this->getOutput();
                $this->setHeaders();
                
                $param = $request->getText( 'param' );
 
                # Trigger Error
                if ( isset( $par ) ) {
                        
                        # if a status code is specified in URL - trigger desired HTTP status
                        FriendlyErrorPages::triggerStatus( $par );

                } else {
                        
                        # if URL does not include any code - display a list of errors
                        $aHttpStatus = FriendlyErrorPages::getStatusArray();
                        global $wgOut;
                        $wgOut->setPageTitle("Friendly Error Pages");
                        $wgOut->addWikiText( wfMessage( 'friendlyerrorpages-specialpage-click' )->text() );
                        foreach ( $aHttpStatus as $key => $message ) {
                                $wgOut->addWikiText("* {{#NewWindowLink:{$this->getTitle()->getPrefixedText()}/$key|$message}}");
                        }
                }
        }
}