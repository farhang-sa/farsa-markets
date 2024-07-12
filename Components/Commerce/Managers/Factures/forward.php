<?php defined( 'Fallon_Root' ) or die( 'Access Denied : ' . basename( __FILE__ ) ) ;

if ( ! $this->COMMERCE()->isManager() ){

	$this->Response( [ 'message' => $this->MsgNotManagerAlert() , true ] );

	return false ;

} $facture = isset( $facture ) ? 
	$facture : $this->input->search([ 'forward-id' ]);
$facture = $this->COMMERCE()->PullFacture( $facture );

if( ! is_array( $facture ) || empty( $facture ) ){

	$this->Response( [ 'message' => 'Facture Not Found' , 'failed' => 1 ] );

	return false ;

} $shop = isset( $shop ) ? $shop : $this->input->search([ 'shid' , 'shop' ]);
$shop = $this->COMMERCE()->PullShop( $shop );

if( ! is_array( $shop ) || empty( $shop ) ){

	$this->Response( [ 'message' => 'Target Shop Not Found' , 'failed' => 1 ] );

	return false ;

} $items = $facture[ 'facture' ];

if( is_array( $items ) && !empty( $items ) ) 
    foreach( $items as $k => $v ) 
        if( $k !== "DebtsCost" && $k !== "SoftwareCost" )
            $items[ $k ] = $v['count'] ;
        else unset( $items[ $k ] );
        
//$coupon = isset( $facture['coupon'] ) ? $facture['coupon'] : null ;
$Address = isset( $facture['address'] ) ? $facture['address'] : null ;

unset( $facture[ 'facture'] , $facture['address'] );

$items = $this->Call( 'Commerce' , 'User' , 'Cart' , 'Execute' , 
    [ 'CartType' => $shop['code'] , 'shop' => $shop , 'MyCart' => $items ,
    'Address' => 'No-Address' , 'receive' => 'RECEIVE_SHOP' , 'onsite' => true ,
        'payMode' => 'PAY_BARROW' , 'attach' => $facture[ 'code' ] ]);

if( $items ){ // Set Original Facture To Preparing!

	$facture = $facture[ 'code' ];

	//$shop = $shop['code'] ;

	$items = time() . '' ;

	$this->Data()->UpdateIntoTable( 'commerce_factures' ,
		[ 'category' => 'Preparing' , 'edit' => $items ] ,
		[ 'code' => $facture ] );

} ?>