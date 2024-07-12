<?php defined( 'Fallon_Root' ) or die( 'Access Denied : ' . basename( __FILE__ ) ) ;

if ( ! $this->COMMERCE()->isManager() ){

	$this->Response( [ 'message' => $this->MsgNotManagerAlert() , true ] );

	return false ;

} $shops = $this->COMMERCE()->PullShops( [ 'writer' => $this->User( 'code' ) ] );

if( empty( $shops ) ){
    
	$this->Response( [ 'message' => "You Have No Shops" , "failed" => 1 ] );

	return false ;

} // else :

if( isset( $shops[ 'code' ] ) )
    $shops = array( $shops );

foreach( $shops as $k => $v ){
    
    unset( $shops[ $k ] );
    
    $shops[ $v[ 'code' ] ] = 1 ;
    
} foreach( $shops as $shid => $v ){
    
    $v = $this->COMMERCE()->LoadDataAsArray( 'commerce_factures' , [ "COUNT(`writer`) AS 'COUNTS'" ] , 
        [ 'discussion' => $shid , 'category' => 'Saved' ] ,  null , null , null  );
        
    if( empty( $v ) )
    
        $shops[ $shid ] = "Save:0" ;
        
    else $shops[ $shid ] = "Save:" . array_values( $v )[0];
    
    $v = $this->COMMERCE()->LoadDataAsArray( 'commerce_factures' , [ "COUNT(`writer`) AS 'COUNTS'" ] , 
        [ 'discussion' => $shid , 'category' => 'Received' ] ,  null , null , null  );
        
    if( empty( $v ) )
        
        $shops[ $shid ] .= " | Reciv:0" ;
        
    $shops[ $shid ] .= " | Reciv:" . array_values( $v )[0];
    
    $v = $this->COMMERCE()->LoadDataAsArray( 'commerce_factures' , [ "COUNT(`writer`) AS 'COUNTS'" ] , 
        [ 'discussion' => $shid , 'category' => 'Prepared' ] ,  null , null , null  );
        
    if( empty( $v ) )
        
        $shops[ $shid ] .= " | Prep:0" ;
        
    $shops[ $shid ] .= " | Prep:" . array_values( $v )[0];
    
    $v = $this->COMMERCE()->LoadDataAsArray( 'commerce_factures' , [ "COUNT(`writer`) AS 'COUNTS'" ] , 
        [ 'discussion' => $shid , 'category' => 'Sent' ] ,  null , null , null  );
        
    if( empty( $v ) )
        
        $shops[ $shid ] .= " | Sent:0" ;
        
    $shops[ $shid ] .= " | Sent:" . array_values( $v )[0];
    
} $this->Response( [ 'message' => "Factures Stats Loaded" , "stats" => $shops ] );

return $shops ; ?>