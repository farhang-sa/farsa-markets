<?php defined( "Fallon_Root" ) or die( "Access Denied" ) ;

Ted\Import( 'secrets' , TPath_AppRoot );
class_exists( 'secrets' ) or die( 'secrets class not loaded' );

class ZarinPalService extends ZarinPalBase { 

    protected $secrets = null ;

    public function __construct(){
        if( ! $this->secrets )
            $this->secrets = new secrets();
    }
	
	protected function getMid(){
	    return $this->secrets->getZarinPalId() ; }
	
} ?>