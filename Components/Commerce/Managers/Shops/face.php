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
	
} $shop = $shop[ 'code' ];

$todayStamp = strtotime('today') + 1 ;

$Response = array();

//////////// Products Info
$Response[ 'total_items' ] = $this->Data()->CountItems( 'commerce' , 
	'writer' , [ "`discussion` = '{$shop}' AND `category` != '{$shop}'"] );
	
$items = $this->Data()->LoadData( "commerce_counts" , 
	[ '*' ] , [ "`counter` <= 6" , 'discussion' => $shop ] );

if( is_array( $items ) ){

	if( isset( $items[ 'code' ] ) )

		$items = array( $items );

	$low = array();
	$fin = array();

	foreach ($items as $value ) {

		$count = ( int ) $value[ 'counter' ] ;
		
		if( $count === 0 )

			$fin[ $value[ 'category' ] ] = 1 ;

		else $low[ $value[ 'category' ] ] = $count ;

	} if( count( $fin ) >= 1 )
		$Response[ 'finished_items' ] = $fin ;

	if( count( $low ) >= 1 )
		$Response[ 'low_count_items' ] = $low ;

} //////////// Orders Info
$items = $this->SilentCall( 'Commerce' , 'Managers' , 'agents' , 
	'factures' , [ 'Time' => $todayStamp , 'shop' => $shop ] );

$Total = 0 ;
$Saved = 0 ;
$Prepa = 0 ;
$Prepd = 0 ;
$Finis = 0 ;

$tValu = 0 ;
$fValu = 0 ;

if( is_array( $items ) && isset( $items["code"] ) )
    $items = array( $items );
    
if( is_array( $items ) )
	foreach ($items as $key => $facture ) {

	unset( $items[ $key ] );
	
	if( isset( $facture["facture"] ) )
	    unset( $facture["facture"] );

	if( ! isset( $facture['category'] ) )
		continue ;

	$Total++ ;
	$tValu += $facture['price'] ;

	switch ( $facture['category'] ) {
		case 'Saved' :
			$Saved++ ;
		break;
		case 'Preparing' :
			$Prepa++ ;
		break;
		case 'Prepared' :
			$Prepd++ ;
		break;
		case 'Finished' :
			$Finis++ ;
			$fValu += $facture['price'] ;
		break;
	}	

} $Response[ 'saved_orders_count' ] 	= $Saved ;
$Response[ 'preparing_orders_count' ] 	= $Prepa ;
$Response[ 'prepared_orders_count' ] 	= $Prepd ;
$Response[ 'finished_orders_count' ] 	= $Finis ;
$Response[ 'total_orders_count' ] 		= $Total ;
$Response[ 'total_orders_value' ] 		= $tValu ;
$Response[ 'finished_orders_value' ] 	= $fValu ;

//////////// Debts Info
$items = 'SELECT COUNT(`amount`) AS `count` , SUM(`amount`) AS `value` ';
$items .= "FROM `commerce_markets_debtors` WHERE `shop`='{$shop}' ";

$Total = $this->Data()->Query( $items )->exec()->Result();

if ( $Total && is_object( $Total ) ) 
	$Total = $Total->QueryResults() ;

if( is_array( $Total ) ){
	
	if( count( $Total ) === 1 )
		$Total = array_pop( $Total );

	if( isset( $Total[ 'count' ] ) )
		$Response[ 'total_debts_count' ] = $Total[ 'count' ] ;

	if( isset( $Total[ 'value' ] ) )
		$Response[ 'total_debts_value' ] = $Total[ 'value' ] ;

} 

$items .= "AND CAST( `edit` as unsigned) >= '{$todayStamp}'" ;

$items = $this->Data()->Query( $items )->exec()->Result();

if ( $items && is_object( $items ) ) 
	$items = $items->QueryResults() ;

if( is_array( $items ) ){
	
	if( count( $items ) === 1 )
		$items = array_pop( $items );

	if( isset( $items[ 'count' ] ) )
		$Response[ 'todays_debts_count' ] = $items[ 'count' ] ;

	if( isset( $items[ 'value' ] ) )
		$Response[ 'todays_debts_value' ] = $items[ 'value' ] ;

} //////////// Staff
$items = $this->COMMERCE()->PullAgentsList( $shop );

if( isset( $items[ 'writer' ] ) )
	$items = array( $items );

$items = count( $items );

$Response[ "staff_count" ] = $items ;

$Response[ "success" ] = 1 ;

$this->Response( $Response );

return $Response ; ?>