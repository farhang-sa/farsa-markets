<?php defined( 'Fallon_Root' ) or die( 'Access Denied : ' . basename( __FILE__ ) ) ;

if ( ! $this->COMMERCE()->isManager() ){

	$this->Response( [ 'message' => $this->MsgNotManagerAlert() , true ] );

	return false ;

} $shop = isset( $shop ) ? $shop : $this->input->override_shop ;

$data = isset( $data )   ? $data : $this->input->override_data ;

if( is_array( $data ) )

    $data = $this->JSON_array_to_str( $data );
    
if( ! $this->JSON_str_to_array( $data ) ){
    
    $this->Response([ 'message' => 'Data Format Failed' , 'failed' => 1 ]);
    
    return false ;
    
} $file = TPath_Default_AppData . TPath_DS . 'OVERRIDES' . TPath_DS . $shop . ".json" ;

file_put_contents( $file , $data );

$file = $this->AppDataFileUrl( 'OVERRIDES' . TPath_DS . $shop . ".json" );

$this->Response([ 'url' => $file , "success" => 1 ]);

return $data ; ?>