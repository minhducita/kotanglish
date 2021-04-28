<?php get_header(); ?>
	<section id="content" class="first clearfix" role="main">
		<div class="post-container">
			<?php if (have_posts()) : ?>
               	<?php while ( have_posts() ) : the_post(); ?>
   			        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article">
  						<div class="singlebox">
						   
                                <header class="article-header">
									<div id="post-meta"><?php get_template_part( 'inc/post-meta' ); ?></div>

									<h1 class="post-title"><?php the_title(); ?></h1>
								</header> <!-- end header -->

								<section class="entry-content clearfix">
									<?php the_content(); ?>
									<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'jovial' ), 'after' => '</div>' ) ); ?>
									<div class="clr"></div>
			</br />
			<footer id="main-footer2">
								</section> <!-- end section -->
			</br />

<!--/* OpenX Javascript Tag v2.8.8 */-->

<!--/*
  * The backup image section of this tag has been generated for use on a
  * non-SSL page. If this tag is to be placed on an SSL page, change the
  *   'http://www.jawhm.or.jp/ad/www/delivery/...'
  * to
  *   'https://www.jawhm.or.jp/ad/www/delivery/...'
  *
  * This noscript section of this tag only shows image banners. There
  * is no width or height in these banners, so if you want these tags to
  * allocate space for the ad before it shows, you will need to add this
  * information to the <img> tag.
  *
  * If you do not want to deal with the intricities of the noscript
  * section, delete the tag (from <noscript>... to </noscript>). On
  * average, the noscript tag is called from less than 1% of internet
  * users.
  */-->

<script type='text/javascript'><!--//<![CDATA[
   var m3_u = (location.protocol=='https:'?'https://www.jawhm.or.jp/ad/www/delivery/ajs.php':'http://www.jawhm.or.jp/ad/www/delivery/ajs.php');
   var m3_r = Math.floor(Math.random()*99999999999);
   if (!document.MAX_used) document.MAX_used = ',';
   document.write ("<scr"+"ipt type='text/javascript' src='"+m3_u);
   document.write ("?zoneid=189");
   document.write ('&amp;cb=' + m3_r);
   if (document.MAX_used != ',') document.write ("&amp;exclude=" + document.MAX_used);
   document.write (document.charset ? '&amp;charset='+document.charset : (document.characterSet ? '&amp;charset='+document.characterSet : ''));
   document.write ("&amp;loc=" + escape(window.location));
   if (document.referrer) document.write ("&amp;referer=" + escape(document.referrer));
   if (document.context) document.write ("&context=" + escape(document.context));
   if (document.mmm_fo) document.write ("&amp;mmm_fo=1");
   document.write ("'><\/scr"+"ipt>");
//]]>--></script><noscript><a href='http://www.jawhm.or.jp/ad/www/delivery/ck.php?n=aada199f&amp;cb=INSERT_RANDOM_NUMBER_HERE' target='_blank'><img src='http://www.jawhm.or.jp/ad/www/delivery/avw.php?zoneid=189&amp;cb=INSERT_RANDOM_NUMBER_HERE&amp;n=aada199f' border='0' alt='' /></a></noscript>



								<footer class="article-footer">
								    <?php the_tags('<p class="tags"><span class="tags-title">' . __('Tags:', 'jovial') . '</span> ', ' ', '</p>'); ?>
									<p class="tags"></p>
                                    <?php edit_post_link( __( 'Edit', 'jovial' ), '<span class="edit-link">', '</span>' ); ?>
								</footer> <!-- end footer -->
                                <?php get_template_part( 'inc/single', 'post-share' ); ?>
                                <?php get_template_part( 'inc/related', 'posts' ); ?>                   	
                                <?php if ( comments_open() || '0' != get_comments_number() ) comments_template( '', true ); ?>	
                        </div>
					</article> <!-- end article -->
                <?php endwhile; ?>
			<?php endif; ?>
		</div>															
	</section> <!-- end #main -->  
<?php get_sidebar(); ?>
<?php get_footer(); ?>