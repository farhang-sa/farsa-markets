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
    
    $ism = "Manager/Owner Level Access Required" ;

	$this->Response([ "Message" => $ism , "failed" => 1 ]);

	return false ;
	
} // Now Edit Each Product!

$edits = $this->input->sync_items ;

if( ! $edits || empty( $edits ) ){
    
	$this->Response([ "Message" => "No Sync Data Received" , "failed" => 1 ]);

	return false ;
	
} $products = "";

foreach( $edits as $itemCode => $change )
    
    $products .= "'{$itemCode}' ,";
    
$products = trim( $products , ' ,' );

$products = $this->Data()->LoadRowsArray( 'commerce' , [ '*' ] , [ "`code` IN ( $products )" ] );

if( is_array( $products ) && isset( $products[ 'code' ] ) )

    $products = array( $products );
    
$countChange = array();

if( is_array( $products ) ) foreach( $products as $k => $v ){
    
    unset($products[$k]);
    
    if( ! is_array( $v ) || ! isset( $v['code'] ) )
        continue ;
        
    $k = $v['code'] ;
    
    $v[ "uCount" ] += $edits[ $k ]; // + : add | - : sub
    
    $Args = [ "View" => "create-desc" , "Product" => $v , 
        "Where" => [ 'code' => $k  , 'writer' => $v["writer"] ] ];

    $update = $this->SilentCall( "commerce" , "managers" , "products" , "intel" , $Args ) ;
    
    if ( $update ){ // OnChange ( price | parent ) do Change Parent(s)
    
    	//$this->SilentCall( 'commerce' , 'Information' , 
    	//    'build' , [ 'shop' => $v[ 'discussion' ] ] );
    
    	if( isset( $v[ "uCount" ] ) ) // Edit Product
    		$this->Data()->UpdateIntoTable( 'commerce_counts' , 
    			[ 'counter' => $v[ "uCount" ] ] , 
    			[ 'discussion' => $v["discussion"] , 'category' => $v[ 'code' ] ]);
    			
    } $products[ $k ] = $v ;
    
} $this->Response([ "message" => "Done Syncing" , "products" => $products ]);

return $edits ; ?>