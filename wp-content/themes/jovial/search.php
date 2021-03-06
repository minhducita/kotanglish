<?php get_header(); ?>
	<section id="content" class="first clearfix">
		<div class="cat-container">	
	    	<div class="cat-head mbottom">
				<!-- author ndung
			         2018/07/25
			         検索結果一覧の表示を変わる
			    -->
				<header class="archive-header">
			        <h1 class="archive-title">
			            <span>
			            <?php if(get_search_query()) : ?>
						<?php echo get_search_query()?>の検索結果一覧
			            <?php else : ?>検索結果一覧
			            <?php endif ?>
			            </span>
			        </h1>
			    </header>

				<!-- <h1 class="archive-title">
					<?php _e("Search Results For:", "jovial"); ?> <?php echo get_search_query(); ?>
				</h1> -->
				<!-- チャージが終わった -->

                <?php echo category_description(); ?>
			</div>
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<!--
					@author ndung
					date 2018/08/01
					custom search result
				 -->
				<article class="item-list mbottom">
			        <div class="cthumb">
		                <!-- <?php if ( has_post_thumbnail() && ! post_password_required() ) : ?>
					        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium');?></a> 
                            <?php endif; ?> -->
					        <!-- <div class="catbox"><?php printf('%1$s', get_the_category_list( ) ); ?></div> -->
				    </div>
			        <div class="cdetail">		
			            <h3><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
					        <div class="catpost"><?php echo get_post_meta(get_the_ID(), '_aioseop_description', true); ?></div>

                            <!-- <div class="postmeta">
			           		    <p class="vsmall pnone">by  <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) )?>" title="<?php sprintf( esc_attr__( 'View all posts by %s', 'tie' ), get_the_author() ) ?>"><?php echo get_the_author() ?> </a>
						        <span class="mdate alignright"><?php echo the_date('F j, Y') ?></span></p>
						    </div> -->
			        </div>
			    </article>				
				<?php endwhile; ?>
				    <div class="pagenavi alignright">
					    <?php if ($wp_query->max_num_pages > 1) jovial_wp_pagination(); ?>
					</div>
				<?php else : get_template_part( 'no-results', 'archive' ); endif; ?>
		</div>
	</section>
<?php get_sidebar(); ?>
<?php get_footer(); ?>