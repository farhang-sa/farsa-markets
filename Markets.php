<?php defined( 'TPath_Root' ) 	or die( 'Access Denied : ' . basename( __FILE__ ) ) ;
defined( 'Fallon_Root' ) 		or die( 'Access Denied : ' . basename( __FILE__ ) ) ;
defined( 'TPath_AppRoot' ) 		or die( 'Access Denied : ' . basename( __FILE__ ) ) ;
defined( "FallonAppName" ) OR define( "FallonAppName" , "Markets" );
define( 'Markets_Root' , realpath( __DIR__ ) ) ;
define( 'Markets_AppDisplayName' , "Markets" ) ;

use Fallon\DataStructured_Framework_v2 As FallonFrameWork ;

// Loading AppManager Class ( It Loads 'AppManagmentStandardClass' )
Ted\Import( "Gaps" , TPath_GapsManager );
Ted\Import( 'Classes.MarketsHandler' , Markets_Root );

#[AllowDynamicProperties]
class Markets extends GapsManager {
		
	public $MarketPlaceAppId = 'MetrooKala' ;
	public $AppComp	         = "site" ;
	public $AppFunc		     = "home" ;
	public $AppTableName     = "app_markets" ;
	public $AppRequestsTable = "app_markets_requests" ;
	public $AppMatchesTable  = "app_markets_matches" ;
	public $AppCountsTable 	 = "app_markets_counts" ;
	
	public function AllowProfileCrossing(){ return true ; } 
	
	public function GetMainShopImageUrl(){
	    return "https://metrookala.ir/assets/metroo_main_shop.jpg" ; }

	public function Initialise(){

		if ( ! parent::Initialise() ) return false ;
		
		$this->translator->LoadINIDirectory( Markets_Root . TPath_DS . "lang" );

		defined( 'Markets_JSON' ) OR
			define( 'Markets_JSON' , TPath_Default_AppData . TPath_DS . "JSON" ) ;

		defined( 'Markets_Matches' ) OR
			define( 'Markets_Matches' , Markets_Root . TPath_DS . "Matches" ) ;
		
		if ( ! is_file( Markets_JSON ) ) 
			@mkdir( Markets_JSON );
		
		if ( ! is_file( Markets_Matches ) ) 
			@mkdir( Markets_Matches );
			
		if( FallonAppName !== "Markets" && 
		
		    $this->AppHistory[1] === "site" && $this->AppHistory[2] === "index" ){
		    
		    $this->AppHistory[1] = "Apps" ;
		
			$this->AppHistory[2] = FallonAppName ;
		    
		} 
		
		// Check For App Settings
		//$Sink = array() ;

		//$Error = "'Markets' Application Miss Configured" ;

		// Load Commerce
		$this->COMMERCE();

        MarketsHandler::init( $this->MarketPlaceAppId );

	}
	
	
	public function Finish(){ 

		parent::Finish();

		return true ; 

	}
	
	public function isMarketPlaceShop( $shop ){
	    return MarketsHandler::isMarketPlaceShop( $shop ); }
	
	public function isMarketMain( $shop ){
	    return MarketsHandler::isMainMarketShop( $shop ); }
	
	private $isMarketPlaceApp = null ;
	public function calcForMarketPlaceApp(){ //calcForClientsApp
		if( is_null( $this->isMarketPlaceApp ) ){
			$MpCalc = $this->getForMarketPlaceApp();
			$this->isMarketPlaceApp = $this->input->{$MpCalc} ;
			if( ! $this->isMarketPlaceApp ) 
			    $this->isMarketPlaceApp = false ;
		} return $this->isMarketPlaceApp ;  
	}

	public function getForMarketPlaceApp(){ //calcForClientsApp
		return 'forMarketPlace_' . $this->MarketPlaceAppId ; }

	public function setAsForMarketPlaceApp(){
		$this->input->{$this->getForMarketPlaceApp()} = 1; }
	
	public function FilterPrice( $item , $price ){
	    return MarketsHandler::FilterPrices( $item , $price ); }

	public function ParsNums( $str ){
		return Ted\PersianNumbers( $str ); }


	public function ParsNumsFormat( $str ){

		$deci = 0 ;

		if( is_numeric( $str ) && floor( $str ) != $str )

			$deci = 3 ;

		return Ted\PersianNumbers( number_format( $str , $deci , "." , "," ) ); 
		
	}

}

?>