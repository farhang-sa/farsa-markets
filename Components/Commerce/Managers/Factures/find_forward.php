<?php defined( 'Fallon_Root' ) or die( 'Access Denied : ' . basename( __FILE__ ) ) ;

if ( ! $this->COMMERCE()->isManager() ){

	$this->Response( [ 'message' => $this->MsgNotManagerAlert() , true ] );

	return false ;

} $facture = isset( $facture ) ? 
	$facture : $this->input->search([ 'fid' , 'facture' ]);
$facture = $this->COMMERCE()->PullItems( 'commerce_factures' , 'Factures' , 
	null , [ "`code`!= '{$facture}' AND `content` LIKE '%$facture%'" ] );

if( ! is_array( $facture ) || empty( $facture ) ){

	$this->Response( [ 'message' => 'Facture Not Found' , 'failed' => 1 ] );

	return false ;

} $facture = array_keys( $facture['discussions'] )[0] ;

$this->Response([ 'shop' => $facture[0] ]);

return $facture[0] ; ?>