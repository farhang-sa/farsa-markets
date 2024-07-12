<?php defined( 'Fallon_Root' ) or die( 'Access Denied : ' . basename( __FILE__ ) ) ;

if ( ! $this->COMMERCE()->isManager() ) {

	$this->Response([ "Message" => "Manager Level Access Required" ]);

	return false ;
	
} $Where = "" ; // Build Where

$Debtors = [ $this->input->shid , $this->input->shop , $this->input->sid ];

foreach ($Debtors as $value) {

	if( ! $value ) continue ;
	
	if( is_array( $value ) ) foreach ( $value as $k => $v ) {
	
		if( is_int( $k ) && is_string( $v ) )

			$Where .= "'{$v}' , " ;

		else if ( is_string( $k ) )

			$Where .= "'{$k}' , " ;

	} else if ( is_string( $value ) )

		$Where .= "'{$value}' , " ;

} $Where = trim( $Where , " ," );

if( empty( $Where ) ){

	$this->Response( [ 'message' => 'No Target Shop' , 'failed' => 1 ] );

	return false ;

} $Where = "`shop` IN( {$Where} )" ;

$table = "commerce_markets_debtors" ;

$Debtors = "SELECT SUM(`amount`) as `TOTALS` FROM `{$table}` WHERE {$Where} ;" ;

$Debtors = $this->Data()->Query( $Debtors )->exec()->Result();

if ( $Debtors && is_object( $Debtors ) ) {

	$Debtors = $Debtors->QueryResults() ;

} if( ! $Debtors ){

	$this->Response( [ 'message' => 'No Debtors Founded' , 'failed' => 1 ] );

	return false ;

} $Debtors = array_pop( $Debtors )['TOTALS'];

return $Debtors ?>