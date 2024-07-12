<?php defined( 'Fallon_Root' ) or die( 'Access Denied : ' . basename( __FILE__ ) ) ;

$shop = isset( $shop ) ? 
	$shop : $this->input->search([ 'shop' , 'shid' ]) ;

if( ! is_array( $shop ) )
	$shop = $this->COMMERCE()->PullShop( $shop );

if( ! is_array( $shop ) ){

	$this->Response([ "Message" => "Shop Not Found" , "failed" => 1 ]);

	return false ;

} $ism = $this->COMMERCE()->isManager() ;

$isa = $this->UAC( $shop );

if( ! $isa && ! $ism ){

	$this->Response([ "Message" => "Manager/Owner Level Access Required" ]);

	return false ;
	
} $shop = $shop['code'] ;

$Agents = $this->COMMERCE()->PullAgentsList( $shop );

if( ! $Agents ){

	$this->Response([ "Message" => "No Agents Found" , "failed" => 1 ]);

	return false ;

} if( isset( $Agents[ 'writer' ] ) )
	$Agents = array( $Agents );

foreach ($Agents as $key => $value) {

	unset( $Agents[$key] );

	$Agents[ $value['writer'] ] = 1 ;

} // Build List

$today = array();
$total = array();

foreach ($Agents as $key => $value ) {
	
	$today[ $key ] = 0 ;
	$total[ $key ] = 0 ;
	$a = $this->User()->getAction( 
		'commerce' , $shop , "today_preparing"  , $key );
	if( $a )
		$today[ $key ] = ( int ) $a['counter'] ;
	$a = $this->User()->getAction( 
		'commerce' , $shop , "total_preparing"  , $key );
	if( $a )
		$total[ $key ] = ( int ) $a['counter'] ;

	$Agents[ $key ] = $total[ $key ] . " - " . $today[ $key ];

} // Sort
arsort( $today );
arsort( $total );

$Response[ "agents" ] = $Agents ;
$Response[ "today" ]  = $today ;
$Response[ "total" ]  = $total ;

$this->Response( $Response ); ?>