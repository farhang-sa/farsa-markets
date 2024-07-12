<?php defined( "Fallon_Root" ) or die( "Access Denied" ) ;

Ted\Import( 'secrets' , TPath_AppRoot );
class_exists( 'secrets' ) or die( 'secrets class not loaded' );

class MeliPayamakService extends MeliPayamakBase {

    protected $secrets = null ;

    public function __construct(){
        if( ! $this->secrets )
            $this->secrets = new secrets();
    }
    
    protected function UserName(){
        return $this->secrets->getMelliPayamakUsername() ;
    }
    
    protected function PassWord(){
        return $this->secrets->getMelliPayamakPassword() ;
    }
    
    protected function PatternId( $PatternId ){
        $plist = $this->secrets->getMelliPayamakPatterns() ;
        $PatternId = isset( $plist[ $PatternId ] ) ? $plist[ $PatternId ] : $PatternId ;
        return $PatternId != null ? 
            $PatternId : $this->secrets->getMelliPayamakDefaultPattern() ;
    }

} ?>