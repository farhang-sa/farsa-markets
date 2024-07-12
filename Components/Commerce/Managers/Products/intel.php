<?php defined( 'Fallon_Root' ) or die( 'Access Denied : ' . basename(  __FILE__ ) ) ;

if ( ! $this->COMMERCE()->isManager() ){

	$this->Response( [ 'message' => $this->MsgNotManagerAlert() , true ] );

	return false ;

} $View = isset( $View ) ? $View : "create-desc" ;

////////////////

$Where = ( isset( $Where ) && is_array( $Where ) ) ? $Where : array() ;

if ( empty( $Where ) ){

	$this->Response( [ "products" => false , "message" => 'No Direction' , true ] ) ;

	return false ;

} 

//////////////

$Product = isset( $Product ) ? $Product : array();

$Product = is_array( $Product ) ? $Product : array();

//////////////

$Delete = isset( $Delete ) ? $Delete : false ;

$Delete = $Delete ? true : false ;

$table = isset( $Product[ 'input_product_template' ] ) ? "commerce_temps" : "commerce" ;

////////////
$Product = $this->CompileJsonActions( $table , $View , $Where , $Product , $Delete );

if ( ! empty( $Product ) && ! isset( $Product[ 0 ] ) ) $Product = array( $Product ) ;

if ( is_array( $Product ) ) foreach ( $Product as $key => $value ) {

	if ( is_array( $value ) && isset( $value["shop"] ) ) {

		if ( isset( $value["id"] ) ) unset( $value["id"] ) ;

		$value[ "parent" ] 	= isset( $value[ "parent" ] ) 	? $value[ "parent" ] 	: $value["shop"] ;

		$value[ "title" ] 	= isset( $value[ "title" ] ) 	? $value[ "title" ] 	: "product title" ;

		$value[ "file" ] 	= isset( $value[ "file" ] ) 	? $value[ "file" ] 		: false ;

		$value[ "tags" ] 	= isset( $value[ "tags" ] ) 	? $value[ "tags" ] 		: "tags" ;

		$value[ "price" ] 	= isset( $value[ "price" ] ) 	? $value[ "price" ] 	: "0" ;

		$value[ "rank" ] 	= isset( $value[ "rank" ] ) 	? $value[ "rank" ] 		: "0" ;

		$value[ "status" ] 	= isset( $value[ "status" ] ) 	? $value[ "status" ] 	: "0" ;

		$value[ "view" ] 	= isset( $value[ "view" ] ) 	? $value[ "view" ] 		: "0" ;

		$value[ "like" ] 	= isset( $value[ "like" ] ) 	? $value[ "like" ] 		: "0" ;

		$value[ "hate" ] 	= isset( $value[ "hate" ] ) 	? $value[ "hate" ] 		: "0" ;

		$value[ "down" ] 	= isset( $value[ "down" ] ) 	? $value[ "down" ] 		: "0" ;

		$value[ "edit" ] 	= isset( $value[ "edit" ] ) 	? $value[ "edit" ] 		: "0" ;

		$value[ "create" ] 	= isset( $value[ "create" ] ) 	? $value[ "create" ] 	: "0" ;

		$Product[$key] = $value ;

	} else unset( $Product[$key] ) ;

} $Product = ( count( $Product ) === 1 && isset( $Product[ 0 ] ) ) ? $Product[ 0 ] : $Product ;

$this->Response( [ "products" => $Product , "message" => $this->Message() , true ] ) ;

return $Product ;

?>