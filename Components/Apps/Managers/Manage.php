<?php defined( 'Fallon_Root' ) or die( 'Access Denied : ' . basename( __FILE__ ) ) ;

if( class_exists( "ManagersManager" ) ) return new ManagersManager() ;
class ManagersManager extends AppManagmentStandardClass {

	private static $matchTimer = 0 ;
	private static $tableName = "commerce" ;

	protected function AppName(){ return "Managers" ; }

	public function GetStandardMatchTime(){ return self::$matchTimer ; }
	protected function TableName(){ return self::$tableName ; }

	public function GetRequiredComponents(){
		return null ;//[ "com_commerce" => "commerce" ]; 
	}

	public function GetUpdates( $lastUpdateStamp ){

		$System = $this->SubApp( "SYSTEM" );

		$settings = $System->PullItems( "system" , "Managers" , "Settings" );
		
        if( is_array( $settings ) && (int) $settings["edit"] >= $lastUpdateStamp )
        
            $settings = $System->CleanData( $settings );
            
        else $settings = false ;
            
		return $settings ;

	}
	
	
	public function GetMainSettingsDefinitions(){

	    return array(
	    	// Custom Settings
	    	"message1" => "<div class='text-center'>Messages For Managers</div>",
	        "markets_managers_alarm_messages" 	=> [ "textarea" , null , "Pinned Messages( Title|Message|link )"] ,
	    	"message2" => "<div class='text-center'>Messages For Operators</div>",
	        "markets_operators_alarm_messages" 	=> [ "textarea" , null , "Pinned Messages( Title|Message|link )"] ,
	    	"message3" => "<div class='text-center'>Delivery Cost Calculator Script</div>",
	        "markets_maxim_delivery_formula" => [ "textarea" , null , "Delivery Cost Calculator Script" , 'ltr'] ,
	    	"message4" => "<div class='text-center'>Delivery Minimum Fee For Each Order</div>",
	        "markets_delivery_minimum_fee" => [ "number" , 4500 , "Delivery Minimum Fee For Each Order" ] ,
	    	"message5" => "<div class='text-center'>Delivery Stop Fee For Each Order</div>",
	        "markets_delivery_stop_fee" => [ "number" , 4500 , "Delivery Stop Fee For Each Order" ] 
	    );
	    
	}
	
	public function GetAndroidSettingsDefinitions(){

	    return array(
	        "markets_android_settings_init"     => "Just For Initing Android Settings!" 
	    );
	    
	}

} return new ManagersManager() ;