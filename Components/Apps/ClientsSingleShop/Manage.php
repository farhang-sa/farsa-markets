<?php defined( 'Fallon_Root' ) or die( 'Access Denied : ' . basename( __FILE__ ) ) ;

if( class_exists( "ClientsSingleShopManager" ) ) return new ClientsSingleShopManager() ;
class ClientsSingleShopManager extends AppManagmentStandardClass {

	private static $matchTimer = 0 ;
	private static $tableName = "commerce" ;
	private $mInput ;
	
	protected function Markets(){
	    return $this->MainApp( 'Markets' ); }
	
	protected function input(){
	    return $this->Markets()->input ; }
	
	protected function AppName(){
	    $appName = defined( 'shid' ) ? shid : $this->input()->shid ;
	    return $appName ? $appName : 'ClientsSingleShops' ; 
	} 

	public function GetStandardMatchTime(){ return self::$matchTimer ; }
	protected function TableName(){ return self::$tableName ; }

	public function GetRequiredComponents(){
		return null ;//[ "com_commerce" => "commerce" ]; 
	}

	public function GetUpdates( $lastUpdateStamp ){

		$System = $this->SubApp( "SYSTEM" );

		$settings = $System->PullItems( "system" , $this->AppName() , "Settings" );
		
        if( is_array( $settings ) && (int) $settings["edit"] >= $lastUpdateStamp )
        
            $settings = $System->CleanData( $settings );
            
        else $settings = false ;
            
		return $settings ;

	}
	
	public function GetMainSettingsDefinitions(){
	    
	    $com = $this->SubApp( "COMMERCE" );
	    $shid = $this->AppName();
	    
	    $shop = $com->PullShop( $shid );
	    
	    if( $shid === 'ClientsSingleShops' || ! $shop ){
	        
	        // Find Shop Form
	        print '<form method=POST>';
	        print '<div class="col-xs-12 padding5 text-center"><h4 style="margin:5px;"> Search Application By Id </h4></div>';
	        print '<input type="text" name="shid" class="form-control input input-lg text-center" placeholder="Single Shop Id">' ;
	        print '</from>' ;
	    
	        return array();

	    } else { 
	        
	        print '<input type="hidden" name="shid" value="' . $shid . '">' ;
	        
	        return array(
    	    	// Custom Settings
    	        'markets_clients_single_app_title'      => [ 'text' , null , 'App Title' ] ,
    	        'markets_clients_single_app_icon'       => [ 'text' , null , 'App ICON URL' ] ,
    	        'markets_clients_under_repairs'         => [ 'checkbox' , 'Under Repair Mode' ] ,
    	        'markets_clients_apply_real_mode'       => [ 'checkbox' , 'Apply Real World Mode' ] ,
    	        'markets_clients_web_mode_active'       => [ 'checkbox' , 'Force Web Mode is Active' ] ,
    	        'markets_clients_alarm_messages_title'  => 'Write Pinned Messages ( Title|Message|link )' ,
    	        'markets_clients_alarm_messages'        => [ "textarea" , null , 'Pinned Messages'] 
    	    );
	    }
	    
	}
	
	public function GetAndroidSettingsDefinitions(){

	    return array(
	        "markets_android_settings_init"     => "Just For Initing Android Settings" /*,
        	"kebtedaei_android_attach_intro_pdf_file"  => [ "checkbox" , "Attach Intro File To PDFs" ] ,
        	"kebtedaei_android_suggest_sida_assistant" => [ "checkbox" , "Suggest Sida Assistant" ] ,
        	"kebtedaei_android_show_links"             => [ "checkbox" , "Show Links In Posts" ] ,
        	"kebtedaei_android_hide_links_filters"     => [ "text"   , ""  , "Hide Links Filter( de: - )" ] ,
        	"kebtedaei_android_internal_browser"   	   => [ "checkbox" , "Internal Browser Enabled" ] */
	    );
	    
	}

} return new ClientsSingleShopManager() ;