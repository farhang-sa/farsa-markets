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
                }
                document.cookie = name + "=" + value + expires + "; path=/";
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
	    
	    <?php if( ! $login || ! $profile ):
	        
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
	    
	    <?php if( $path == "Markets-site-home" ) : // Show MainPage ?>

		<article class="col-xs-12 col-sm-10 col-sm-push-1 col-md-8 col-md-push-2 content-box text-center"
			style="padding:5px;margin-top:60px;">
		    
		    <?php $settings = $App->SYSTEM()->PullItems( "system" , "Clients" , "Settings" );
		    $settings = $settings[ "settings" ];
		    
		    // Messages
		    $messages = "markets_clients_alarm_messages" ;
		    $msg = isset( $settings[ $messages ] ) ? $settings[ $messages ] : "" ;
		    if( isset( $settings[ $messages . '_' . $province . '_' . $city ] ) )
		        $msg .= "\n" . $settings[ $messages . '_' . $province . '_' . $city ];
		        
	        $messages = 'markets_clients_main_page_definition';
		    //$home = $settings[ $messages ];
		    $home = '' ;
		    if( isset( $settings[ $messages . '_' . $province . '_' . $city ] ) )
		        $home = $settings[ $messages . '_' . $province . '_' . $city ];
		    $home = $App->JSON_str_to_array( $home ); ?>
			
			<div class='col-xs-12 padding5'>
			    
			    <?php if( ! empty( $msg ) ) :
		        $ex = explode( "\n" , $msg );
		        if( ! empty( $ex ) ) foreach( $ex as $msg ):
		            if( empty( $msg ) || stristr( $msg , '|' ) === false )
		                continue ;
		            $msg = explode( "|" , $msg );
		            $Html->Call( 'web' , 'views' , 'view_message' , [ 'message' => $msg ] );
		            print $div ;
		           
		        endforeach ; endif; ?>
                
                <div class="col-xs-12 padding10">
                    
                    <span class="col-xs-12 padding0">
                    <?php $shop = [ "`content` LIKE '%{$province}%{$city}%'" 
                        , "`content` LIKE '%main_shop%'" ];
                    $shop = $App->COMMERCE()->PullShops( $shop );
                    if( $shop ): ?>
                        
                        <a href='<?php $Html->EchoLink( 'web/shopping/tag:main_shop' ); ?>'>
                        <img src='<?php print $App->GetMainShopImageUrl(); ?>'
                            class="img img-thumbnail img-round" />
                        </a>
                        
                    <?php endif; ?>
                    </span>
                    
                </div>
                
                <?php print $div ; ?>
                
                <?php if( ! empty( $home ) ) : ?>
                <span class="col-xs-12 padding10">
                    
                    <h5 style="margin:5px;padding5px;text-align:center;">
                        <?php $Html->pTranslate( 'Express : Delivery And Payment By Shops' ); ?>
                    </h5>
                    
                </span>
                <div class="col-xs-12 padding10">
                    <?php foreach( $home as $row => $data ): 
                    $data = array_values( $data );
                    if( count( $data ) == 4 ): ?>
                    <div class="col-xs-12 padding0">
                        <div class="col-xs-3 padding5">
                            <?php if( $data[0]['tag'] && $data[0]['tag'] !== "null" ) : ?>
                            <a href='<?php $Html->EchoLink( 'web/shopping/tag:' . $data[0]['tag'] ); ?>'>
                            <img src='<?php print $data[0][ 'poster' ]; ?>'
                                class='img img-thumbnail img-round padding10' />
                            <br/>
                            <b><?php print $data[0]['title'];?></b>
                            </a>
                            <?php endif; ?>
                        </div>
                        <div class="col-xs-3 padding5">
                            <?php if( $data[1]['tag'] && $data[1]['tag'] !== "null" ) : ?>
                            <a href='<?php $Html->EchoLink( 'web/shopping/tag:' . $data[1]['tag'] ); ?>'>
                            <img src='<?php print $data[1][ 'poster' ]; ?>'
                                class='img img-thumbnail img-round padding10' />
                            <br/>
                            <b><?php print $data[1]['title'];?></b>
                            </a>
                            <?php endif; ?>
                        </div>
                        <div class="col-xs-3 padding5">
                            <?php if( $data[2]['tag'] && $data[2]['tag'] !== "null" ) : ?>
                            <a href='<?php $Html->EchoLink( 'web/shopping/tag:' . $data[2]['tag'] ); ?>'>
                            <img src='<?php print $data[2][ 'poster' ]; ?>'
                                class='img img-thumbnail img-round padding10' />
                            <br/>
                            <b><?php print $data[2]['title'];?></b>
                            </a>
                            <?php endif; ?>
                        </div>
                        <div class="col-xs-3 padding5">
                            <?php if( $data[3]['tag'] && $data[3]['tag'] !== "null" ) : ?>
                            <a href='<?php $Html->EchoLink( 'web/shopping/tag:' . $data[3]['tag'] ); ?>'>
                            <img src='<?php print $data[3][ 'poster' ]; ?>'
                                class='img img-thumbnail img-round padding10' />
                            <br/>
                            <b><?php print $data[3]['title'];?></b>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php print $div; ?>
                    <?php elseif( count( $data ) == 3 ): ?>
                    <div class="col-xs-12 padding0">
                        <div class="col-xs-4 padding5">
                            <?php if( $data[0]['tag'] && $data[0]['tag'] !== "null" ) : ?>
                            <a href='<?php $Html->EchoLink( 'web/shopping/tag:' . $data[0]['tag'] ); ?>'>
                            <img src='<?php print $data[0][ 'poster' ]; ?>'
                                class='img img-thumbnail img-round padding10' />
                            <br/>
                            <b><?php print $data[0]['title'];?></b>
                            </a>
                            <?php endif; ?>
                        </div>
                        <div class="col-xs-4 padding5">
                            <?php if( $data[1]['tag'] && $data[1]['tag'] !== "null" ) : ?>
                            <a href='<?php $Html->EchoLink( 'web/shopping/tag:' . $data[1]['tag'] ); ?>'>
                            <img src='<?php print $data[1][ 'poster' ]; ?>'
                                class='img img-thumbnail img-round padding10' />
                            <br/>
                            <b><?php print $data[1]['title'];?></b>
                            </a>
                            <?php endif; ?>
                        </div>
                        <div class="col-xs-4 padding5">
                            <?php if( $data[2]['tag'] && $data[2]['tag'] !== "null" ) : ?>
                            <a href='<?php $Html->EchoLink( 'web/shopping/tag:' . $data[2]['tag'] ); ?>'>
                            <img src='<?php print $data[2][ 'poster' ]; ?>'
                                class='img img-thumbnail img-round padding10' />
                            <br/>
                            <b><?php print $data[2]['title'];?></b>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php print $div; ?>
                    <?php elseif( count( $data ) == 2 ): ?>
                    <div class="col-xs-12 padding0">
                        <div class="col-xs-6 padding5">
                            <?php if( $data[0]['tag'] && $data[0]['tag'] !== "null" ) : ?>
                            <a href='<?php $Html->EchoLink( 'web/shopping/tag:' . $data[0]['tag'] ); ?>'>
                            <img src='<?php print $data[0][ 'poster' ]; ?>'
                                class='img img-thumbnail img-round padding10' />
                            <br/>
                            <b><?php print $data[0]['title'];?></b>
                            </a>
                            <?php endif; ?>
                        </div>
                        <div class="col-xs-6 padding5">
                            <?php if( $data[1]['tag'] && $data[1]['tag'] !== "null" ) : ?>
                            <a href='<?php $Html->EchoLink( 'web/shopping/tag:' . $data[1]['tag'] ); ?>'>
                            <img src='<?php print $data[1][ 'poster' ]; ?>'
                                class='img img-thumbnail img-round padding10' />
                            <br/>
                            <b><?php print $data[1]['title'];?></b>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php print $div; ?>
                    <?php else : ?>
                    <div class="col-xs-12 padding0">
                        <?php if( $data[0]['tag'] && $data[0]['tag'] !== "null" ) : ?>
                        <div class="col-xs-12 padding5">
                            <a href='<?php $Html->EchoLink( 'web/shopping/tag:' . $data[0]['tag'] ); ?>'>
                            <img src='<?php print $data[0][ 'poster' ]; ?>'
                                class='img img-thumbnail img-round padding10' />
                            <br/>
                            <b><?php print $data[0]['title'];?></b>
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php print $div; ?>
                    <?php endif; endforeach; ?>
                </div>
                <?php endif; ?>
			    
			</div>
		
		</article>
		
		<div class="col-xs-12 padding5">
		    
		    Powered By Markets 2024
		    
		</div>
		
		<?php else : // Show Component ?>

		<div class="col-xs-12 content-box align_center padding0" style='margin-top:60px;min-height:90%;'>

			<div class="col-xs-12 col-sm-10 col-sm-push-1 col-md-10 col-md-push-1 content padding0" id="home" style='height:100%;'>
				
				<?php print $Site->RenderComponent(); ?>
				
			</div>

		</div>
		
		<?php endif; endif ; ?>
	
		<?php print $this->RenderBody(); ?>
	
	</body>
</html>