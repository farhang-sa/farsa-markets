<?php defined( 'Fallon_Root' ) or die( 'Access Denied : ' . basename( __FILE__ ) ) ;

if ( ! $this->User( "login" ) ) {

	$this->Response(["message" => $this->MsgNotLogedinAlert() , true ]);

	return false ;

} $target = isset( $target ) ? $target : $this->input->facture_target ;

// Set Target System To Commerce If Not Sent
$target = $target ? $target : "commerce" ;

// [ "item_code" => (int) how_many _ ( float ) total_weight ]
$items  = isset( $items ) && 
	is_array( $items ) ? $items : $this->input->facture_items ;

// Update Mode !
$facture = isset( $facture ) ? $facture :
	$this->input->search([ "fid" , "facture" , "facture-id" , "facture_id" ]);

if( ! is_array( $facture ) )
	$facture = $this->COMMERCE()->PullFacture( $facture );

if( is_string( $items ) ){ // Single Item
	
	$items = $this->COMMERCE()->PullElementExt( $target , $items );

	if( is_array( $items ) && isset( $items["code"] ) )

		$items = array( $items["code"] => $items );

} if ( ! is_array( $items ) || empty( $items ) ){

	$items = $this->Message( 'Item Not Founded' );

	$this->Response([ "message" => $items , "failed" => true ]);

	return false ;

} $coupon = isset( $coupon ) ? $coupon : $this->input->facture_coupon ;

if( is_string( $coupon ) )
	$coupon = $this->COMMERCE()->PullCoupon( $coupon );

if( is_array( $facture ) && isset( $facture[ 'coupon' ] ) )
	$coupon = $this->COMMERCE()->PullCoupon( $facture[ 'coupon' ] );

$check = $this->SilentCall( "commerce" , "user" , "factures" , "details" , 
	[ "target" => $target , "items" => $items , "coupon" => $coupon ] );

if ( ! is_array( $check ) ){

	$check = $this->Message( $check );

	$this->Response( [ "message" => $check , "failed" => true ] );

	return false ;

} $items = $check[0]; // Item's List

$price   = $check[1]; // Total Price 

$vExists = $check[5]; // Virtual Items Count
	
$factureCode = $this->Data()->CreateUserCode( "fac" );

$onsite = isset( $onsite ) ? $onsite : $this->input->facture_onsite ;

if( isset( $onsite ) && $vExists >= 1 ){ 
	// Not Good : No On-Site For Virtual Items !

	$onsite = "On-Site Payment is Not Available For Virtual Items" ;

	$onsite = $this->Message( $onsite );

	$this->Response( [ "message" => $onsite , "failed" => true ] );

	return false ;

} if( ! isset( $onsite ) ) { // Purchase By Credit !

	$check = $this->SilentCall( "commerce" ,"user" , "factures" , 
		"check" , [ "coupon" => $coupon , "Details" => $check ] );

	if ( $check === false ){

		$this->Response( [ "message" => $this->Message() , "failed" => true ] );

		return false ;

	} else if ( is_array( $check ) ){ // Charge Required

		$this->Response( [ "message" => $this->Message() , 
			"charge" => $check[1] , "failed" => true ] );

		return false ;	

	} $price = $check ; // Final Purchase Price

	// Decrease User's Credit With Item Price
	$this->AccessCheck( 'DecreaseUserCredit' , true );
	$Done = $this->SilentCall( "user" , "Credit" , "Decrease" , 
		["Amount" => $price , "Tracker" => $factureCode ,
			"Title" => "Purchasing Some Item(s)"]);
	$this->AccessCheck( 'DecreaseUserCredit' , false );

} else {

	// Check If OnSite Is Active ?!

	// OnSite Facture !
	$Done = true ; 

} if ( $Done ){ // Grant User Access To the Data "Full" or "End-Access-TimeStamp"

	$intel = array();

	$discs = array();

	if( $this->calcForMarketPlaceApp() )
		$discs[$this->input->clients_shid] = 1 ;

	$allVirtual = true ;

	foreach ($items as $key => $item ) {

		$key = isset( $item[ "purchase_count" ] ) ? $item[ "purchase_count" ] : 1 ;

		$key = array( "count" => $key );

		if( isset( $item[ "title" ] ) )
			$key[ "title" ] = $item[ "title" ];
		else $key[ "title" ] = "Item " . $item["code"];

		$key[ "actual" ] = $item[ "actual_price" ];
		$key[ "real" ] 	 = $item[ "real_price" ];
		if( isset( $item[ "coupon_price" ] ) 
			&& $item[ "coupon_price" ] >= 1 ){ // used coupons !
			$key[ "coupon" ] = $item[ "coupon_price" ];
			// set coupon usage !
			if( $item[ "discussion" ] != "_LastMinuteChanges_" ){
				// Count Coupon Usage
				$this->SilentCall( 'commerce' , 'Actions' , 'counter' ,
					[ 'item' => $coupon["category"] , 'action' => "purchases" ] );
				// Count Coupon Usage For This Item
				$this->SilentCall( 'commerce' , 'Actions' , 'counter' ,
					[ 'item' => $coupon["category"] , 'action' => $item["code"] ] );
				// Count Item Purchases
				$this->SilentCall( 'commerce' , 'Actions' , 'counter' ,
					[ 'item' => $item["code"] , 'action' => "purchases" ] );
			} // Farhang
		} else $key[ "coupon" ] = 0;
		$key[ "pay" ] 	 = $item[ "pay_price" ];

		$intel[ $item["code"] ] = $key ;

		if( ! $this->calcForMarketPlaceApp() )
			$discs[ $item[ "discussion" ] ] = 1 ;

		// If Item IS Virtual => set AllVirtual = false 
		if( $item[ "discussion" ] != "_LastMinuteChanges_" 

			&& ! ( array_key_exists( "iVirtual" , $item ) && $item[ "iVirtual" ] ) )

			$allVirtual = false ;

	} $intel = array( "facture" => $intel , "price" => $price );

	$intel[ "discussions" ] = $discs;

	if( is_array( $facture ) ) {  // Edit Mode :)

		// No Intel Required !
		// Just Put New Items And Price !!
		$factureCode = $facture[ 'code' ];

		if( is_array( $coupon ) && isset( $coupon["name"] ) )
			$intel[ 'coupon' ] = $coupon[ 'name' ] ;
		else if( isset( $facture[ 'coupon' ] ) )
			$intel[ 'coupon' ] = $facture['coupon'] ;

		$st = isset( $newStatus ) ? $newStatus : 'Saved' ;

		$this->COMMERCE()->UpdateFacture( $factureCode , $intel , $st );

        //file_put_contents( TPath_Root . TPath_DS . "check.txt" , $this->JSON_array_to_str( $intel ) );

		$st = $this->Message( "Facture Update Successful" );

		$intel[ "reference" ] = $facture[ "reference" ];

	} else {

		if( isset( $Address ) && is_array( $Address ) )

			$intel[ "address" ] = $Address ;

		$intel[ "reference" ] = @time();

		$intel[ "target" ] = $target ;

		if( isset( $onsite ) && $onsite )

			$intel[ "onsite" ] = 1 ;

		$us = $this->User( "code" ) ;

		$st = ( $allVirtual ) ? "Finished" : "Saved" ;

		if( isset( $extra ) && is_array( $extra ) ) 
			foreach ($extra as $key => $value)
			    if( is_string( $key ) && ! empty( $value ) )
				    $intel[ $key ] = $value ;

		$this->COMMERCE()->SaveFacture( $us , $factureCode , $intel , $st );

		$st = $this->Message( "Facture Execute Successful" );

	} // Execute 'Target' System's Grant Access OR Sth :: If Exists !
	$this->SilentCall( $target , 
		"user" , "purchase" , "analyze" , [ "items" => $items ] );

	$this->Response([ "message" => $st , "facture" => $factureCode , 
		"execute" => $intel[ "reference" ] , "price" => $price ,"success" => true ]);

	return $factureCode ;

} $intel = $this->Message( "Facture Execute Faild" );

$this->Response( [ "message" => $intel , "failed" => true ] );

return false ; ?>