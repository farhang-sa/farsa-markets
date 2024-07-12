<?php defined( 'Fallon_Root' ) or die( 'Access Denied : ' . basename( __FILE__ ) ) ;

if ( ! $this->User( 'login' ) ) {

	$this->Response([ "Message" => "Login Required" ]);

	return false ;
	
} $Where = '`user`=\'' . $this->User( 'code' ) . '\'' ; 

$cPage   = isset( $cPage )   ? $cPage  : $this->input->cPage  ;

$cPage   = (int) $cPage > 0  ? $cPage  : 1 ;

$cCount  = isset( $cCount )  ? $cCount : $this->input->cCount ;

$cCount  = (int) $cCount > 0 ? $cCount : 15 ;

$cPage   = ( $cPage - 1 ) * $cCount ;

$shop = isset( $shop ) ? $shop : 
	$this->input->search([ "shid" , "shop" , "sid" ]);

if( is_array( $shop ) && isset( $shop["code"] ) )

	$shop = $shop["code"] ;

if( $shop ) {

	$shop = $this->SYSTEM()->getWhereIn( $shop , "code" );

	$Where .= " AND `shop` IN( {$shop} )" ;

} $Debts = "SELECT * FROM `commerce_markets_debtors` WHERE {$Where}" ;

$Debts .= " ORDER BY `create` DESC LIMIT {$cCount} OFFSET {$cPage};" ;

$Debts = $this->Data()->Query( $Debts )->exec()->Result();

if ( $Debts && is_object( $Debts ) ) {

	$Debts = $Debts->QueryResults() ;

} if( ! $Debts ){

	$this->Response( [ 'message' => 'No Debts Founded' , 'failed' => 1 ] );

	return false ;

} if( is_array( $Debts ) && isset( $Debts[ "user" ] ) )

	$Debts = array( $Debts );

foreach ( $Debts as $key => $value ) {

	unset( $Debts[ $key ] );

	$Debts[ $value[ 'shop' ] ] = $value ;

} $this->Response( [ 'message' => 'Debts Loaded' , 'debts' => $Debts ] );

return $Debts ; ?>