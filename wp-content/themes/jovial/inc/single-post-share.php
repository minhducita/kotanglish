<?php /* Social Shares. */ ?>
<section class="post-shares">
    <ul class="post-share">
	<li><div class="fb-like" data-href="<?php the_permalink(); ?>" data-width="100" data-layout="box_count" data-show-faces="false" data-send="false"></div></li>
        <li><a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php the_permalink(); ?>" data-via="<?php echo get_theme_mod( 'twitterlink' ); ?>" data-lang="en" data-related="anywhereTheJavascriptAPI" data-count="vertical">Tweet</a></li>
        <li><div class="g-plus" data-action="share" data-annotation="vertical-bubble" data-height="60" data-href="<?php the_permalink(); ?>"></div></li>
        				
	</ul>
</section>
