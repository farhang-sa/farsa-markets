<?php defined( 'Fallon_Root' ) or die( 'Access Denied : ' . basename( __FILE__ ) ) ;

// Update :ReExecute Facture!
if ( ! $this->User( 'login' ) ) {

	$this->Response(['message' => $this->MsgNotLogedinAlert() , 'failed' => 1 ]);

	return false ;

} // Save Factures / Save Access Of Items / Save Fincance Changes
$newCart = isset( $newCart ) ? $newCart : 
	$this->input->search([ 'facture_items' , 'facture_cart' ]);

$newCart = is_array( $newCart ) ? $newCart : $this->JSON_str_to_array( $newCart );

if( ! is_array( $newCart ) || empty( $newCart ) ){

	$this->Response(['message' => 'Facture Items Not Received' , 'failed' => 1 ]);

	return false ;

} // Resume :

$facture = isset( $facture ) ? $facture : $this->input->facture_id ;

$CartType = $facture ;

if( is_string( $facture ) ){

	$facture = $this->COMMERCE()->PullFacture( $facture );

	if( ! is_array( $facture ) || empty( $facture ) ){

		$this->Response(['message' => 'Facture Id Not Received' , 'failed' => 1 ]);

		return false ;

	} $CartType = array_keys( $facture[ 'discussions' ] )[0] ;

} $isa = $this->COMMERCE()->isShopAgent( $CartType );

$ism = $this->COMMERCE()->isManager() ;

if( ! $isa && ! $ism ){

	$this->Response([ 'Message' => 'Manager/Agent Level Access Required' ]);

	return false ;
	
} $Checkout = $this->SilentCall( 'Commerce' , 'User' , 'Cart' , 
	'Checkout' , [ 'CartType' => $CartType , 'MyCart' => $newCart ] );

if( ! $Checkout ){

	$Checkout = $this->Message( 'Cart Facture Failed' );

	$this->Response([ 'message' => $Checkout , 'failed' => 1 ]);

	return false ;

} $Cart 	= $Checkout[0] ;

$Checkout  	= false ; // Free Memory

$exec = $this->SilentCall( 'Commerce' , 'user' , 'factures' , 'execute' , 
	[ 'items' => $Cart , 'facture' => $facture , 'newStatus' => 'Prepared'
		, 'onsite' => isset( $facture[ 'onsite' ] ) ? true : false ,
		'address' => isset( $facture['address'] ) ? $facture['address'] : null ,
		"coupon" => isset( $facture['coupon'] ) ? $facture['coupon'] : null ] );

$msg = $this->Message() ; // Any Message From Execute !

$exec = $this->COMMERCE()->PullFacture( $facture["code"] ) ;

if( $exec ){
    
    if( isset( $facture[ 'attach' ] ) ){ // Attached Facture!
		
		// Update Old Facture As Prepared!
		$msg = $facture[ 'attach' ];

		$this->Data()->UpdateIntoTable( 'commerce_factures' ,
			[ 'category' => 'Prepared' , 'edit' => time() . '' ] ,
			[ 'code' => $msg ] );

	} $msg = $this->Message( 'Facture Successfully Updated' );

} else $msg = $this->Message( 'Facture Update Failed' ) . " : " . $msg ;

$this->Response([ 'message' => $msg , 
	'execute' => $exec['reference'] , 'facture' => $exec ]);

return $exec ; ?>