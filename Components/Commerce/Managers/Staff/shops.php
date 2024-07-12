<?php defined( 'Fallon_Root' ) or die( 'Access Denied : ' . basename( __FILE__ ) ) ;

if ( ! $this->User( "login" ) ) {

	$this->Response(["message" => $this->MsgNotLogedinAlert() , "failed" => 1 ]);

	return false ;

} $shops = $this->User( 'code' );

$shops = $this->COMMERCE()->PullStaffShops( $shops );

if( empty( $shops ) ){
    
	$this->Response(["message" => "No Shops Found" , "failed" => 1 ]);

	return false ;

} // else :

$shops = isset( $shops[ 'code' ] ) ? [ $shops ] : $shops ;

foreach( $shops as $k => $v ){
    
    unset( $shops[ $k ] );
    
    if( ! is_array( $v ) )
        continue ;
    
    $v = $this->COMMERCE()->CleanData( $v );
        
    $v = array_keys( $v );
    
    foreach( $v as $a )
        if( stristr( $a , '_Agent') !== false )
            $k = str_ireplace( '_Agent' , '' , $a ) ;
        else continue ;
    
    $shops[ $k ] = 1 ;
    
} if( empty( $shops ) ){
    
	$this->Response(["message" => "No Shops Found" , "failed" => 1 ]);

	return false ;

} $this->Response(["message" => "Your Working Shops Found" , "shops" => $shops ]);

return $shops ; ?>