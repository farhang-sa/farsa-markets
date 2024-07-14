var teslaThemes = {

  settings: {

    scripts: [
      /*'js/plugins/queryloader.js',
      'js/plugins/easing.js',
      'js/plugins/sticky.js',
      'js/plugins/viewport.js',
      'js/plugins/magnific-popup.js',
      'js/plugins/bigslide.js',
      'js/plugins/enquire.js',
      'js/plugins/jquery.finger.js',
      'js/plugins/simple-slider.js',
      'js/plugins/knob.js',
      'js/plugins/fitvids.js',
      'js/plugins/masonry.pkgd.min.js',
      'js/plugins/classie.js',
      'js/plugins/imagesloaded.js',
      'js/plugins/AnimOnScroll.js',
      'js/plugins/tubular.js',
      'js/plugins/swipebox.js',
      'js/plugins/scrolld.js',
      'js/plugins/appear.js',*/
    ]

  },
  init: function () {
    this.loadScripts();    
  },

  module: function () {
    this.queryLoader();
    this.twitter();
    this.sticky();
    this.viewport();
    this.modal();
    this.map();
    this.knob();
    this.flickr();
    this.menu();
    this.fitvids();
    this.progressBar();
    this.tubular();
    this.zoomImage();
    this.portfolioFilter();
    this.scrolld();
    this.scrollEffect();
  },

  loadScripts: function () {

    Modernizr.load([
      {
        /*load: [
                '//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js',
              ],*/
        complete: function () {
          /*if ( !window.jQuery ) {
                Modernizr.load('js/jquery.js');
          }*/
          var scripts = [];
          $.each(teslaThemes.settings.scripts,function(index,script){
            scripts[index] = $.getScript(script);
          });
          $.when.apply($,scripts).done(function(){
            teslaThemes.module();
            
          });

        },
      },
     
    ]);

  },
  
  queryLoader: function () {

    $(document).ready(function () {
      $("body").queryLoader2({
        backgroundColor: '#FBFBFB',
        barColor: '#99D9F2',
        percentage: false,
        barHeight: 10,
        minimumTime: 3000,
        overlayId: 'theme-loader',
        onComplete: showContent(),
      });

      //$('#theme-loader').prepend('Some text');

      function showContent() {
        $('#home').addClass('show-content');
         $("body").css('overflow', 'visible');
        teslaThemes.simpleSlider();

        setTimeout(function() {
          teslaThemes.gridEffect();
          
        }, 100);
      }
    });
  },

  sticky: function () {
    if($('.sticky-bar').length) {
      $(".sticky-bar").sticky({topSpacing:0});       
    }
  },

  fitvids: function () {
    var video = $('noscript').text();

    if(video.trim().search('iframe') === 1) {
      $('noscript').parent().append(video);      
    }


    $("#home").fitVids({ customSelector: ""});
  },

  viewport: function () {
     //$(":in-viewport")
     //$(":below-the-fold")
     //$(":above-the-top")
     //$(":left-of-screen")
     //$(":right-of-screen")

    $(window).scroll(function () {
       $('.small-footer:in-viewport').each(function () {
       
     });
    }); 
  },

  modal: function () {

    $('.modal-link').magnificPopup({
      type:'inline',
      midClick: true,
      gallery: {
          enabled: true
        },
      mainClass: 'mfp-fade'
    });

  },

  scrolld: function () {
    $(".main-nav > ul#one-page > li > a, .intro-button").stop(true).on('click', function (e) {
      $(".main-nav > ul#one-page > li").removeClass('current-menu-item');

      if($(this).hasClass('intro-button')) {
        $(".main-nav > ul > li").eq(0).addClass('current-menu-item'); 
      } else {
        $(this).parent().addClass('current-menu-item');        
      }


      e.preventDefault();
      $(this).scrolld({
        scrolldEasing: 'easeOutBack'
      });
    });
  },

  tubular: function () {

    $('.full-video').tubular({
      videoId : 'ssutK1Gei4A',
      start   : 3
    });

  },

  map: function () {

    if(typeof(google) != "undefined") {

      var myLatlng = new google.maps.LatLng(44.2661906,-68.5691898);
      var myOptions = {
          zoom: 16,
          center: myLatlng,
          disableDefaultUI: true,
          panControl: true,
          zoomControl: true,
          scrollwheel: false,
          zoomControlOptions: {
              style: google.maps.ZoomControlStyle.DEFAULT
          },

          mapTypeControl: true,
          mapTypeControlOptions: {
              style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR
          },
          streetViewControl: true,
          mapTypeId: google.maps.MapTypeId.ROADMAP,

          styles:[
              {
                  "featureType": "landscape",
                  "stylers": [
                      {
                          "saturation": -100
                      },
                      {
                          "lightness": 65
                      },
                      {
                          "visibility": "on"
                      }
                  ]
              },
              {
                  "featureType": "poi",
                  "stylers": [
                      {
                          "saturation": -100
                      },
                      {
                          "lightness": 51
                      },
                      {
                          "visibility": "simplified"
                      }
                  ]
              },
              {
                  "featureType": "road.highway",
                  "stylers": [
                      {
                          "saturation": -100
                      },
                      {
                          "visibility": "simplified"
                      }
                  ]
              },
              {
                  "featureType": "road.arterial",
                  "stylers": [
                      {
                          "saturation": -100
                      },
                      {
                          "lightness": 30
                      },
                      {
                          "visibility": "on"
                      }
                  ]
              },
              {
                  "featureType": "road.local",
                  "stylers": [
                      {
                          "saturation": -100
                      },
                      {
                          "lightness": 40
                      },
                      {
                          "visibility": "on"
                      }
                  ]
              },
              {
                  "featureType": "transit",
                  "stylers": [
                      {
                          "saturation": -100
                      },
                      {
                          "visibility": "simplified"
                      }
                  ]
              },
              {
                  "featureType": "administrative.province",
                  "stylers": [
                      {
                          "visibility": "off"
                      }
                  ]
              },
              {
                  "featureType": "water",
                  "elementType": "labels",
                  "stylers": [
                      {
                          "visibility": "on"
                      },
                      {
                          "lightness": -25
                      },
                      {
                          "saturation": -100
                      }
                  ]
              },
              {
                  "featureType": "water",
                  "elementType": "geometry",
                  "stylers": [
                      {
                          "hue": "#ffff00"
                      },
                      {
                          "lightness": -25
                      },
                      {
                          "saturation": -97
                      }
                  ]
              }
          ]
          }
      var map = new google.maps.Map(document.getElementById("map-box"), myOptions);
      var marker = new google.maps.Marker({
          position: myLatlng,
          map: map,
          title:"brooklin"
      });

    }

  },

  flickr: function () {
    $('.flickr-widget').each(function(){
        var stream = $(this),
            stream_userid = stream.attr('data-userid'),
            stream_items = parseInt(stream.attr('data-items'));

        $.getJSON("http://api.flickr.com/services/feeds/photos_public.gne?lang=en-us&format=json&id="+stream_userid+"&jsoncallback=?", function(stream_feed){
            
            for(var i=0;i<stream_items&&i<stream_feed.items.length;i++){
                var stream_function = function(){
                    if(stream_feed.items[i].media.m){
                        var stream_a = $('<a>').addClass('flickr-link').attr('href',stream_feed.items[i].link).attr('target','_blank');
                        var stream_img = $('<img>').addClass('flickr-img').attr('src',stream_feed.items[i].media.m).attr('alt','').each(function(){
                            var t_this = this;
                            var j_this = $(this);
                            var t_loaded_function = function(){
                                stream_a.append(t_this);
                            };
                            var t_loaded_ready = false;
                            var t_loaded_check = function(){
                                if(!t_loaded_ready){
                                    t_loaded_ready = true;
                                    t_loaded_function();
                                }
                            }
                            var t_loaded_status = function(){
                                if(t_this.complete&&j_this.height()!==0)
                                    t_loaded_check();
                            }
                            t_loaded_status();
                            $(this).load(function(){
                                t_loaded_check();
                            });
                        });
                        stream.append($('<li class="col-xs-3 col-md-6">').append(stream_a));
                    }
                }
                stream_function();
            }
        });
    });
  },

  twitter: function () {
    var linkify = function(text) {
        text = text.replace(/(https?:\/\/\S+)/gi, function(s) {
            return '<a href="' + s + '">' + s + '</a>';
        });
        text = text.replace(/(^|)@(\w+)/gi, function(s) {
            return '<a href="http://twitter.com/' + s + '">' + s + '</a>';
        });
        text = text.replace(/(^|)#(\w+)/gi, function(s) {
            return '<a href="http://search.twitter.com/search?q=' + s.replace(/#/, '%23') + '">' + s + '</a>';
        });
        return text;
    };
    $('.twitter-widget').each(function() {
        var t = $(this);
        var t_date_obj = new Date();
        var t_loading = 'Loading tweets..'; //message to display before loading tweets
        var t_container = t.append('<p>' + t_loading + '</p>');
        t.append(t_container);
        var t_user = t.attr('data-user');
        var t_posts = parseInt(t.attr('data-posts'), 10);
        $.getJSON("php/twitter.php?user=" + t_user, function(t_tweets) {
            t_container.empty();
            for (var i = 0; i < t_posts && i < t_tweets.length; i++) {
                var t_date = Math.floor((t_date_obj.getTime() - Date.parse(t_tweets[i].created_at)) / 1000);
                var t_date_str;
                var t_date_seconds = t_date % 60;
                t_date = Math.floor(t_date / 60);
                var t_date_minutes = t_date % 60;
                if (t_date_minutes) {
                    t_date = Math.floor(t_date / 60);
                    var t_date_hours = t_date % 60;
                    if (t_date_hours) {
                        t_date = Math.floor(t_date / 60);
                        var t_date_days = t_date % 24;
                        if (t_date_days) {
                            t_date = Math.floor(t_date / 24);
                            var t_date_weeks = t_date % 7;
                            if (t_date_weeks)
                                t_date_str = t_date_weeks + ' week' + (1 == t_date_weeks ? '' : 's') + ' ago';
                            else
                                t_date_str = t_date_days + ' day' + (1 == t_date_days ? '' : 's') + ' ago';
                        }
                        else
                            t_date_str = t_date_hours + ' hour' + (1 == t_date_hours ? '' : 's') + ' ago';
                    }
                    else
                        t_date_str = t_date_minutes + ' minute' + (1 == t_date_minutes ? '' : 's') + ' ago';
                }
                else
                    t_date_str = t_date_seconds + ' second' + (1 == t_date_seconds ? '' : 's') + ' ago';
                var t_message =
                        '<p>' +
                        linkify(t_tweets[i].text) +
                        '<span>' +
                        t_date_str +
                        '</span>' +
                        '</p>';
                t_container.append(t_message);
            }
        });
    });
  },

  menu: function () {
    var menu = $('.main-nav');
    var bodyPosition,
        menuButtonHTML = '<a href="#" class="mobile-menu-button" style="color:black;border:1px solid black;"><hr style="color:black;border: 2px solid black;margin:4.5px 2px;" /><hr style="color:black;border: 2px solid black;margin:4.5px 2px;" /><hr style="color:black;border: 2px solid black;margin:4.5px 2px;" /></a>',
        menuButton = $('.mobile-menu-button'),
        menuButtonHolder = $('.logo'),
        menuMarkup = menu.clone(),
        i = 0,
        t = 0;
        //menuMarkup.prepend(menuButtonHTML);

        $(document).on('click', '.mobile-menu-button', function(e) {
          e.preventDefault();

          if(menuMarkup.hasClass('active-menu')) {
            menuMarkup.removeClass('active-menu');
          }else {
            menuMarkup.toggleClass('active-menu');            
          }

          if($('body').hasClass('active-menu')) {
             $('body').removeClass('active-menu');
          }else {            
            $('body').toggleClass('menu-effect');
          }
        });

        $(document).on('drag', 'body .main-nav', function(e) {
            bodyPosition = -(e.adx - 200);

            if(e.adx < 100) {
              $('body.menu-effect .boxed-view').css({
                '-webkit-transform': 'translate3d('+bodyPosition+'px,0,0)'
              });
            }

            if(e.end === true) {
                $('body.menu-effect .boxed-view').removeAttr("style");
              if(e.adx > 100) {
                $('body ').removeClass('menu-effect');
                $('body > .main-nav').removeClass('active-menu');
              }
            }            
        });

        enquire.register("screen and (max-width:992px)", {

            // OPTIONAL
            // If supplied, triggered when a media query matches.
            match : function() {
              menu.hide();
              if(i === 0) {
                $('body').prepend(menuMarkup);
                i++;             
              } else {
                menuMarkup.show();
              }

              if(t === 0) {
                menuButtonHolder.append(menuButtonHTML);
                t++;               
              } else {
                $('.logo .mobile-menu-button').show();
              }


              $(document).on('click', 'body > .main-nav > ul > li > a', function(e) {
                var checkParent = $(this).next('ul');
                var menuItem = $(this)[0].outerHTML;

                if(checkParent.length === 1) {
                   e.preventDefault();
                   $('body > .main-nav > ul > li > ul').removeClass('menu-items-active-mob');
                   checkParent.toggleClass('menu-items-active-mob');
                   $('body > .main-nav > ul > li > a').removeClass('link-items-active-mob');
                   $(this).toggleClass('link-items-active-mob');
                   if(checkParent.find('.prepended').length === 0 ){
                    checkParent.prepend('<li class="prepended">'+menuItem+'</li>');
                    
                   }
                }

              });

               $(document).on('click', '.link-items-active-mob', function(e) {
                  $(this).removeClass('link-items-active-mob');
                   $(this).next().removeClass('menu-items-active-mob');
                });
            },      
                                        
            // OPTIONAL
            // If supplied, triggered when the media query transitions 
            // *from a matched state to an unmatched state*.
            unmatch : function() {
             menuMarkup.hide();
             menu.show();
             $('.logo .mobile-menu-button').hide();
            },    
            
            // OPTIONAL
            // If supplied, triggered once, when the handler is registered.
            setup : function() {},    
                                        
            // OPTIONAL, defaults to false
            // If set to true, defers execution of the setup function 
            // until the first time the media query is matched
            deferSetup : true,
                                        
            // OPTIONAL
            // If supplied, triggered when handler is unregistered. 
            // Place cleanup code here
            destroy : function() {}
              
        });

  },

  simpleSlider: function () {

    $( '#simple-slider' ).sudoSlider({
       numeric: true,
       responsive: true,
       autoHeight: false,
       effect: "random",
       prevhtml:          ' <a href="#" class="prev-nav"><i class="icon-517"></i></a> ',
       nexthtml:          ' <a href="#" class="next-nav"><i class="icon-501"></i></a> ',
       controlsattr:      'id="controls"',
       numericattr:       'class="slider-nav"',
       continuous: true,
       updateBefore: true,
       animationZIndex: 10,
    });    

    var portfolioSlider = $( '#portfolio-slider' ).sudoSlider({
       numeric: false,
       responsive: true,
       slideCount: 4,
       moveCount: 1,
       speed: 500,
       continuous: false,
       updateBefore: true,
       prevhtml:          ' <a href="#" class="prev-nav"><i class="icon-517"></i></a> ',
       nexthtml:          ' <a href="#" class="next-nav"><i class="icon-501"></i></a> ',
       controlsattr:      'id="controls-portfolio"'
    });

    if($( '#portfolio-slider' ).length) {
      $(window).resize(function() {
      if($(window).width() < 992) {
        portfolioSlider.setOption('slideCount', 1);
      }else {
        portfolioSlider.setOption('slideCount', 4);
      }
    });
    }

    $( '#testimonials-slider' ).sudoSlider({
       numeric: false,
       responsive: true,
       moveCount: 1,
       speed: 1000,
       updateBefore: true,
       vertical: true,
       continuous: true,
       auto: true,
       prevhtml:          ' <a href="#" class="prev-nav"><i class="icon-503"></i></a> ',
       nexthtml:          ' <a href="#" class="next-nav"><i class="icon-515"></i></a> ',
       controlsattr:      'id="controls-testimonials"'
    });

    $( '.portfolio-slider' ).sudoSlider({
       numeric: true,
       responsive: true,
       moveCount: 1,
       speed: 1000,
       auto: false,
       continuous: true,
       updateBefore: true,
       prevhtml:          ' <a href="#" class="prev-nav"><i class="icon-517"></i></a> ',
       nexthtml:          ' <a href="#" class="next-nav"><i class="icon-501"></i></a> ',
       controlsattr:      'id="controls"',
       numericattr:       'class="slider-nav"', 
    });

    $( '.blog-slider' ).sudoSlider({
       numeric: false,
       responsive: true,
       moveCount: 1,
       speed: 1000,
       auto: false,
       continuous: true,
       updateBefore: true,
       prevhtml:          ' <a href="#" class="prev-nav"><i class="icon-517"></i></a> ',
       nexthtml:          ' <a href="#" class="next-nav"><i class="icon-501"></i></a> ',
       controlsattr:      'id="controls"',
       numericattr:       'class="slider-nav"', 
    });


    // sliders.each(function() {
    //   var imgSrc = $(this).find('img').attr('src');
    //   $('.portfolio-thumbs').append('<li><img src="' + imgSrc + '" height="28"></li>');
    // });

  },

  knob: function () {

    $(".statistic-item").knob({
      thickness: '.1',
      lineCap: 'round',
      fgColor: '#ffffff',
      bgColor: 'rgba(255,255,255,0.2)',
      readOnly: true,
      displayInput: true,
      font: "Oxygen",
      fontWeight: '300',
      step: 1,
    });

  },

  progressBar: function () {
    var target = $('.progresive-bar-items > li'),
        bar = $('.progresive-bar-items > li span');

    target.each(function (e) {

      var data = bar.eq(e).data('progress');
      var i = 100;

      data = 100-data;

      var progressVal = setInterval(function() {
        if (i > data) {

          if(i%2 == 0) {
            i = i+3;
          } else {
            i = i-5;
          }

          bar.eq(e).css('right', i+'%');
        } else {
          clearInterval(progressVal);
        }          
      }, 20);
      
    });
  },

  gridEffect: function() {

      if($('#grid-effect').length) {
          new AnimOnScroll( document.getElementById( 'grid-effect' ), {
            minDuration : 0.4,
            maxDuration : 0.7,
            viewportFactor : 0.1
          } );
      }
    
  },

  zoomImage: function () {
    $( '.zoom-image' ).swipebox();
  },

  portfolioFilter: function () {
      $('.filter-tags > li a').click(function (e) {
          e.preventDefault();
          var tag = $(this).text();
          var filters = $(this).parent();
          $('.filter-tags > li').removeClass('active-filter')
          filters.addClass('active-filter');

          $('#sorted-tag').html(tag);

          $('.portfolio-items > li > div').each(function() {
           $(this).parent().removeClass('hidden-item');
            if($(this).hasClass(tag.toLowerCase()) == false && tag.toLowerCase() !== 'all') {
              $(this).parent().addClass('hidden-item');
            }

          });

      });
  },

  scrollEffect: function() {

      $('.box').appear();

      $('.box').on('appear', function(event) {

          $('.fancy-heading').addClass('scroll-actived');
          
          if($(this).is('#features')) {

            if($('.features-items > li').hasClass('scroll-actived') === false) {

                $('.features-items > li').each(function(index) {
                      setTimeout(
                        function() 
                        {
                          //do something special
                           $('.features-items > li').eq(index).addClass('scroll-actived');

                        }, 400 * index);
                      
                });
            }
          }

          if($(this).is('.action-box') &&  $('.action-box').hasClass('scroll-actived') === false) {
            $('.action-box').addClass('scroll-actived');
          }

          if($(this).is('.statistics-box') && $('.statistics-box').hasClass('scroll-actived') === false) {
            $('.statistics-box').addClass('scroll-actived');
            setTimeout(
              function() 
              {               

                  $(".statistic-item").each(function() {
                    var defaultVal = $(this).val(),
                        item = $(this),
                        i = 0,
                        statisticAnimation = setInterval(function(){
                          if(i<=defaultVal) {
                            item.val(i).trigger("change");
                            i++;
                          } else {
                            clearInterval(statisticAnimation);
                          }
                        }, 50);

                  });

                
              }, 400);
          }

          // if($(this).is('.portfolio-box') && $('.portfolio-box').hasClass('scroll-actived') === false) {

          //     $('.portfolio-box').addClass('scroll-actived');

          //      $('.portfolio-items > li').each(function(index) {
          //         setTimeout(
          //           function() 
          //           {
          //             //do something special
          //              $('.portfolio-items > li').eq(index).addClass('scroll-actived');

          //           }, 100 * index);
                      
          //       });
          // }

    });
  }

};

teslaThemes.init();