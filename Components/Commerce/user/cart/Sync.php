<?php defined( 'Fallon_Root' ) or die( 'Access Denied : ' . basename( __FILE__ ) ) ;

$CartType = isset( $CartType ) ? $CartType : $this->input->CartName ;
$CartType = $CartType ? $CartType : $this->COMMERCE()->CartTypeDefault ;

$items = isset( $items ) && is_array( $items ) ? $items : $this->input->items ;

if( is_string( $items ) ){

	$items = $this->COMMERCE()->PullElement( $items );

	// Maybe It's a JSON String !
	if( ! $items ) $items = Ted\json_str_to_array( $items );

} $cart = $this->User()->Carts( $CartType , $this->User( "code" ) );

$changes = array();

// Check Cart : Items Ranout ? Finished?
if( is_array( $cart ) ) foreach ($cart as $key => $value) {

	// Pull Item 
	$item = $this->COMMERCE()->PullElement( $key ); 

	if( ! is_array( $item ) ){ // Deleted ?

		$cart[ $key ] = false ;

		$changes[ $key ] = 0 ;

		continue;

	} if( isset( $item[ 'iVirtual'] ) )

		continue ; // Virtual Item!

	// Check If Exists in Storage !
	if( ! isset( $item[ 'uCount' ] ) ){ // No Items

		$changes[ $key ] = 0 ;

		$cart[ $key ] = false ;

		continue ;

	} if( $this->calcForMarketPlaceApp() ){

		$ic = isset( $item[ 'iCount' ] ) ? $item[ 'iCount' ] : 1 ;
		$ic = $ic > 0 ? $ic : 1 ;

		$uc = $item[ 'uCount' ];
		$uc = $uc > 0 ? $uc : 0 ;

		// New Count !
		$item[ 'uCount' ] = $ic * $uc ;

	} if( $item[ 'uCount' ] <= 0 ){ // No Items

		$changes[ $key ] = 0 ;

		$cart[ $key ] = false ;

	} else if( $item[ 'uCount' ] < $value ){ // Not Enough Items

		$changes[ $key ] = $item[ 'uCount' ] ;

		$cart[ $key ] = $item[ 'uCount' ] ;

	} // else : No Change!

} if( ! empty( $changes) ) // Save Cart!
	$this->User()->setCarts( $CartType , $cart , $this->User( "code" ) );

if( ! is_array( $items ) || empty( $items ) ){ // No New Items To Add

	$items = $this->Message( "Items Not Found" );

	$this->Response( [ 'message' => $items , 
		"cart" => $cart , 'changes' => $changes , "failed" => 1 ] );

	return $cart ;

} // Go Action
$itemsObjects = array();

foreach ($items as $item => $count ) {

	if( $count == 0 ) 
		continue;

	$itemObj = $this->COMMERCE()->PullElement( $item );

	if( ! $itemObj ){

		$item = $this->Message( "Item Not Found : " . $item );

		unset( $items[$item] );
		
		continue ;

	} if( isset( $itemObj[ 'iVirtual'] ) ) { // Nothing

		// No Checking :)

	} else if( ! isset( $itemObj[ 'uCount' ] ) ) { // Remove

		unset( $items[$item] );
		
		continue ;

	} else if( $itemObj[ 'uCount' ] < $count ) // Change!

		$count = $itemObj[ 'uCount' ];

	// else : continue

	$itemObj[ "purchase_count" ] = $count ;

	$itemsObjects[ $item ] = $itemObj ;

} // Add New Items
$count = $this->User()->setCarts( 
	$CartType , $items , $this->User( "code" ) );

$this->User()->LoadCarts( $this->User( "code" ) );
$cart = $this->User()->Carts( $CartType );

if( $cart ){

	$cart = is_array( $cart ) ? $cart : array();

	$cart = ! empty( $cart ) ? $cart : false ;
	
	$count = array( "success" => 1 );

	if( $cart ) {
	
		$items = $this->Message( "Items Successfully Synced With Cart" );

		$count[ "cart" ] = $cart ;

		if( ! empty( $changes ) )
			$count[ 'changes' ] = $changes ;

	} else {

		$items = $this->Message( "Cart Successfully Removed" );

		$count[ "empty" ] = 1 ;

	} $count[ "message" ] = $items ;
	
	$items = $this->SilentCall( "Commerce" , "User" , "Factures" , 
		"details" , [ "items" => $itemsObjects , 'shop' => $CartType ] );
    
	$items = $this->SilentCall( "Commerce" , 
		"User" , "Factures" , "Check" , [ "Details" => $items ] );
	
	if( is_array( $items ) ) {
		$count[ "total" ]  = $items[0] ; // Price
		$count[ "actual" ] = $items[2] ; // Actual
		$count[ "items" ]  = $items[6] ;
		$count[ "count" ]  = $items[7] ;
		$count[ "weight" ] = round( $items[8] , 3 , PHP_ROUND_HALF_UP ) ;
	} else if( is_string( $items ) ){
		$count[ "total" ]  = $items ; // Price
		$count[ "actual" ] = $items ; // Actual
		$count[ "items" ]  = count( $itemsObjects );
		$count[ "count" ]  = -1 ;
		$count[ "weight" ] = -1 ;
	} $this->Response( $count );
	
	return $cart ;

} $this->Response( [ 'message' => "Function Failed" , "failed" => 1 ] );

return $cart ; ?>