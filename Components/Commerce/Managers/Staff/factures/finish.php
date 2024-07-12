<?php defined( 'Fallon_Root' ) or die( 'Access Denied : ' . basename( __FILE__ ) ) ;

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
	
} $shop = $this->COMMERCE()->PullElement( $shop );

if( ! $shop ){

	$this->Response([ 'Message' => 'Shop Not Found' ]);

	return false ;
	
} $isMarketPlaceShop = MarketHandler::isMarketPlaceShop( $shop ) ;

$facture[ 'category' ] = 'Finished' ;

$Where = array( 'code' => $facture[ 'code' ] );

$this->Data()->EditRowsJsonContent( 
	'commerce_factures' , $facture , $Where ) ;

$facture = $this->Data()->LoadRowsJsonContent( 
	'commerce_factures' , 'create-desc' , $Where);

$facture = $facture[ 'facture' ];

if( is_array( $facture ) ) foreach ( $facture as $pid => $value )

	$facture[ $pid ] = $value[ 'count' ];

$products = $this->COMMERCE()->getWhereIn( array_keys( $facture ) );

$products = $this->COMMERCE()->PullProducts( 
	null , null , null , "`code` IN ( {$products} )" );

if( is_array( $products ) && isset( $products['code'] ) )

	$products = array( $products );

if( is_array( $products ) ) foreach ( $products as $key => $product ) {

	unset( $products[ $key ] );

	if( isset( $product['iVirtual'] ) )

		continue;

	if( ! isset( $product[ 'uCount' ] , $product[ 'code' ] ) )

		continue ;

	$key = $product[ 'code' ];

	if( ! isset( $facture[ $key ] ) )

		continue ;
		
	if( $isMarketPlaceShop )
	
	    continue ; // Do Not Change For MarketPlace Shops! ( We Are Always Full :)

	$oCount = $product[ 'uCount' ] ;

	$product[ 'uCount' ] -= $facture[ $key ] ;

	if( $product[ 'uCount' ] < 0 )

		$product[ 'uCount' ] = "0" ;

	$this->Data()->EditRowsJsonContent(
		'commerce' , $product , [ 'code' => $product[ 'code' ] ] );

	$this->Data()->ChangeCount( 'commerce_counts' , 
		[ 'discussion' => $shop , 'category' => $product[ 'code' ] ] , 
		[ 'counter' => $oCount ] , -1 * ( int ) $facture[ $key ] );

} $this->Response([ 'Facture' => $facture , 'success' => 1 ]);

return $facture; ?>