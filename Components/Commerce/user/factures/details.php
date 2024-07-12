<?php defined( 'Fallon_Root' ) or die( 'Access Denied : ' . basename( __FILE__ ) ) ;

// *******************************************************************
// Only Usable Before Purchase ( Before : Save Access / Save Facture )

if ( ! $this->User( "login" ) ) 

	return $this->MsgNotLogedinAlert() ;
	
$com = $this->COMMERCE();

$target = isset( $target ) ? $target : $this->input->facture_target ;

// Set Target System To Commerce If Not Sent
$target = $target ? $target : "commerce" ;

$items  = isset( $items ) && is_array( $items ) ? $items : null ;

if( is_string( $items ) ){ // Single Item
	
	$items = $com->PullElementExt( $target , $items );

	if( is_array( $items ) && isset( $items["code"] ) )

		$items = array( $items["code"] => $items );

} if ( ! is_array( $items ) || empty( $items ) )

	return $this->Message( 'Item Not Found' ) ;

$coupon = isset( $coupon ) ? $coupon : $this->input->facture_coupon ;

if( is_string( $coupon ) )

	$coupon = $com->PullCoupon( $coupon );

$cTargets = $coupon && isset( $coupon["targets"] ) ? $coupon["targets"] : null ;

$gold = $this->User( "gold" );

$totalPrice  = 0 ; // Only One System's Items Can Be Purchased At a Time

$actualTotal = 0 ;

$internalOff = 0 ;

$couponOff   = 0 ;

$virtualCount = 0 ;

$totalWeight = 0 ;

$totalCount = 0 ;

$totalItems = 0 ;

$totalProfit = 0 ;

$shop = isset( $shop ) ? $shop : null ;
if( is_string( $shop ) )
	$shop = $com->PullElement( $shop );

$taxo = $com->CalcVatTax( $shop );
$taxa = $com->isTaxAttached();

foreach ( $items as $key => $count ) {

	// key is Item_code
	// count is int ( how many items ? ) || count = item_array();

	$item = $count ;

	if( is_string( $item ) )

	 	$item = $com->PullElementExt( $target , $item );

	if( ! is_array( $item ) && is_string( $key ) ) 

		$item = $com->PullElementExt( $target , $key );
	
	if( ! is_array( $item ) || empty( $item ) ){
	    
	    //file_put_contents( TPath_Root . TPath_DS . "check.txt" , $this->JSON_array_to_str( $items ) . "\n\n" . $key . "\n\n" . $this->JSON_array_to_str( $count ) );

		return $this->Message( "item Not Found" );
		
	} if( array_key_exists( "iVirtual" , $item ) && $item[ "iVirtual" ] )

		$virtualCount++ ;

	if( isset( $item[ "purchase_count" ] ) )

		$count = $item[ "purchase_count" ] ;

	if( is_array( $count ) || $count <= 0 ) $count = 1 ;

	if( $count <= 0 )
		continue ;

	$item[ "purchase_count" ] = $count ;

	$totalCount += $count ;

	$totalItems += 1 ;

	$divide = 1 ;

	// Change For 'MarketPlaceMode'
	if( $this->calcForMarketPlaceApp() && isset( $item[ "iCount" ] )
	    && ! isset( $item[ 'isSellByWeight' ] ) )
		$divide = $item[ "iCount" ] ;

	if( $divide == 0 )
		$divide = 1 ;

	if( isset( $item[ "iWeight" ] ) )

		$totalWeight += ( $item[ "iWeight" ] / $divide ) * $count ;

	// Price After Considering Off(s) By Systems And Parent Items
	$price = $this->SilentCall( $target , "user" , 
		"purchase" , "CalcRealPrice" , [ "item" => $item ] );
	
	if( $price === false ){ // Not Founded | Not Prchasable(Part Of Sth Bigger ?) 

		$price = $this->Translate( "Item is Not Purchasable" );

		return $this->Message( $price . " : " . $item["title"] );

	} if( $price === true ){ // Already Purchased

		$price = $this->Translate( "Item is Already Purchased" );

		return $this->Message( $price . " : " . $item["title"] );

	} // Calc Profit Accoding To Cloning System
	$itemDisc = isset( $item['discussion'] ) ? $item['discussion'] : null ;
	
	// Add 'MarketPlaceMode' Precentage To Price
	if( $item["discussion"] === "_LastMinuteChanges_" ){

		// Ignore !

	} else if( $this->calcForMarketPlaceApp() ){ 
	    
	    // New SubItem Price
	    $price = $price / $divide ;

		// Changing Initial-Price To SubItem Initial-Price
		$item[ "initial_price" ] = $price ; // ( 300$ / 6 ) = 50$
		
		// Profit According To 'MarketPlaceApp' Raise Precentage
		$newPrice = MarketsHandler::CalcMarketPlacePrice( $itemDisc , $price , $shop , $com );
		
		// Add To Total Profit =)
		$totalProfit += $newPrice - $price ; // ( +27$ / 6 )

        $price = $newPrice ;
	
	} else if( isset( $item[ "initial_price" ] ) ) { // Add VAT Tax !

		$profit = $price - $item[ "initial_price" ] ;

		$totalProfit += $profit ; 

		if( $taxa ){ // Add Vat Tax
			
			// Item Tax Percentage
			$taxp = $com->CalcItemTax( $shop , $item , $taxo );
		
			// Calc And Add VAT Tax
			$price += ( $profit * $taxp ) / 100 ;

		}

	} $item[ "real_price" ] = $price ;
	
	$price = $price * $count ; // Price * how_many ( 55$ * 6 = 330$ )

	// Original Entered Price ( actual > price )
	$actual = $com->GetItemPrice( $item ) ; 

	if( $item["discussion"] === "_LastMinuteChanges_" ){

		// Ignore !

	} else if( $this->calcForMarketPlaceApp() ){ 

	    // New SubItem Price
	    $actual = $actual / $divide ;

		// Changing Initial-Price To SubItem Initial-Price
		//$item[ "initial_price" ] = $actual ; // ( 300$ / 6 ) = 50$
		
		// Profit According To 'MarketPlaceApp' Raise Precentage
		$newPrice = MarketsHandler::CalcMarketPlacePrice( $itemDisc , $actual , $shop , $com );
		
		// Add To Total Profit =)
		//$totalProfit += $newPrice - $actual ; // ( +27$ / 6 )

        $actual = $newPrice ;
		
	} else if( isset( $item[ "initial_price" ] ) ) { // Add VAT Tax !

		$profit = $actual - $item[ "initial_price" ] ;

		// Not For Actual
		//$totalProfit += $profit ; 

		if( $taxa ){ // Add Vat Tax
			
			// Item Tax Percentage
			$taxp = $com->CalcItemTax( $shop , $item , $taxo );
		
			// Calc And Add VAT Tax
			$actual += ( $profit * $taxp ) / 100 ;

		}

	} $item[ "actual_price" ] = $actual ;

	$actual = $actual * $count ;
	
	$actualTotal += $actual ;

	$internalOff += $actual - $price ;

	if( $item["discussion"] === "_LastMinuteChanges_" ){

		// Do Nothing : No Coupon For _LastMinuteChanges_ Items

	} else {

		// Custom Coupon For This Item
		// Find Out if Coupon Works On This Item
		$itemCoupon = is_array( $cTargets ) && isset( $cTargets[ $item[ "code" ] ] ) ? 
			$cTargets[ $item[ "code" ] ] : null ;

		if( $itemCoupon && is_array( $itemCoupon ) ){

			$Off = $com->CalcCouponDiscount( $itemCoupon , $price / $count );

			$item[ "coupon_price" ] = $Off ;

			// Real Paying Price
			$item[ "pay_price" ] = $item[ "real_price" ] - $Off ;

			$Off = $Off * $count ;

			$couponOff += $Off ;

			$price = $price - $Off ;

		} else $item[ "pay_price" ] = $item[ "real_price" ];

	} $totalPrice += $price ;

	$items[ $key ] = $item ;

} if( $this->calcForMarketPlaceApp() ){
    
	$totalPrice  = $this->FilterPrice( null , $totalPrice );
	$actualTotal = $this->FilterPrice( null , $actualTotal );
	$internalOff = $this->FilterPrice( null , $internalOff );
    
} $this->input->totalProfit = $totalProfit ;

//file_put_contents( TPath_Root . TPath_DS . "check.txt" , $this->JSON_array_to_str([ $items , $totalPrice , $actualTotal , $internalOff , 
//	$couponOff , $virtualCount , $totalItems , $totalCount , $totalWeight ]) );
//file_put_contents( TPath_Root . TPath_DS . "check.txt" , $this->JSON_array_to_str( $shop ) );

return [ $items , $totalPrice , $actualTotal , $internalOff , 
	$couponOff , $virtualCount , $totalItems , $totalCount , $totalWeight ]; ?>