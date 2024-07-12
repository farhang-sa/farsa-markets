<?php defined( 'Fallon_Root' ) or die( 'Access Denied : ' . basename( __FILE__ ) ) ;

$facture = isset( $facture ) ? 
	
	$facture : $this->input->search([ 'facture' , 'fid' ]) ;

if( ! is_array( $facture ) )

	$facture = $this->COMMERCE()->PullFacture( $facture );

if( ! is_array( $facture ) ){

	$this->Response([ 'Message' => 'Facture Not Found' , 'failed' => 1 ]);

	return false ;

} $shop = array_keys( $facture[ 'discussions' ] )[0];

$isa = $this->COMMERCE()->isShopAgent( $shop );

$ism = $this->COMMERCE()->isManager() ;

if( ! $isa && ! $ism ){

	$this->Response([ 'Message' => 'Manager/Agent Level Access Required' ]);

	return false ;
	
} $facture[ 'Agent' ] = $this->User( 'code' );

$facture[ 'category' ] = 'Preparing' ;

$Where = array( 'code' => $facture[ 'code' ] );

$this->Data()->EditRowsJsonContent( 'commerce_factures' , $facture , $Where ) ;

$facture = $this->Data()->LoadRowsJsonContent( 'commerce_factures' , 'create-desc' , $Where);

$this->Response([ 'Facture' => $facture , 'success' => 1 ]);

return $facture; ?>