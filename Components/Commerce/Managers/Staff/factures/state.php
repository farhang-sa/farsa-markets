<?php defined( 'Fallon_Root' ) or die( 'Access Denied : ' . basename( __FILE__ ) ) ;

// Deals With Facture Status Change
// Saved -> Received -> Prepared -> Rejected - Sent
$facture = isset( $facture ) ? 
	
	$facture : $this->input->search([ 'facture' , 'fid' ]) ;

if( ! is_array( $facture ) )

	$facture = $this->COMMERCE()->PullFacture( $facture );

if( ! is_array( $facture ) ){

	$this->Response([ 'Message' => 'Facture Not Found' , 'failed' => 1 ]);

	return false ;

} if( $facture['category'] === 'Finished' ){

	$this->Response([ 'Message' => 'Facture Is Already Finished' , 'failed' => 1 ]);

	return false ;

} $shop = array_keys( $facture[ 'discussions' ] )[0];

$isa = $this->COMMERCE()->isShopAgent( $shop );

$ism = $this->COMMERCE()->isManager() ;

if( ! $isa && ! $ism ){

	$this->Response([ 'Message' => 'Manager/Agent Level Access Required' ]);

	return false ;
	
} $category = $facture['category'];

$newState = isset( $newState ) ? $newState : $this->input->newState ;

$newState = ucfirst( $newState );

$States = array(
	'Saved' => $category !== 'Saved' ,
	'Received' => $category === 'Saved' ,
	'Rejected' => $category === 'Saved' ||
	    $category === 'Received' || $category === 'Preparing' ,
	'Sent' => $category === 'Prepared'
);

if( isset( $States[ $newState ] ) && $States[ $newState ] ){
    
    $category = $newState ;

	$this->Data()->UpdateIntoTable( 'commerce_factures' , 
		[ 'category' => $category ] , [ 'code' => $facture['code'] ] );

	$this->Response([ 'status' => $category , 'success' => 1 ]);

} else $this->Response([ 'status' => $category , 'failed' => 1 ]);

return $category; ?>