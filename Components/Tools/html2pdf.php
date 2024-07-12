<?php defined( 'Fallon_Root' ) or die( 'Access Denied : ' . basename( __FILE__ ) );
$html = isset( $html ) ? $html : $this->input->pdf_html ;
$name = isset( $name ) ? $name : $this->input->pdf_name ;
$name = $name ? $name : "pdf" . time() . ".pdf" ;
if( ! $html || empty( $html ) )
	return false ;

// Import mPDF Library
Ted\Import( "libraries.mpdf.vendor.autoload" , Fallon_Root );

// clean slate
@ob_clean();

// Create Page setup
$setup = [90,200] ;
if( $this->input->pdf_landscape )
	$setup = [90,200] ;

if( stristr( $html , "بارکد") ){
	$setup = 'A4-P' ;
	$pdfMarginTop = 2 ;
	$pdfMarginRight = 10 ;
	$pdfMarginBottom = 2 ;
	$pdfMarginLeft = 10 ;
}

// Set Page Margins
$tm = isset( $pdfMarginTop ) ? $pdfMarginTop : $this->input->pdf_topMargin ;
$tm = $tm >= 1 ? $tm : 1 ; 
$rm = isset( $pdfMarginRight ) ? $pdfMarginRight : $this->input->pdf_rightMargin ;
$rm = $rm >= 1 ? $rm : 1 ; 
$bm = isset( $pdfMarginBottom ) ? $pdfMarginBottom : $this->input->pdf_bottomMargin ;
$bm = $bm >= 1 ? $bm : 1 ; 
$lm = isset( $pdfMarginLeft ) ? $pdfMarginLeft : $this->input->pdf_leftMargin ;
$lm = $lm >= 1 ? $lm : 1 ; 

//function mPDF($mode='',$format='A4',$default_font_size=0,
	//$default_font='',$mgl=15,$mgr=15,$mgt=16,$mgb=16,$mgh=9,$mgf=9, $orientation='P')
$mpdf = new mPDF( '' , $setup , 0 , '' , $lm , $rm , $tm , $bm );

// Prepare $html
$html = stripcslashes( $html );
$html = str_ireplace( "bnazanin", "xbriyaz", $html );
$html = str_ireplace( "b nazanin", "xbriyaz", $html );

// Write some HTML code:
if( $this->input->pdf_2cols ){
	$mpdf->SetColumns( 2 , 'J', 8 );
	$mpdf->WriteHTML( $html );
	$mpdf->WriteHTML( "<columnbreak />" );
	$mpdf->WriteHTML( $html );
} else $mpdf->WriteHTML( $html );

// Output a PDF file directly to the browser
if( isset( $save_name ) ) {// Save Mode !

	$save_name = TPath_Fallon_PDF . TPath_DS . $save_name;

	$mpdf->Output( $save_name , 'F' );

	$this->Response([ "message" => "Save Successful" , 
		"link" => $this->WebLink( $save_name ) ]);

	return true ;

} else $mpdf->Output( $name , 'D' ); // Download Mode
@ob_flush();