<?php defined( 'Fallon_Root' ) or die( 'Access Denied : ' . basename( __FILE__ ) ) ;

Ted\Import( 'SiteInterface' , GapsManager_Root );

class Markets_Site_Interface extends Gaps_Site_Interface {

    public function Respond( $directRespond = true ){ 
        
        // ClientsSingles
        if( defined( 'Markets_Web_api' ) && defined( 'shid' ) )
            $this->setController( [ "singles.php" ] );
        
        // Clients - Managers
        else if( defined( 'Markets_Web_api' ) )
            $this->setController( [ "clients.php" ] );
            
        parent::Respond( $directRespond);
        
	}

} ?>