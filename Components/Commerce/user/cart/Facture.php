<?php defined( 'Fallon_Root' ) or die( 'Access Denied : ' . basename( __FILE__ ) ) ;

if ( ! $this->User( "login" ) ) {

	$this->Response(["message" => $this->MsgNotLogedinAlert() , true ]);

	return false ;

} $coupon = isset( $coupon ) ? $coupon : $this->input->coupon ;

$CartType = isset( $CartType ) ? $CartType : $this->input->CartName ;
$CartType = $CartType ? $CartType : $this->COMMERCE()->CartTypeDefault ;

$CartDetails = $this->SilentCall( "Commerce" , "User" , "Cart" , 
	"Checkout" , [ "coupon" => $coupon , "CartType" => $CartType ] );
	
//file_put_contents( TPath_Root . TPath_DS . "check.txt" , $this->JSON_array_to_str( $CartDetails ) . "\n\n" . $CartType );

return $this->Call( "Commerce" , "User" , "Factures" , "Check" , 
	[ "Details" => $CartDetails , "target" => "Commerce" ]); ?>