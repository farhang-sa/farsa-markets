<?php defined( 'Fallon_Root' ) or die( 'Access Denied : ' . basename( __FILE__ ) ) ;
$path = implode( "-" , $App->AppHistory() ); ?><!doctype html>
<html lang="en" class="no-js">
	<head>
		<?php print $this->RenderHead(); ?>
        <script src="https://cdn.jsdelivr.net/npm/ol@v9.1.0/dist/ol.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol@v9.1.0/ol.css">
	</head>
	<body class='col-xs-12 <?php $Html->EchoDir() ; ?>'>

		<?php if( $path == "Markets-site-index" ) : ?>

		<article class="col-xs-12 col-sm-10 col-sm-push-1 content-box text-center"
			style="padding:15px;">
			
			<?php $Html->Call( "Site" , "index" ) ; ?>
		
		</article>

		<?php else : ?>

		<?php if ( stristr( $path , "Markets-installation" ) === false ) : ?>

		<div class="col-xs-12 padding0 header-box align_center">
		
		<?php $Html->Call( 'Site' , "Navigation" ); ?>
		
		</div>

		<?php endif ; ?>

		<section class="col-xs-12 content-box align_center" style="margin-top:80px;">

			<div class="col-xs-12 col-sm-10 col-sm-push-1 content" id="home">
				
				<?php print $Site->RenderComponent(); ?>
				
			</div>

		</section>

		<div class="col-xs-12 padding0 footer-box align_center">
		
		<?php $Html->Call( 'Site' , 'Footer' ); ?>
		
		</div>
	
		<?php print $this->RenderBody(); ?>

		<?php endif ; ?>
	
	</body>
</html>