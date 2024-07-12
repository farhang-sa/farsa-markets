<?php defined( "Fallon_Root" ) or die( "Access Denied" ) ;

Ted\Import( 'secrets' , TPath_AppRoot );
class_exists( 'secrets' ) or die( 'secrets class not loaded' );

class WebOneSMSService extends WebOneSMSBase {

    protected $secrets = null ;

    public function __construct(){
        if( ! $this->secrets )
            $this->secrets = new secrets();
    }

	protected function UserName(){
	    return $this->secrets->getWebOneSMSUsername() ;
	}
	
	protected function PassWord(){
	    return $this->secrets->getWebOneSMSPassWord() ;
	}
	
	protected function FromNumb(){
	    return $this->secrets->getWebOneSMSFromNumb() ;
	}

} ?>