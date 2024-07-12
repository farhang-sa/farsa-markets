<?php defined( 'Fallon_Root' ) or die( 'Access Denied : ' . basename( __FILE__ ) ) ;

// Single Item Add

$CartType = isset( $CartType ) ? $CartType : $this->input->CartName ;
$CartType = isset( $CartType ) ? $CartType : $this->COMMERCE()->CartTypeDefault ;

$item = isset( $item ) ?   $item : $this->input->item_id ;

$count = isset( $count ) ? $count : $this->input->item_count ;

$clean = isset( $clean ) ? $clean : $this->input->cart_clean ;

$clean = ( bool ) $clean ;

if( is_string( $item ) )

	$item = $this->COMMERCE()->PullElement( $item );

$cart = $this->User()->Carts( $CartType , $this->User( "code" ) );

if( ! is_array( $item ) || ! isset( $item[ "code" ] ) ){

	$item = $this->Message( "Item Not Found" );

	$this->Response( [ 'message' => $item , "failed" => 1 ] );

	return $cart ; // No Item To Change : Return Cart !

} if( $count <= 0 ) // Go Remove
	$count = false ;

// Do The Changes !
if( $clean ) $count = $this->User()->setCarts( 
		$CartType , false , $this->User( "code" ) );
else { // Add If OK
	$isok = true ;
	if( isset( $item[ 'iVirtual'] ) ) { // Nothing
		// No Checking :)
	} else if( ! isset( $item[ 'uCount' ] ) ) { // Remove
		$isok = false ;
	} else {
		if( $this->calcForMarketPlaceApp() ){

			$ic = isset( $item[ 'iCount' ] ) ? $item[ 'iCount' ] : 1 ;
			$ic = $ic > 0 ? $ic : 1 ;

			$uc = $item[ 'uCount' ];
			$uc = $uc > 0 ? $uc : 0 ;

			// New Count !
			$item[ 'uCount' ] = $ic * $uc ;

		} 
	} if( $item[ 'uCount' ] < $count ) // Change!
		$count = $item[ 'uCount' ] ;
	if( $isok ) $count = $this->User()->setCarts( $CartType , 
		[ $item[ "code" ] => $count ] , $this->User( "code" ) );
}

$this->User()->LoadCarts( $this->User( "code" ) );
$cart = $this->User()->Carts( $CartType );

if( is_array( $cart ) ) // Clean Shopping Cart
	foreach ( $cart as $key => $value ) 
		if( $value <= 0 ) unset( $cart[ $key ] );

if( $count ){

	$cart = is_array( $cart ) ? $cart : array();

	$cart = ! empty( $cart ) ? $cart : false ;

	$count = array( "success" => 1 );

	if( $cart && array_key_exists( $item[ "code" ] , $cart ) ){

		$item = $this->Message( "Item Successfully Added to Cart" );

		$count[ "cart" ] = $cart ;

	} else if ( ! $cart ){

		$item = $this->Message( "Cart Successfully Removed" );

		$count[ "empty" ] = 1 ;

	} else {

		$item = $this->Message( "Item Successfully Removed From Cart" );

		$count[ "cart" ] = $cart ;

	} $count[ "message" ] = $item ;

	$item = $this->SilentCall( "Commerce" , "User" , "Factures" , "Check" , 
		[ "items" => $cart , "target" => "commerce" ] );

	if( $item ) $count[ "total" ] = $item[0] ;

	$this->Response( $count );

	return $cart ;

} $this->Response( [ 'message' => "Function Failed" , "failed" => 1 ] );

return $cart ; ?>