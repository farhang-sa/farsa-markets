<?php defined( 'Fallon_Root' ) or die( 'Access Denied : ' . basename( __FILE__ ) ) ;

if ( ! $this->COMMERCE()->isManager() ) {

	$this->Response([ "Message" => "Manager Level Access Required" ]);

	return false ;
	
} $shop = isset( $shop ) ? $shop : 
	$this->input->search([ "shid" , "shop" , "sid" ]);

$search = $this->input->search_keywords ;

$user = $this->SilentCall( 
	"User" , "Search" , [ 'UserId' => $search ] ); 

if( isset( $user[ 'User' ] ) )
	unset( $user[ 'User' ] ) ;

if ( ! $user ) {

	$this->Response([ "Message" => "No User Found" , "failed" => 1 ]);

	return false ;
	
} $wh = "" ;

$profile = array();

$debts = array();

if( ! isset( $user[ "code" ] ) ){
	
	foreach ($user as $us )
		$wh .= "'" . $us['code'] . "' ," ;
	
	$wh = trim( $wh , " ," );
	
	$profile = $this->User()->LoadData( 
		"users_profiles" , [ "*" ] , [ "`writer` IN ( " . $wh . " )" ] );
	
	if( $profile && ! isset( $profile[ 'code' ] ) )
	
		foreach ($profile as $key => $value) {

			unset( $profile[$key] );

			$profile[ $value[ 'writer' ] ] = $value ;

		}
	
	elseif( $profile )
		$profile = array( $profile['code'] => $profile );
	
	$debts = $this->SilentCall( 'Commerce' , 'Managers' , 
		'Debtors' ,'list' , [ 'user' => $user , 'shop' => $shop ] );

} else {

	$profile = $this->SilentCall( "User" , 
		"Profile" , [ "UserCode" => $user[ "writer" ] ] );

	if( $profile )
		$profile = array( $profile['writer'] => $profile );
	
	$debts = $this->SilentCall( 'Commerce' , 'Managers' , 
		'Debtors' , 'list' , [ 'user' => $user , 'shop' => $shop  ] );

} $this->Response([ "Message" => "Data Loaded" , 
		"debts" => $debts , "profiles" => $profile ]);

return [ $debts , $profile ]; ?>