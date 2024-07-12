<?php defined( 'Fallon_Root' ) or die( 'Access Denied : ' . basename( __FILE__ ) ) ;

if ( ! $this->COMMERCE()->isManager() ) {

	$this->Response([ "Message" => "Manager Level Access Required" ]);

	return false ;
	
} $shop = isset( $shop ) ? $shop : 
	$this->input->search([ "shid" , "shop" , "sid" ]);

$user  = isset( $user) ? $user : 
	$this->input->search(['uid' ,'user' ,'user-id']);

$getDebt = $this->SilentCall( 'Commerce' , 'Managers' , 
	'Debtors' , 'List' , [ 'shop' => $shop , 'user' => $user ] );

if( $getDebt ){

	$this->Response([ "Message" => "Debtor Already Exists" , "exist" => 1 ]);

	return false ;

} $amount = isset( $amount ) ? $amount : 
	$this->input->search(['amount' , 'debt-amount']);

if( ! $amount || ( $amount + 0 ) <= 1 ){

	$this->Response([ "Message" => "No Debt Amount Received" , "failed" => 1 ]);

	return false ;

} $table = "commerce_markets_debtors";

$time = time() . '' ;

$insert = array(
	"user" => $user ,
	"shop" => $shop ,
	"amount" => $amount ,
	"create" => $time ,
	"edit" => $time
); // New Insert

$this->Data()->InsertIntoTable( $table , $insert ); 

$this->Response([ "Message" => "Debtor Added Successfully" , "success" => 1 ]);

return true ; ?>