<?php defined( 'Fallon_Root' ) or die( 'Access Denied : ' . basename(  __FILE__ ) ) ;

Ted\Import( 'HtmlInterface' , GapsManager_Root );

class Markets_holyhtml_Interface extends Gaps_Html_Interface { 

	private static $SiteNavActive = false ;

	public function Respond( $directRespond = true ){

		$HtmlUI = $this->App()->AppInterface( "Html" ) ;

		//Exec The Init/Config Files
		if ( $this->Root === $this->App()->AppRoot() )

			$this->Root = $HtmlUI ? $HtmlUI->Root() : $this->Root ;

		// Execute Init.php For SiteUI
		$this->ExecInitFiles( $this->Root );

		// Execute Init.php For HtmlUI
		$HtmlUI->ExecInitFiles( $HtmlUI->Root() );
		
		$HtmlUI->Connect( false );

		if ( ! Ted\IsCli( ) ) header( 'Content-Type: text/html; charset=UTF-8' );
			
		@ob_start(); // Start Output Buffrer
		@ob_clean(); // Cleaning Output Buffrer

		// Print The Document In Simple Way
		print "<!doctype html>" . PHP_EOL ;
		
		print "<html>" . PHP_EOL ;
		
		print "\t<head>" . PHP_EOL ;
		
			print $this->RenderHead();
			
		print "\t</head>" . PHP_EOL ;
		
		print "\t<body class='TedEngineBody padding10'>" . PHP_EOL ;
		
			print $this->RenderComponent();
			
			print $this->RenderBody();
			
		print "\t</body>" . PHP_EOL ;
		
		print "</html>";	
			
		$Html = ob_get_contents() ;

		@ob_end_clean(); // End Cleaning Output Buffrer
		
		print trim( $Html , " /\\." . PHP_EOL ) ;

		return true ;
	}

}