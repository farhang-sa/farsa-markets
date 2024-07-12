<?php defined( 'Fallon_Root' ) or die( 'Access Denied : ' . basename(  __FILE__ ) ) ; 

Ted\Import( 'HtmlInterface' , GapsManager_Root );

class Markets_Html_Interface extends Gaps_Html_Interface { 

	private static $SiteNavActive = false ;

	public function isActive( $page ){

		if ( self::$SiteNavActive ) return false ;

		if ( $this->App()->input->{$page} != false ) {

			print "current-menu-item" ;
			
			self::$SiteNavActive = true ;

		}

	}

	public function GetAlignedIMG( $src ){
	    
	    $html = "<div class='width:100%;'>";
	    $html .= "<img src='{$src}' style='float:left;' />";
	    $html .= "</div>";
	    return $html ;
	    
	}

}