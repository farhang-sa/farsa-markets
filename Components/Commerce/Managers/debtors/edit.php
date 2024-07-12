<?php defined( 'Fallon_Root' ) or die( 'Access Denied : ' . basename( __FILE__ ) ) ;

if ( ! $this->COMMERCE()->isManager() ) {

	$this->Response([ "Message" => "Manager Level Access Required" ]);

	return false ;
	
} $shop = isset( $shop ) ? $shop : 
	$this->input->search([ "shid" , "shop" , "sid" ]);

$user  = isset( $user) ? $user : 
	$this->input->search(['uid' ,'user' ,'user-id']);

$where = [ 'shop' => $shop , 'user' => $user ];

$getDebt = $this->SilentCall( 'Commerce' , 
	'Managers' , 'Debtors' , 'List' , $where );

if( ! $getDebt ){

	$this->Response([ "Message" => "No Debt Found" , "failed" => 1 ]);

	return false ;

} $getDebt = array_pop( $getDebt );

$amount = isset( $amount ) ? $amount : 
	$this->input->search(['amount' , 'debt_amount' , 'debt-amount']);

$add  = isset( $add ) ? $add : 
	$this->input->search(['add' , 'debt_add_amount', 'debt_increase_amount']);

$sub = isset( $sub ) ? $sub : 
	$this->input->search(['sub' , 'debt_sub_amount', 'debt_decrease_amount']);

if( ! $amount && ! $add && ! $sub ){

	$this->Response([ "Message" => "No Action Amount Received" , "failed" => 1 ]);

	return false ;

} $nAmount = $amount ; // New Amount !

if( $add ){

	$nAmount = $getDebt[ "amount" ] + $add ;

	if( $nAmount < 0 ) $nAmount = 0 ;

} else if( $sub ){

	$nAmount = $getDebt[ "amount" ] - $sub ;

	if( $nAmount < 0 ) $nAmount = 0 ;

} // New Update

$table = "commerce_markets_debtors";

$update = array( "amount" => $nAmount , "edit" => time() . '' ); 

$this->Data()->UpdateIntoTable( $table , $update , $where ); 

$this->Response([ "Message" => "Debtor Added Successfully" , "success" => 1 ]);

return true ; ?>