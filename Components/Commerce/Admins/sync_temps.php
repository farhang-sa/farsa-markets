<?php defined( 'Fallon_Root' ) or die( 'Access Denied : ' . basename(  __FILE__ ) ) ;

if ( ! $this->User( "admin" ) ) {

	$this->Response(["message" => $this->Message( $this->MsgNotAdminAlert() ) , true ]);

	return false ;

} $file = Markets_Root . TPath_DS . "temps.json" ;

$file = @fopen( $file ,"w" );

$cPage = 0 ;

$cCount = 2000 ;

while( true ) {

	$cPage++ ;

	$nPage = ( $cPage - 1 ) * $cCount ;

	$temps = $this->COMMERCE()->PullItems( 
		'commerce_temps' , null , null , [ '1=1' ] , $cCount , $nPage );

	if( empty( $temps ) || ! is_array( $temps ) ) break ;

	if( is_array( $temps ) && isset( $temps[ 'code' ] ) )
		$temps = array( $temps );

	if( is_array( $temps ) ) foreach ($temps as $key => $value) {

		unset( $temps[ $key ] );
		unset( $value["status"] , $value["view"] , $value["rank"] );
		unset( $value["like"] , $value["hate"] , $value["down"] );
		unset( $value["shared_from"] , $value["shared_till"] , $value["shared_days"] );

		$temps[ $value[ 'code' ] ] = $value ;

	} $temps = $this->JSON_array_to_str( $temps ) . "\n";

	fwrite( $file , $temps );

} @fclose( $file );

$this->Response([ "Done" => "YES" ]); ?>