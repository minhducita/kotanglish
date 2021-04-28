<?php ?>		
<p class="post-meta vsmall">
	<span class="post-meta-date"><?php _e( 'Updated on ' , 'jovial' ); ?><?php echo the_time(get_option( 'date_format' )) ?></span>
	<?php if ( post_password_required() != true ): ?>
    <?php endif; ?>
</p>
<div class="clear"></div>
<?php ?>