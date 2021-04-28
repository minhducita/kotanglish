<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<title><?php wp_title( '|', true, 'right' ); ?></title> 
<meta charset="<?php bloginfo( 'charset'); ?>"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>"/>
<script type="text/javascript" src="https://www.taglog.jp/www.kotanglish.jp/taglog-x.js" async></script>
<script src='https://www.recaptcha.net/recaptcha/api.js'></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<!--
    <nav id="main-nav" class="top-bar">
	    <div id="main-nav-wrap" class="container">
    	<?php if ( has_nav_menu( 'main-menu' ) ) : ?>
		<div id="menu-icon" class="pos-1"></div><?php
		wp_nav_menu( array( 'theme_location' => 'main-menu', 'container' => '', 'menu_id' =>'main-menu', 'menu_class' => 'menu', 'depth' => '2' ) ); 
		endif; ?>
		    
		</div>
		<div class="clr"></div>
	</nav>
-->
	<header id="top-header">
	    <div class="container top-header-con">

            <?php // if(is_home()) echo '<h1>'; else echo '<h2>'; ?>
		<h1><?php bloginfo( 'description' ); ?></h1>
		<h2>
			   <?php if ( get_theme_mod( 'jovial_logo' ) ) : ?>


<center>
        <a href='<?php echo esc_url( home_url( '/' ) ); ?>' title='<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>'>
		<img src='<?php echo esc_url( get_theme_mod( 'jovial_logo' ) ); ?>' alt='<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>'></a>
	<br />
	<a href="http://www.kotanglish.jp/759" class="logo3" target="blank" ><?php echo'  What is KOTANGLISH?  ' ?></a><a href="https://jp.surveymonkey.com/r/WJGNFJN" class="logo4" target="blank" ><?php echo'Ask Your Question' ?></a>
</center>
              <?php else : ?>
			
           <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo" ><?php bloginfo( 'name' ); ?></a> 
			<?php endif; ?>
			<?php if(is_home()) echo '</h1>'; else echo '</h2>'; ?>
       	    
             
            <div class="clr"></div>
        </div> <!-- end #container -->
	</header> <!-- end header -->
	
<!-- author ndung
	 date	2018/07/26
	 add folow button for SP 
-->
<script type="text/javascript">
    //ページ内リンク、#非表示。スムーズスクロール
    document.getElementsByTagName('a[href^=#]').onClick = function(){
        var speed = 800;
        var href= $(this).attr("href");
        var target = $(href == "#" || href == "" ? 'html' : href);
        var position = target.offset().top;
        $("html, body").animate({scrollTop:position}, speed, "swing");
        return false;
    };

    function hide_banner(){
        var banner = $("#mem-banner");
        banner.hide();

        if (typeof(Storage) !== "undefined")
        {
            localStorage.memBanner = "hide";
            localStorage.memBannerTime = new Date().getTime() / 1000;
        }
        else
            console.log ( '#Browser does not support localStore' );

        taglog.sendInfo('object', 'click', 'close');
    };

    $("document").ready(function(){

        // check click "CLOSE bUTTON" or not!
        //checkCookieClick();
        var banner = $('.banner-rdirect');

        //localStorage.removeItem("memBanner");

        if (typeof(Storage) !== "undefined") {

            if(!localStorage.memBanner || localStorage.memBanner == "")
            {
                localStorage.memBanner = "begin";
            }
            else if(localStorage.memBanner && localStorage.memBanner == "begin")
            {
                localStorage.memBanner = "clicked";
            }

            if( (localStorage.memBanner && localStorage.memBanner == "hide") )
            {
                var timeNow = "";
                timeNow = new Date().getTime() / 1000;
                memBannerTime = localStorage.memBannerTime;

                if(timeNow - 24*60*60 < memBannerTime) // set 24h
                //if(timeNow - 30 < memBannerTime) // set 30s to testing
                {
                    banner.hide();
                }

            }
            else if( (localStorage.memBanner && localStorage.memBanner == "begin") )
            {
                banner.hide();
            }

            // console.log ( localStorage.memBanner );
            // console.log (localStorage.memBannerTime);
            // console.log (new Date().getTime() / 1000);
        }
        else
        {
            // console.log ( '#Browser does not support localStore' );
        };
    });

    function popupTwitterFolow()
    {
        var popup = document.getElementsByClassName("banner-rdirect-flat");
        popup['0'].style.display = 'block';
    };

    // on PC, set css for twitter
    $(window).bind("load", function() { 
        var isMobile = document.getElementById('mem-banner');
        if (!isMobile)
        {
            var iframe = document.getElementById('twitter-widget-0');
            var innerDoc = iframe.contentDocument || iframe.contentWindow.document;
            var timelineViewport = innerDoc.getElementsByClassName("timeline-Viewport")['0'];
            timelineViewport.style.overflow = "hidden scroll";
            timelineViewport.style.maxHeight = "600px";

            var blockTwitter = document.getElementById('twitter-show-timeline');
            blockTwitter.style.overflow = "inherit";
        }
        var textQuestion = document.getElementsByClassName("textarea-question")['0'];
        textQuestion.style.width = "104%";
    });
</script>

<?php
    {
        // on SP, show folow button twitter
        if(wp_is_mobile() && (!isset($_SESSION['mem_id']) || $_SESSION['mem_id']=='')) {
        echo '
            <div id="mem-banner" class="banner-rdirect">
                <div class="banner-rdirect-flat">
					<p style="text-align: center;">
						<a href="https://twitter.com/intent/tweet?screen_name=kotanglish&ref_src=twsrc%5Etfw" class="" data-show-count="false">
							<img class=" size-medium wp-image-2533 aligncenter" src="' . esc_url( home_url( '/' )) . 'wp-includes/images/Follow-Me.png' . '" alt="TW-バナー" width="300" height="74">
						</a>
					</p>
                    <button onClick="hide_banner()">x</button>
                </div>
            </div>
        ';
      }
    }
?>
<!-- end -->

	<div class="container mtop">
		<div id="inner-content">

