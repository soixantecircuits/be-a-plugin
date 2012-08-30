<?php get_header(); ?>
<div id="content" class="clear">
  <?php if ( have_posts() ) : ?>
    <?php get_template_part( 'loop' ); ?>
  <?php else : ?>
    <p><?php _e( 'No posts found.', 'be_a_plugin' ); ?></p>
  <?php endif; ?>
</div><!--end content-->
<?php get_sidebar(); ?>
<?php get_footer(); ?>