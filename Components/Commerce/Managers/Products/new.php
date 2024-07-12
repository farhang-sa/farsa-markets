<?php defined( 'Fallon_Root' ) or die( 'Access Denied : ' . basename( __FILE__ ) ) ;

if ( ! $this->COMMERCE()->isManager() ){

    $this->Response( [ 'message' => $this->MsgNotManagerAlert() , true ] );

    return false ;

} $error = "All Good !" ;

if( ! isset( $Data[ "code"] ) ) 
    
    $Data[ "code" ]     =  $this->Data()->CreateUserCode( "pr" ) ;

else {

    if( ! is_string( $Data[ "code"] ) ) 

        $Data[ "code" ] =  $this->Data()->CreateUserCode( "pr" ) ;

    if( strlen( $Data[ "code" ] ) == 0 )

        $Data[ "code" ] =  $this->Data()->CreateUserCode( "pr" ) ;

} $Data = $this->SilentCall( "post" , "check_send" , 
    [ "Data"     => "new-product" , 
    "target" => "commerce" ,
    "Code"   => $Data[ "code" ] ,
    "control" => array( "shop" , "parent" ) ]);

if ( empty( $Data ) ){

    $this->Response([ 'message' => $this->Message() ]);

    return false ;

} $Data = $this->SilentCall( "post" , "check_files" , 
    [ "Data"        => $Data , 
    "check_access"  => true ,
    "prefix"        => $Data["shop"] . "__" . $Data[ "code" ] . "__" ,
    "target"        => "commerce" ,
    "poster"        => "new-product-poster" ,
    "files"         => "new-product-files" , 
    "secure_files"  => "new-product-secure-files" ]);

if ( empty( $Data ) ){

    $this->Response([ 'message' => $this->Message() ]);

    return false ;

}  // Check if User IS Owner Of Season
$ShopOldData = $this->COMMERCE()->LoadShopJSON( $Data[ "shop" ] ) ;

if ( ! $ShopOldData ){

    $error = "Shop Intel Not Founded" ;

    $this->Response( [ 'message' => $error ] );

    return false ;

} else if( ! $this->UAC( $ShopOldData["writer"] ) ){

    $this->Response( [ 'message' => $this->NotOwnerAlert() ] );

    return false ;

} // Check New Product In Product_template Course!

$Data['input_type_product'] = 1 ;

if( isset( $Data[ 'input_product_template' ] ) ){ // This Is A Template!

    $Data["parent"]   = ( $Data["parent"] == "0" ) ? $Data["shop"] : $Data["parent"] ;

    $Where = array( 'code' => $this->Data()->CreateUserCode( 'tpr' ) );

    $Where['discussion'] = $Data[ 'shop' ] ;

    $Where['category']   = $Data[ 'parent' ] ;

    $Where[ 'writer' ]   = $ShopOldData[ 'writer' ];

    $find = $this->Data()->LoadRowsArray( "commerce_temps" , 
        [ '*' ] , [ 'discussion' => $Data['shop'] , "`content` LIKE '%" . $Data['title'] . "%'" ] );
    
    if( is_array( $find ) && ! empty( $find ) ){ // Already There!
    
        $error = $this->Message( "This Product Template Already Exists" ) ;
        
        $this->Response( [ 'failed' => 1 , 'message' =>$error , true ] );

        return false ; 
        
    } // else :

    $Args = [ 'View' => 'create-desc' , 'Product' => $Data , 'Where' => $Where ] ;

    $insert = $this->SilentCall( 'commerce' , 'managers' , 'products' , 'intel' , $Args );

    $this->Response( [ 'insert' => $insert , 'message' =>$error , true ] );

    return $insert ; 

} else if( ! isset( $Data[ 'template' ] ) ){ // This Has No Template!

    $subs = $ShopOldData["subjects"];
    
    $tempShop = null ;
    
    $Where = array( "`content` LIKE '%products_template%'" );
    
    $table = 'commerce_temps' ;
    
    if( is_array( $subs ) ) foreach ($subs as $sub => $nWt ) {
    
        $Where[1] = "`content` LIKE '%{$sub}%'" ;
        
        $tempShop = $this->COMMERCE()->PullShops( $Where );
    
        if( is_array( $tempShop ) && isset( $tempShop[ 'code' ] ) ){
    
            // Find This Product in Template
            $find = $this->Data()->LoadRowsArray( $table , 
                [ '*' ] , [ 'discussion' => $Data['shop'] , "`content` LIKE '%" . $Data['title'] . "%'" ] );
    
            if( is_array( $find ) && ! empty( $find ) ){
                
                if( ! isset( $find[ 'code' ] ) )
                
                    $find = array_pop( $find );
                    
                $Data[ 'template' ] = $find[ 'code' ];
    
                continue ; // Already There!
                
            }
    
            // Add This Product To Template!
            $sub = $Data[ 'code' ] ;
            
            $Data[ 'code' ] = $this->Data()->CreateUserCode( 'tpr' ) ;
    
            $nWt = array( 'code' => $Data['code'] );
    
            $nWt['discussion'] = $tempShop[ 'code' ];
    
            $nWt['category'] = $Data[ 'parent' ] ;
    
            $nWt[ 'writer' ] = $tempShop['writer'];

            $Data[ 'input_product_template' ] = 1 ;
    
            $this->COMMERCE()->ExecuteDataActionsUnlimited( 
                $table , array() , $nWt , $Data , false );
                
            $Data[ 'template' ] = $Data[ 'code' ];
                
            $Data[ 'code' ] = $sub ;

            unset( $Data['input_product_template'] );
    
        }
    
    } 
    
} // resume :

$Data["parent"]   = ( $Data["parent"] == "0" ) ? $Data["shop"] : $Data["parent"] ;

$Data[ "status" ] = "0" ;

$Data[ "view" ] = "0" ;

$Data[ "rank" ] = "0" ;

$Data[ "like" ] = "0" ;

$Data[ "hate" ] = "0" ;

$Data[ "down" ] = "0" ;

$Where = array();

$Where[ "code" ]     = $Data["code"] ;

$Where['discussion'] = $Data[ 'shop' ] ;

$Where['category']   = $Data[ 'parent' ] ;

$Where[ "writer" ]   = $ShopOldData["writer"];

$Args = [ "View" => "create-desc" , 'Product' => $Data , "Where" => $Where ] ;

$insert = $this->SilentCall( "commerce" , "managers" , "products" , "intel" , $Args );

if ( $insert ) {

    $this->SilentCall( "commerce" , "Information" , "build" , 
        [ "shop" => $Data["shop"] ] );

    if( isset( $Data[ "uCount" ] ) ) // Edit Product
        $this->Data()->ChangeCount( 'commerce_counts' , 
            [ 'discussion' => $Data["shop"] , 'category' => $Data[ 'code' ] ] , 
            [ 'counter' => $Data[ "uCount" ] ] , 0 );

} $this->Response( [ "insert" => $insert , "message" =>$error , true ] );

return $insert ; ?>