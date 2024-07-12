<?php defined( 'Fallon_Root' ) or die( 'Access Denied : ' . basename( __FILE__ ) ) ;

if( class_exists( "ClientsManager" ) ) return new ClientsManager() ;
class ClientsManager extends AppManagmentStandardClass {

	private static $matchTimer = 0 ;
	private static $tableName = "commerce" ;

	protected function AppName(){ return "Clients" ; }

	public function GetStandardMatchTime(){ return self::$matchTimer ; }
	protected function TableName(){ return self::$tableName ; }

	public function GetRequiredComponents(){
		return null ;//[ "com_commerce" => "commerce" ]; 
	}

	public function GetUpdates( $lastUpdateStamp ){

		$System = $this->SubApp( "SYSTEM" );

		$settings = $System->PullItems( "system" , "Clients" , "Settings" );
		
        if( is_array( $settings ) && (int) $settings["edit"] >= $lastUpdateStamp )
        
            $settings = $System->CleanData( $settings );
            
        else $settings = false ;
            
		return $settings ;

	}
	
	public function GetMainSettingsDefinitions(){
	    
	    $COM = $this->SubApp( "COMMERCE" );

	    $settings = array(
	    	// Custom Settings
	        'markets_clients_under_repairs'         => [ 'checkbox' , 'Under Repair Mode' ] ,
	        'markets_clients_apply_real_mode'       => [ 'checkbox' , 'Apply Real World Mode' ] ,
	        'markets_clients_web_mode_active'       => [ 'checkbox' , 'Force Web Mode is Active' ] ,
	        'markets_clients_alarm_messages_title'  => 'Write Pinned Messages ( Title|Message|link )' ,
	        'markets_clients_alarm_messages'        => [ "textarea" , null , 'Pinned Messages'] ,
	        'markets_clients_mainmenu_ui_title'     => 'Write Down MainMenu Ui Definitions' ,
	        'markets_clients_main_page_definition'  => [ 'textarea' , null , 'Use Menu Ui Definition Creator And Past Result In here!' , 'ltr'] ,
	    );
	    
	    $ac = $COM->Settings( "active_locations" );
	    $ac = Ted\json_str_to_array( $ac ) ;
	    if( is_array( $ac ) ) foreach( $ac as $prov => $pd ){
	        
	        if( ! isset( $pd["cities"] ) )
	            continue ;
	        $pd = $pd["cities"] ;
	        if( is_array( $pd ) ) foreach( $pd as $city => $intel ){
	            
	            $city = '_' . $prov . '_' . $city;
	            $settings[ 'Msg' . $city  ] = "<h3>{$intel['name']}</h3>" ;
	            $settings[ 'markets_clients_under_repairs' . $city ]  = [ 'checkbox' , 'Under Repair Mode' ] ;
	            $settings[ 'markets_clients_web_mode_active' . $city ] = [ 'checkbox' , 'Force Web Mode is Active' ] ;
	            $settings[ "markets_clients_alarm_messages_title" . $city ]  = 'Write Pinned Messages ( Title|Message|link )' ;
	            $settings[ "markets_clients_alarm_messages" . $city ]        = [ 'textarea' , null , 'Pinned Messages'] ;
	            $settings[ "markets_clients_mainmenu_ui_title" . $city ]     = 'Write Down MainMenu Ui Definitions' ;
	            $settings[ "markets_clients_main_page_definition" . $city ]  = [ 'textarea' , null , 'Use Menu Ui Definition Creator And Past Result In here!' , 'ltr'] ;
	            
	        }
	        
	    }
	    
	    return $settings;
	    
	}
	
	public function GetAndroidSettingsDefinitions(){

	    return array(
	        "markets_android_settings_init"     => "Just For Initing Android Settings!" /*,
        	"kebtedaei_android_attach_intro_pdf_file"  => [ "checkbox" , "Attach Intro File To PDFs" ] ,
        	"kebtedaei_android_suggest_sida_assistant" => [ "checkbox" , "Suggest Sida Assistant" ] ,
        	"kebtedaei_android_show_links"             => [ "checkbox" , "Show Links In Posts" ] ,
        	"kebtedaei_android_hide_links_filters"     => [ "text"   , ""  , "Hide Links Filter( de: - )" ] ,
        	"kebtedaei_android_internal_browser"   	   => [ "checkbox" , "Internal Browser Enabled" ] */
	    );
	    
	}

} return new ClientsManager() ;