<?php

header( 'Content-type:application/json; charset=UTF-8' );

$Response = array() ;
$Response[ "message" ] = "Update Required" ;
$Response[ "update" ] = true ;
$Response[ "exec" ] = true ;

print json_encode( $Response , JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );

?>