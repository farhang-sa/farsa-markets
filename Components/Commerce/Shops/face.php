<?php defined( 'Fallon_Root' ) or die( 'Access Denied : ' . basename( __FILE__ ) ) ;

if ( ! $this->User( 'login' ) ) {

	$this->Response([ "Message" => "Login Required" ]);

	return false ;
	
} $shop = isset( $shop ) ? $shop : 
$this->input->search([ "shid" , "shop" , "shop-id" ]);

if( is_array( $shop ) && isset( $shop['code'] ) )

	$shop = $shop['code'];

// Fetch Debts
$debts = $this->SilentCall( "Commerce" , "user" , 
	"debts" , "list" , [ 'shop' => $shop ] );

if( $debts )
	$debts = array_values( $debts )[0][ "amount" ];

// Fetch Orders
$this->input->pay_state = "Saved" ;
$Saved = $this->SilentCall( "Commerce" , 
	"user" , "factures" , "list" , [ 'shop' => $shop ] );
$this->input->pay_state = "Preparing" ;
$Preparing = $this->SilentCall( "Commerce" , 
	"user" , "factures" , "list" , [ 'shop' => $shop ] );
$this->input->pay_state = "Prepared" ;
$Prepared = $this->SilentCall( "Commerce" , 
	"user" , "factures" , "list" , [ 'shop' => $shop ] );

$facturesByState = array( $Saved , $Preparing , $Prepared );

$counter = 0 ;
foreach ($facturesByState as $factures ) {

	if( $factures && isset( $factures['code'] ) )

		$counter++ ;

	else if( $factures )

		$counter += count( $factures );	

} $res = array();

if( $debts )
	$res[ 'debt' ] = $debts ;

if( $counter >= 1 )
	$res[ 'factures' ] = $counter ;

$res[ 'message' ] = 'Intel Loaded' ;

$this->Response( $res );

return $res ; ?>