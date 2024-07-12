<?php defined( 'Fallon_Root' ) or die( 'Access Denied : ' . basename( __FILE__ ) ) ;

if ( ! $this->COMMERCE()->isManager() ) {

	$this->Response([ "Message" => "Manager Level Access Required" ]);

	return false ;
	
} $Where = "" ; // Build Where 

$shop = isset( $shop ) ? $shop : 
	$this->input->search([ "shid" , "shop" , "sid" ]);

$user  = isset( $user) ? $user : 
	$this->input->search(['uid' ,'user' ,'user-id']);

$getDebt = $this->SilentCall( 'Commerce' , 'Managers' , 
	'Debtors' , 'List' , [ 'shop' => $shop , 'user' => $user ] );

if( ! $getDebt ){

	$this->Response([ "Message" => "Debt Not Found" , "failed" => 1 ]);

	return false ;

} // else :

$getDebt = array_pop( $getDebt );

$confirm = isset( $confirm ) ? $confirm : $this->input->confirm ;

if( strtolower( $confirm ) !== "1" ){

	$this->Response([ "Message" => "Confirm Delete Please" , "confirm" => 1 ]);

	return $getDebt ;

} // else : delete

$q = "commerce_markets_debtors" ;

$q = "DELETE FROM `{$q}` WHERE `shop`='{$shop}' AND `user`='{$user}'" ;

$q = $this->Data()->Query( $q )->exec()->Result();

$this->Response([ "Message" => "Debt Loaded" , "success" => $getDebt ]);

return true ; ?>