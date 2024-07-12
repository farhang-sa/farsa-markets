<?php defined( 'Fallon_Root' ) or die( 'Access Denied : ' . basename( __FILE__ ) ) ;

if ( ! $this->User( "login" ) ) {

	$this->Response(["message" => $this->MsgNotLogedinAlert() , "failed" => 1 ]);

	return false ;

} $coupon = isset( $coupon ) ? $coupon : $this->input->facture_coupon ;

$CartType = isset( $CartType ) ? $CartType : $this->input->CartName ;
$CartType = $CartType ? $CartType : $this->COMMERCE()->CartTypeDefault ;

$MyCart =  isset( $MyCart ) && is_array( $MyCart ) ? $MyCart : 
	$this->SilentCall( "Commerce" , "user" , "cart" , [ "CartType" => $CartType ] );

// Check Shop
$shop = isset( $shop ) && is_array( $shop ) ? 
	$shop : $this->COMMERCE()->PullElement( $CartType );

//file_put_contents( TPath_Root . TPath_DS . "check.txt" , $this->JSON_array_to_str( $shop ) . "\n\n" . $CartType );
	
if( ! empty( $shop ) && isset( $shop[ 'code' ] ) ){

	if( isset( $shop[ 'shop_closed' ] ) ){

		$shop = $this->Message( 'shop_closed : Shop is Closed!' );

		$this->Response(["message" => $shop , "shop_closed" => 1 , "failed" => 1 ]);

		return -1 ;

	} $ctList = " 'Saved' , 'Received' , 'Preparing' ";

	$maxOrder = isset( $shop[ 'max_orders' ] ) ? $shop[ 'max_orders' ] : 0 ;

	$countOrder = strtotime( 'today' );

	if( $maxOrder > 0 )
		$countOrder = $this->Data()->CountItems( 'commerce_factures' , [ 'code' => 'items' ] , 
		[ "`discussion` = '{$CartType}' AND `edit` >= '{$countOrder}' AND `category` IN({$ctList})" ] );
	else $countOrder = -1 ;

	if( $maxOrder > 0 && $countOrder >= $maxOrder ){

		$shop = $this->Message( 'max_orders : Too Many Orders! Please Try Tomorrow' );

		$this->Response(["message" => $shop , "max_orders" => 1 , "failed" => 1 ]);

		return -2 ;

	}

} // Find Cart Item Details
$Details = $this->SilentCall( "commerce" ,"user" ,"factures" ,
	"details" , [ "items" => $MyCart , "coupon" => $coupon , 'shop' => $shop ] );
	
if( ! is_array( $Details ) || empty( $Details ) ) {

	$this->Response(["message" => "Checkout Not OK" , "failed" => 1 ]);

	return false ;

} $allVirtual = $Details[5] == count( $MyCart ) ? true : false ;

if( ! isset( $cart_mode ) ){

	$totalLastMinChanges = 0 ;

	// Add Tax For Virtual Items If Set
	$taxp = $this->COMMERCE()->CalcVatTax( $shop );
	if( $taxp > 0 && ! $this->COMMERCE()->isTaxAttached() ){
		// We have Tax !
		$totalTax = 0 ;
		foreach ( $Details[0] as $key => $value )
			if( is_array( $value ) && isset( $value[ "iVirtual"] ) ){
				$rp = $value[ "pay_price"] ;
				$rc = $value[ "purchase_count"] ;
				$totalTax += ( $rp * $rc ) * ( $taxp / 100 ) ;
			} else if( is_array( $value ) && ! isset( $value[ "iNoVatTax"] )
				&& isset( $value[ 'initial_price' ] ) ){ // This Is Taxable !
				$rc = $value[ "purchase_count"] ;
				$rp = $value[ "pay_price"] - $value[ 'initial_price' ];
				$totalTax += ( $rp * $rc ) * ( $taxp / 100 ) ;
			}

		if( $totalTax >= 10 ){

			$pc = array( "price" => $totalTax , "code" => "TaxCost"  	  , 
				"title" => $this->Translate( "Government issued value added tax" ) , 
				"real_price" => $totalTax , "actual_price" 	=> $totalTax , 
				"coupon_price" => 0   	  , "pay_price" 	=> $totalTax , 
				"purchase_count" => 1 	  , "discussion" 	=> "_LastMinuteChanges_" );

			$MyCart[ "TaxCost" ] = 1 ;

			$Details[0][ "TaxCost" ] = $pc ;

			$totalLastMinChanges += $totalTax ;

		} 
	}

	// Add Postal Charges If Not AllVirtual : NO POSTING !
	/*if( ! $allVirtual ){ 

		$WeightTotal = $Details[8] ;
		
		//foreach ( $Details[0] as $key => $value )
		//	if( is_array( $value ) && isset( $value[ "iWeight"] ) )
		//		$WeightTotal += $value[ "iWeight"] ;

		$PostCost = $this->COMMERCE()->CalcWeightToPostalCost( $WeightTotal );

		$fp = $this->SYSTEM()->Settings( "commerce-free-posting-minumun" , 500000 );

		$pc = array( "price" => $PostCost ,
			"code" => "PostCost" , "title" => $this->Translate("Postal Charges") , 
			"real_price" => $PostCost , "actual_price" 	=> $PostCost , 
			"coupon_price" => 0   	  , "pay_price" 	=> $PostCost , 
			"purchase_count" => 1 	  , "discussion" 	=> "_LastMinuteChanges_" );

		// $Details[1] = total cart price 
		if( $Details[ 1 ] > $fp ){ 
			// if to low-value : charge postal costs

			$MyCart[ "PostCost" ] = 1 ;

			$Details[0][ "PostCost" ] = $pc ;

			$totalLastMinChanges += $PostCost ;

		} 

	} */

	// Add Previous Debts
	$userDebts = $this->SilentCall( 'Commerce' , 'Managers' , 'Debtors' ,
		'list' , [ 'shop' => $CartType , 'user' => $this->User( 'code' ) ] );

	if( $userDebts ){

		$userDebts = array_pop( $userDebts );

		$userDebts = $userDebts[ 'amount' ];

		$pc = array( "price" => $userDebts ,
			"code" => "DebtsCost" , "title" => $this->Translate("Previous Debts") , 
			"real_price" => $userDebts , "actual_price" => $userDebts , 
			"coupon_price" => 0   	  , "pay_price" 	=> $userDebts , 
			"purchase_count" => 1 	  , "discussion" 	=> "_LastMinuteChanges_" );

		$MyCart[ "DebtsCost" ] = 1 ;

		$Details[0][ "DebtsCost" ] = $pc ;

		$totalLastMinChanges += $userDebts ;

	}

	// Add Gifts
	$purchaseGift = isset( $gift ) ? $gift : null ;

	if( $purchaseGift && is_array( $purchaseGift ) ){

		$purchaseGift[ "discussion" ] 	  = "_LastMinuteChanges_" ;

		$MyCart[ $purchaseGift[ "code" ] ]  = 1 ;

		$Details[0][ $purchaseGift[ "code" ] ] = $purchaseGift ;

	} 

	// Add Software Costs :)
	$totalSC = $this->COMMERCE()->Settings( 'software_cost' , 0 );
	$ds 	 = $this->COMMERCE()->Settings( 'software_perc' , 0 );
	if( is_array( $shop ) ){ // Override
	    if( isset( $shop[ 'software_cost' ] ) )
	        $totalSC = $shop[ 'software_cost' ] ;
	    if( isset( $shop[ 'software_perc' ] ) )
	        $ds = $shop[ 'software_perc' ];
	} if( $ds > 0 )
		$totalSC += intval( $Details[2] * $ds / 100 ) ;
	if( is_array( $shop ) && isset( $shop['delivery_calc_system'] ) ){
	    
	    if( $shop['delivery_calc_system'] === 'post' )
	        $this->input->delivery_cost = $this->COMMERCE()->CalcWeightToPostalCost( $Details[8] );
	    //else if( $shop['delivery_calc_system'] === 'tipax' )
	        //$this->input->delivery_cost = 1000 ;
	    
	} if( $this->input->delivery_cost ){
		$ds = $this->input->delivery_cost ;
		/* if( $this->calcForMarketPlaceApp() ){ // For MetrooKala
			$profit = $this->input->totalProfit;
			if( $profit > 0 ){
				if( $profit <= $ds )
					$ds -= $profit / 6 ;
				else $ds -= $profit / 5 ;
			} if( $ds <= 0 )
			    $ds = $this->COMMERCE()->Settings( "software_cost" , 0 ) ;
		} */ $totalSC += $ds ;
	} $totalSC = round( $totalSC , -2 ) + 100 ;

	$pc = array( "price" => $totalSC ,
		"code" => "SoftwareCost" , "title" => $this->Translate("Software Charges And Delivery Fare") , 
		"real_price" => $totalSC , "actual_price" 	=> $totalSC , 
		"coupon_price" => 0   	  , "pay_price" 	=> $totalSC , 
		"purchase_count" => 1 	  , "discussion" 	=> "_LastMinuteChanges_" );

	$MyCart[ "SoftwareCost" ] = 1 ;

	$Details[0][ "SoftwareCost" ] = $pc ;

	$totalLastMinChanges += $totalSC ;

	// ReCalculate Details 
	$Details = $this->SilentCall( "commerce" , "user" , "factures" ,
		"details" , [ "items" => $Details[0] , "coupon" => $coupon , "shop" => $shop ] );

} $Details[5] = $allVirtual ? true : $Details[5] ;

if( isset( $only_items ) )

	return $Details[0] ;

if( isset( $only_cart ) )

	return $MyCart ;

$Details[6] = $MyCart ;

return $Details ; ?>