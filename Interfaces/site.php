<?php defined( 'Fallon_Root' ) or die( 'Access Denied : ' . basename( __FILE__ ) ) ;

Ted\Import( 'SiteInterface' , GapsManager_Root );

class Markets_Site_Interface extends Gaps_Site_Interface {

    public function Respond( $directRespond = true ){ 
        
        if( defined( 'Markets_Web_api' ) && defined( 'shid' ) )
            $this->SiteLoadersList = [ "singles.php" ];
        
        else if( defined( 'Markets_Web_api' ) )
            $this->SiteLoadersList = [ "web.php" ];
            
        parent::Respond( $directRespond);
        
	}

} ?>