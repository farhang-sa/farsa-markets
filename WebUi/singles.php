<?php defined( 'Fallon_Root' ) or die( 'Access Denied : ' . basename( __FILE__ ) ) ;
$path = implode( "-" , $App->AppHistory() ); 
$login   = $App->User( 'login' );
$profile = $App->User( 'profile' );
$province = $profile && isset( $profile[ 'province' ] ) ? $profile[ 'province' ] : null ;
$city = $profile && isset( $profile[ 'city' ] ) ? $profile[ 'city' ] : null ;
$div = '<div class="col-xs-12 padding2"> &#160; </div>'; 
?><!doctype html>
<html lang="en" class="col-xs-12 padding0 no-js">
	<head>
		<?php print $this->RenderHead(); ?>
        <script src="https://cdn.jsdelivr.net/npm/ol@v9.1.0/dist/ol.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol@v9.1.0/ol.css">
		<style>
		    html, body {
                height: 100%;
            } body::-webkit-scrollbar {
                  display: none;
		    } body {
		        -ms-overflow-style: none;  /* IE and Edge */
                scrollbar-width: none;  /* Firefox */
		    } .hide-scrollbar  , .no-scrollbar {
                overflow-y: scroll;
                -ms-overflow-style: none;  /* IE and Edge */
                scrollbar-width: none;  /* Firefox */
                
                &::-webkit-scrollbar {
                    display: none;
                }
                
                &::-webkit-scrollbar-track {
                    background: transparent;
                }
                
                &::-webkit-scrollbar-thumb {
                    background-color: transparent;
                }
            } .hide-scrollbar::-webkit-scrollbar , .no-scrollbar::-webkit-scrollbar {
                display: none;
            } .btn{ border-radius:0px;}
            .col-fixed , .col-menu-fixed {
                right:5%;
                left:5%;
            }
            
            @media screen and (max-width:450px){ /* XXS */
             
                .col-fixed {
                    right:1.5%;
                    left:1.5%;
                }   
                .col-menu-fixed {
                    right:0.5%;
                    left:0.5%;
                }
            }
            
            @media screen and (max-width:767px){ /* XS */
             
                .col-fixed {
                    right:7.5%;
                    left:7.5%;
                }   
                .col-menu-fixed {
                    right:5%;
                    left:5%;
                }
            }
            
            @media screen and (min-width:992px){ /* SM */
                .col-fixed {
                    right:20%;
                    left:20%;
                }
            }
            
            
            @media screen and (min-width:1200px){ /* MD */
                .col-fixed {
                    right:25%;
                    left:25%;
                }
            }
		</style>
		<script>
            
		    function createCookie(name,value,days) {
                if (days) {
                    var date = new Date();
                    date.setTime(date.getTime() + (180 * 24 * 3600 * 1000));
                    var expires = "; expires=" + date.toGMTString();
                } else {
                    var expires = "";
                } document.cookie = name + "=" + value + expires + "; path=/";
            }
            
            function readCookie(name) {
                var nameEQ = name + "=";
                var ca = document.cookie.split(';');
                for(var i=0;i < ca.length;i++) {
                    var c = ca[i];
                    while (c.charAt(0)==' ') {
                        c = c.substring(1,c.length);
                    } if (c.indexOf(nameEQ) == 0) {
                        return c.substring(nameEQ.length,c.length);
                    }
                } return null;
            }
            
            function deleteCookie(name) {
                createCookie(name,"",-1);
            }
            
            function initProfileEdit( link ){
                if( typeof native_app_integrated != 'undefined' )
                    native_app_integrated.startProfileActivity();
                else window.location = link ;
            }
            
            function LoadCustomViewWithStateChange( link , view ){
                LoadCustomView( link , view );
                window.history.pushState( {'url':link , 'target' : view , 'id' : 'custom-view-with-state-change' },"", link );
            }
            
            $(window).on("popstate", function () {
                // if the state is the page you expect, pull the name and load it.
                if( history.state && history.state.url === window.location.href ){
                    //history.go(-1);
                    return ;
                } else if ( history.state && history.state.id === 'custom-view-with-state-change' ) 
                    LoadCustomView( history.state.url , history.state.target );
              
            });
		</script>
	</head>
	<body style="height:100%;padding:0px;"
	    class='col-xs-12 col-sm-10 col-sm-push-1 col-md-8 col-md-push-2 rtl text-center main-body'>
	    
	    <?php if( $path === "Markets-site-home" ) : ?>
	        
        <div class="col-xs-12 padding0 no-scrollbar" style="height:100%;overflow-y:none;">
	        
	        <?php if( $login ) :  ?>
	        
            <div class='col-xs-12 padding0' style='background-color:white;'>
    	        
    	        <div class='col-xs-4 text-center <?php $Html->EchoUnDir(); ?>'>
    	            
    	            <a href="<?php $Html->EchoLink( '' ); ?>">
    	                <span  style='margin-top:25px;'
    	                    class='glyphicon glyphicon-home'></span>
                    </a> &#160; &#160; &#160; &#160; 
    	            
    	            <a href='<?php $Html->EchoLink( 'web/shopping/view:cart/tag:' . shid ); ?>'>
    	                <span  style='margin-top:25px;'
    	                    class='glyphicon glyphicon-shopping-cart'></span>
                    </a>
    	            
    	        </div>
    	        
    	        <div class='col-xs-8'>
    	            
    	            <b onclick="initProfileEdit( '<?php $Html->EchoLink( 'web/profile' ); ?>' );" style='cursor:pointer;'>
    	                <?php $Html->Call( 'user' , 'profile' , 'id' , [ 'profile' => $profile ] ); ?>
    	            </b>
    	            
    	        </div>
    	        
            </div>
	        
	        <?php endif; ?>
	        
            <div class='col-xs-12 padding0 home-view-holder'>
	        
	        <?php  $Html->Call( 'Apps' , 'ClientsSingleShop' , shid ); ?>
            
            </div>
            
        </div>
        
        <?php elseif( ! $login || ! $profile ) :
	        
	        $Html->Call( 'web' , 'setup' );
	        
	    else : // Show Navigation ?>
	    
	    <div class='col-xs-12 padding0' 
	        style='position:fixed;top:0px;left:0px;height:60px;z-index:99;background-color:white;'>
	        
	        <div class='col-xs-4 text-center <?php $Html->EchoUnDir(); ?>'>
	            
	            <?php if( $path !== "Markets-site-home" ) : ?>
	            
	            <a href='<?php $Html->EchoLink( '' ); ?>'>
	                <span  style='margin-top:18px;'
	                    class='glyphicon glyphicon-home'></span>
                </a>
	            
	             &#160; &#160; 
	            
	            <?php endif; $gold = $App->User( 'gold' );
	            $gold = $gold > 0 ? $gold : 0 ; ?>
	            <a href='#' onclick='alert( "<?php $Html->pTranslate( "Your Credit" ) ; print " : " . $gold . ' ' ; $Html->pTranslate( 'toman' ); ?>" );'>
	                <span  style='margin-top:25px;'
	                    class='glyphicon glyphicon-credit-card'></span>
                </a>
	            
	        </div>
	        
	        <div class='col-xs-8'>
	            
	            <b onclick="initProfileEdit( '<?php $Html->EchoLink( 'web/profile' ); ?>' );" style='cursor:pointer;'>
	                <?php $Html->Call( 'user' , 'profile' , 'id' , [ 'profile' => $profile ] ); ?>
	            </b>
	            
	        </div>
	        
        </div>
	    
        <?php // Show Application Home
        $App->input->shop = shid ;
        $App->input->tag = shid ; ?>
		
		<div class="col-xs-12 content-box align_center padding0" style='margin-top:60px;min-height:90%;'>

			<div class="col-xs-12 col-sm-10 col-sm-push-1 col-md-10 col-md-push-1 content padding0" id="home" style='height:100%;'>
				
				<?php print $Site->RenderComponent(); ?>
				
			</div>

		</div>
		
		<?php endif ; ?>
	
		<?php print $this->RenderBody(); ?>
	
	</body>
</html>