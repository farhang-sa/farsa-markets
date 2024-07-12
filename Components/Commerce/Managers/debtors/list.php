<?php defined( 'Fallon_Root' ) or die( 'Access Denied : ' . basename( __FILE__ ) ) ;

if ( ! $this->COMMERCE()->isManager() ) {

	$this->Response([ 'Message' => 'Manager Level Access Required' ]);

	return false ;
	
} $Where = "" ; // Build Where 

$cPage   = isset( $cPage )   ? $cPage  : $this->input->cPage  ;

$cPage   = (int) $cPage > 0  ? $cPage  : 1 ;

$cCount  = isset( $cCount )  ? $cCount : $this->input->cCount ;

$cCount  = (int) $cCount > 0 ? $cCount : 15 ;

$cPage   = ( $cPage - 1 ) * $cCount ;

$shop = isset( $shop ) ? $shop : 
	$this->input->search([ "shid" , "shop" , "sid" ]);

if( is_array( $shop ) && isset( $shop["code"] ) )

	$shop = $shop["code"] ;

$Where = $this->SYSTEM()->getWhereIn( $shop , "code" );

if( empty( $Where ) ){

	$this->Response( [ 'message' => 'No Target Shop' , 'failed' => 1 ] );

	return false ;

} $Where = "`shop` IN( {$Where} )" ;

$user  = isset( $user) ? $user : 
	$this->input->search(['uid' ,'user' ,'user-id']);

if( $user ) {

	$wh = $this->SYSTEM()->getWhereIn( $user , 'writer' );

	$Where .= " AND `user` IN ( {$wh} )" ;

} $from = isset( $from ) ? $from : 
	$this->input->search([ 'from' , 'time' ]);

$from_type = isset( $from_type ) ? 
	$from_type : $this->input->search([ 'from_type' , 'time_type' ]);

if( stristr( 'create-edit' , $from_type ) === false )

	$from_type = "edit" ;

if( $from && $from_type )

	$Where .= " AND `{$from_type}` >= '{$from}'" ;

$order = isset( $order ) ? $order : 
	$this->input->search([ 'order' , 'order-debts' ]);

$order = $order !== null ? $order : "create" ;

$arrange = isset( $arrange ) ? $arrange : 
	$this->input->search([ 'arrange' , 'arrange-debts' ]);

$arrange = $arrange !== null ? $arrange : "DESC" ;

$Debtors = "SELECT * FROM `commerce_markets_debtors` WHERE {$Where}" ;

$Debtors .= " ORDER BY CAST(`{$order}` as unsigned) {$arrange} LIMIT {$cCount} OFFSET {$cPage};" ;

$Debtors = $this->Data()->Query( $Debtors )->exec()->Result();

if ( $Debtors && is_object( $Debtors ) ) {

	$Debtors = $Debtors->QueryResults() ;

} if( ! $Debtors ){

	$this->Response( [ 'message' => 'No Debtors Found' , 'failed' => 1 ] );

	return false ;

} if( is_array( $Debtors ) && isset( $Debtors[ "user" ] ) )

	$Debtors = array( $Debtors );

foreach ( $Debtors as $key => $value ) {

	unset( $Debtors[ $key ] );

	$Debtors[ $value['user'] ] = $value ;
}

$this->Response( [ 'message' => 'Debtors Loaded' , 'debtors' => $Debtors ] );

return $Debtors ; ?>