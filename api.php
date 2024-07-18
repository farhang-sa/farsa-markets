<?php

define( 'debug_mode' , 1 );
define( 'TExec' , microtime( true ) ); // PHP Script Execution Started
define( 'TPath_DS' , DIRECTORY_SEPARATOR );
define( 'TPath_Root' , realpath( __DIR__ ) );
defined( 'TPath_Index' ) OR define( 'TPath_Index' , basename( __FILE__ ) );
define( 'TPath_Base' , dirname ( dirname ( TPath_Root ) ) . TPath_DS . "TedBase_v2" );
define( 'TPath_Fallon' , dirname ( dirname ( TPath_Root ) ) . TPath_DS . "Fallon_DSFramework_v3" );
define( 'TPath_GapsManager' , dirname ( dirname ( TPath_Root ) ) . TPath_DS . "Gaps_v1" );
define( 'TPath_AppRoot' , TPath_Root ) ;
define( 'TPath_Default_AppData' , TPath_AppRoot . TPath_DS . "AppData" );
@date_default_timezone_set( 'UTC' );

// Execute The Pilot Needs
$pilotFile = TPath_Base . TPath_DS . "Load.php" ;
( $pilotFile && include_once( $pilotFile ) ) OR die( "Cannot Load Ted" );

defined( "FallonAppName" ) OR define( "FallonAppName", "Markets" );
Ted\Import( "Framework" , TPath_Fallon ); // For Framework Based Apps
Ted\Import( "Markets" , TPath_AppRoot );

$App = new Markets( TPath_AppRoot , Ted\Intel::GetRoute() );

$App->Initialise();

$App->Respond( );

$App->Finish();

?>