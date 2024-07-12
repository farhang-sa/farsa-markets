<?php defined( 'Fallon_Root' ) or die( 'Access Denied : ' . basename( __FILE__ ) ) ;

/*if ( ! $this->User( "login" ) ){

	$this->Response( [ "message"=>$this->MsgNotLogedinAlert(),"login"=>false ] );

	return false ;

}*/ $updates = array() ;
if( ! isset( $lastUpdate ) ){ // If Direct Update Requested
	$cTime = @time() ;
	$lastUpdate = ( int ) $this->input->last_update ;
	$lastUpdate = ( $lastUpdate > 0 ) ? $lastUpdate : $this->session->last_update ;
	$lastUpdate = ( $lastUpdate > 0 ) ? $lastUpdate : $cTime ;
	// Check if is First Time Init
	if( $this->input->app_init ) $lastUpdate = 1 ;
	$this->session->last_update = $cTime ;
} 

if( $this->User( "login" ) ) {
    // Commerce Access List For This User
    //$su = ( int ) $this->User()->CustomIntels( 'COMMERCE' , 'Access' , 'edit' );
    //if( $su >= $lastUpdate )
    	$updates["access"] = $this->User()->CustomIntels( 'COMMERCE' , 'Access' );
    
    // Custom Commerce Settings For This User
    $su = ( int ) $this->User()->CustomSettings( 'COMMERCE' , 'General' , 'edit' );
    if( $su >= $lastUpdate )
    	$updates["user_settings"] = $this->User()->CustomSettings( 'COMMERCE' , 'General' );
}

// Malls Subjects List Update Check
$su = ( int ) $this->SYSTEM()->SyncStamps( "COMMERCE-Mall-Subjects-Update" );
$su = $su <= 0 ? @time() : $su ;
if ( $su >= $lastUpdate ) {
	$subjects = $this->COMMERCE()->LoadMallSubjects();
	if ( is_array( $subjects ) && ! empty( $subjects ) ) 
		$updates["mall_subjects"] = $subjects ;
} 

// Shops Subjects List Update Check
$su = ( int ) $this->SYSTEM()->SyncStamps( "COMMERCE-Shop-Subjects-Update" );
$su = $su <= 0 ? @time() : $su ;
if ( $su >= $lastUpdate ) {
	$subjects = $this->COMMERCE()->LoadShopSubjects();
	if ( is_array( $subjects ) && ! empty( $subjects ) ) 
		$updates["shop_subjects"] = $subjects ;
} 


// Commerce Settings Update For All
$Settings = $this->SilentCall( "System" , "admins" ,
    "Settings" , [ "settings_name" => "Commerce" ] );
$updates["settings"] = $Settings ;

// Shops( OR Their Childeren Have Been Updated )
$su = ( int ) $this->SYSTEM()->SyncStamps( "COMMERCE-Shops-Update" );
$su = $su <= 0 ? @time() : $su ;
if( $su >= $lastUpdate ){

	// Get New Shops ||| ->  Add This(`discussion`='COMMERCE' AND ) For Only COMMERCE Shops
	/*
	Sync With :
		All Templates ( Shops - Malls );
		All Open-Free-NonPrivate Subjects ( Malls - Shops )
		All My Shops From My Malls
		All Shops */
		
	$su = $this->User( "login" ) ? $this->User( "code" ) : "fallon-no-user-555";

	$malls = "`content` LIKE '%is_template%'";
	//$malls .= " OR `content` LIKE '%is_cloning_template%'" ;
	$malls = "( `category`='Malls' AND ( `writer`='{$su}' OR {$malls} ) )" ;

	$mMalls = [ $malls , "`edit`>=" . $lastUpdate ] ;
	$mMalls = $this->COMMERCE()->LoadDataAsArray( 
		'commerce' , [ "*" ] , $mMalls , null , null , null );
	$mMalls = $this->COMMERCE()->PrepItemsArray( $mMalls ) ;

	if( ! empty( $mMalls ) ){

		$updates[ "malls" ] = $mMalls ;

		unset( $mMalls );

	} 

	$shops = "`discussion`='COMMERCE' OR `content` LIKE '%is_template%'" ;
	//$shops .= " OR `content` LIKE '%is_cloning_template%'";
	$shops = "( `category`='Shops' AND ( `writer`='{$su}' OR {$shops} ) )" ;

	$mShops = [ $shops , "`edit`>=" . $lastUpdate ] ;
	$mShops = $this->COMMERCE()->LoadDataAsArray( 
		'commerce' , [ "code" ] , $mShops , null , null , null );
	$mShops = $this->COMMERCE()->PrepItemsArray( $mShops ) ;

	if( ! empty( $mShops ) ){

		foreach ($mShops as $key => $value) {
			
			$value = $this->COMMERCE()->LoadShop( $key );

			if( empty( $value ) || ! is_array( $value ) ){

				unset( $mShops[ $key ] );

				continue ;

			} $mShops[ $key ] = $value ;
		} $updates[ "shops" ] = $mShops ;

		unset( $mShops );

	} 

	$secs = $prod = "`discussion` IN( SELECT `code` FROM `commerce` WHERE {$shops} )";

	$secs = [ "`edit`>={$lastUpdate} AND {$secs} AND `content` LIKE '%input_type_section%'" ] ;
	$secs = $this->COMMERCE()->LoadDataAsArray( 'commerce' , 
		[ "*" ] , $secs , null , null , null );
	$secs = $this->COMMERCE()->PrepItemsArray( $secs ) ;

	if( ! empty( $secs ) ){

		$updates[ "secs" ] = $secs ;

		unset( $secs );

	} 

	if( $lastUpdate === 1 ) $prod = str_ireplace( "( `category`='Shops' AND (" , 
		"( `category`='Shops' AND `content` NOT LIKE '%real_time_sync_view%' AND (", $prod );
	$prod = ["`edit`>={$lastUpdate} AND {$prod} AND `content` LIKE '%input_type_product%'"];
	$prod = $this->COMMERCE()->LoadDataAsArray( 
		'commerce' , [ "*" ] , $prod , null , null , null );
	
	$prod = $this->COMMERCE()->PrepItemsArray( $prod ) ;

	if( ! empty( $prod ) ){

		$updates[ "products" ] = $prod ;

		unset( $prod );

	}

	// Temp Products
	$prod = ["`edit`>={$lastUpdate}"];
	$prod = $this->COMMERCE()->LoadDataAsArray( 
		'commerce_temps' , [ "*" ] , $prod , null , null , null );
	
	$prod = $this->COMMERCE()->PrepItemsArray( $prod ) ;

	if( ! empty( $prod ) ){

		if( isset( $updates[ "products" ] ) && is_array( $updates[ "products" ] ) )
			$updates[ "products" ] = array_merge( $updates[ "products" ] , $prod );
		else $updates[ "products" ] = $prod ;

		unset( $prod );

	}

} if( $lastUpdate == 1 ) { // If Direct Update Requested

	$updates[ "last_update" ] = $this->session->last_update ;

	$updates[ "message" ] = "Update Compeleted" ;

} $this->Response( $updates );

return $updates ; ?>