<?php defined( 'Fallon_Root' ) or die( 'Access Denied : ' . basename( __FILE__ ) ) ;
$path = implode( "-" , $App->AppHistory() ); 
$login   = $App->User( 'login' );
$profile = $App->User( 'profile' );
$div = '<div class="col-xs-12 padding2"> &#160; </div>'; ?><!doctype html>
<html lang="en" class="col-xs-12 padding0 no-js">
	<head>
		<?php print $this->RenderHead();
        $Html->Call( 'web' , 'head' ); ?>
	</head>
	<body style="height:100%;padding:0px;"
	    class='col-xs-12 col-sm-10 col-sm-push-1 col-md-8 col-md-push-2 rtl text-center main-body'>
	    
	    <?php if( ! $login || ! $profile ): ?>

            <div class="col-xs-12 col-sm-10 col-sm-push-1 col-md-8 col-md-push-2 content-box text-center"
                style="padding:5px;margin-top:60px;">

                <div class="col-xs-12 content padding0">

    	           <?php $Html->Call( 'web' , 'setup' ); ?>

                </div>

            </div>
	        
	    <?php else : 

        // Show Navigation
        $Html->Call( 'web' , 'navigation' , 
            [ 'path' => $path , 'profile' => $profile ] );

        if( $path == "Markets-site-home" ) : // Show MainPage 

            $Html->Call( 'web' , 'home.clients' );

        else : // Show Component ?>

		<div class="col-xs-12 content-box align_center padding0" style='margin-top:60px;min-height:90%;'>

			<div class="col-xs-12 col-sm-10 col-sm-push-1 col-md-10 col-md-push-1 content padding0" id="home" style='height:100%;'>
				
				<?php print $Site->RenderComponent(); ?>
				
			</div>

		</div>
		
		<?php endif; endif ; ?>
	
		<?php print $this->RenderBody(); ?>
	
	</body>
</html>