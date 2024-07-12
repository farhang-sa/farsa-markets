<?php defined( 'Fallon_Root' ) or die( 'Access Denied : ' . basename( __FILE__ ) ) ;

$com = $this->COMMERCE();

if ( ! $com->isManager() ){

	$this->Response( [ 'message' => $this->MsgNotManagerAlert() , true ] );

	return false ;

} $Products = array() ;

$cPage   = isset( $cPage )   ? $cPage  : $this->input->cPage  ;

$cPage   = (int) $cPage > 0  ? $cPage  : 1 ;

$cCount  = isset( $cCount )  ? $cCount : $this->input->cCount ;

$cCount  = (int) $cCount > 0 ? $cCount : 20 ;

$cPage   = ( $cPage - 1 ) * $cCount ;

$Where = array( '`content` LIKE "%input_type_product%"' );

$keywords  = $this->input->search_keyword ;

if( $keywords ) {
	
	$mSearch = "( `content` LIKE '%{$keywords}%' OR " ;
	
	$ex = explode( ' ' , $keywords ) ;
	
	if( count( $ex ) >= 2 ) foreach ($ex as $value) {
	
		$value = trim( $value , ' \\' ) ;
	
		if( strlen( $value ) <= 0 ) continue ;
	
		$mSearch .= "`content` LIKE '%{$value}%' OR " ;
	
	} $mSearch = trim( $mSearch , 'OR ' ) ;
	
	$mSearch .= " )" ;
	
	$Where[0] .= " AND " . $mSearch ;

} if ( $this->input->pid ){

	$Where[ "code" ] = $this->input->pid ;

	$Products = $com->PullItems( 'commerce' , null , null , 
		$Where , null , null , '`create` DESC' );

} else if ( $this->input->sid ){

	$Where[ "category" ] = $this->input->sid ;

	$Products = $com->PullItems( 'commerce' , null , null , 
		$Where , $cCount , $cPage , '`create` DESC' );

} else if ( $this->input->shid ) {

	$shop = $com->PullShop( $this->input->shid );

	$shop = isset( $shop[ 'subjects' ] ) ? $shop[ 'subjects' ] : array();

	$shop = isset( $shop['products_template'] ) ? 'commerce_temps' : 'commerce' ;
	
	//$Where[] = "`discussion` IN (" . $com->GetParentDiscussionsList( $this->input->shid , "LoadShop" ) . ")";
    
	$Products = $com->PullItems( $shop , $this->input->shid , 
	    null , $Where , $cCount , $cPage , '`create` DESC' ) ;
	
} if( is_array( $Products ) && isset( $Products["code"] ) )

	$Products = array( $Products );

if( is_array( $Products ) ) foreach ( $Products as $key => $value ) {

	$value[ "inCart" ] = 0 ;

	$Products[ $key ] = $value ;

} $this->Response( [ 'message' => 'Products Loaded' , 'products' => $Products ] );

return $Products ;

?>