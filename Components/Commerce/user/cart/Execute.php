<?php defined( 'Fallon_Root' ) or die( 'Access Denied : ' . basename( __FILE__ ) ) ;

if ( ! $this->User( "login" ) ) {

	$this->Response(["message" => $this->MsgNotLogedinAlert() , "failed" => 1 ]);

	return false ;

} // Save Factures / Save Access Of Items / Save Fincance Changes
$coupon  = isset( $coupon )	 ? $coupon  : $this->input->facture_coupon ;
$onsite  = isset( $onsite )  ? $onsite  : $this->input->facture_onsite ;
$receive = isset( $receive ) ? $receive : $this->input->facture_receive ;
$payMode = isset( $payMode ) ? $payMode : $this->input->facture_pay ;
$attach  = isset( $attach )  ? $attach  : $this->input->facture_attach ;
$Address = isset( $Address ) ? $Address : $this->input->facture_address ;

$CartType = isset( $CartType ) ? $CartType : $this->input->CartName ;
$CartType = $CartType ? $CartType : $this->COMMERCE()->CartTypeDefault ;

$shop = isset( $shop ) ? $shop : $this->input->shop ;

$MyCart = isset( $MyCart ) ? $MyCart : $this->input->MyCart ; 

$Checkout = $this->SilentCall( "Commerce" , "User" , "Cart" , "Checkout" , 
	[ "CartType" => $CartType , 'MyCart' => $MyCart , 'shop' => $shop , "coupon" => $coupon ] );

if( ! $Checkout || $Checkout < 0 ){

	$what = $Checkout === -2 ? "max_orders" : "shop_closed" ;

	$Checkout = $this->Message();

	$this->Response(["message" => $Checkout , $what => 1 , "failed" => 1 ]);

	return false ;

} $Cart 	= $Checkout[0] ;
$allVirtual = $Checkout[5] ;
$Checkout  	= false ; // Free Memory
$MyCart = false ; // Free Memory
$shop = false ; // Free Memory

if( $allVirtual === true ){ // All Products Are Virtual

	//

} else { 

	$on_site = $this->COMMERCE()->Settings( "onsite_payment" , false );

	if( $onsite && ! $on_site ){

		$Address = $this->Message( "No On-Site Payments" );

		$this->Response(["message" => $Address , "failed" => 1 ]);

		return false ;

	} if( $receive !== "RECEIVE_SHOP" ){

		// Address Required !
		if( is_string( $Address ) ){

			$addsList = $this->SilentCall( "social" , "user" , "addresses" , "list" );
			
			if( is_array( $addsList ) && isset( $addsList[ $Address ] ) )

				$Address = $addsList[ $Address ] ;

		} if( ! is_array( $Address ) ){

			$Address = $this->Message( "No Postal Address" );

			$this->Response(["message" => $Address , "failed" => 1 ]);

			return false ;

		}

	}

} $exec = $this->SilentCall( "Commerce" , "user" , "factures" , "execute" ,  
	["items" => $Cart ,"Address" => $Address ,"onsite" => $onsite , "coupon" => $coupon , 
		"extra" => ["pay" => $payMode ,"receive" => $receive , 'attach' => $attach ] ]);

$msg = $this->Message() ; // Any Message From Execute !
$msg = $this->Message( "Cart Checkout Failed" ) . " : " . $msg ;

$exec = $this->COMMERCE()->PullFacture( $exec ) ;

if( $exec ){ // Drop The Cart

	$msg = "Cart Successfully Checkedout" ;

	$this->User()->setCarts( $CartType , false , $this->User( "code" ) );

} $msg = $this->Message( $msg );

$this->Response([ "message" => $msg , "execute" => $exec["reference"] ]);

return $exec ; ?>