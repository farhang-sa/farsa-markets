<?php defined( "Fallon_Root" ) or die( "Access Denied" ) ;

Ted\Import( 'secrets' , TPath_AppRoot );
class_exists( 'secrets' ) or die( 'secrets class not loaded' );

class FardaPayamakService extends FardaPayamakBase {

    protected $secrets = null ;

    public function __construct(){
        if( ! $this->secrets )
            $this->secrets = new secrets();
    }

    protected function UserName(){
        return $this->secrets->getFardaPayamakUsername() ;
    }
    
	protected function PassWord(){
        return $this->secrets->getFardaPayamakPassword() ;
	}
	
	protected function FromNumb(){
        return $this->secrets->getFardaPayamakFromNumb() ;
	}

} ?>