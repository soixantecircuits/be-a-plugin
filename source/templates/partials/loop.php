<?php if ( have_posts() ) : ?>
	<?php while ( have_posts() ) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="post-header">
				<h2 class="post-title">
					<a href="<?php the_permalink(); ?>" rel="bookmark">
					  <?php the_title(); ?>
					</a>
				</h2>
				<p><?php the_time( __( 'F jS, Y', 'be_a_plugin' ) ); ?></p>
				<p><?php the_author(); ?></p>
				<?php if ( has_post_thumbnail() ) { ?>
					<a href="<?php the_permalink(); ?>">
						<?php the_post_thumbnail(); ?>
					</a>
				<?php } ?>
			</div><!--end post-header-->
			<div class="entry">
				<?php the_content( __( 'Read more', 'be_a_plugin' )); ?>
				<?php edit_post_link( __( 'Edit this', 'be_a_plugin' ), '<p>', '</p>' ); ?>
			</div><!--end entry-->
			<div class="post-footer">
				<p><?php comments_popup_link( __( 'Leave a comment', 'be_a_plugin' ), __( '1 Comment', 'be_a_plugin' ), __( '% Comments', 'be_a_plugin' ) ); ?></p>
			</div><!--end post-footer-->
		</div><!--end post-->
	<?php endwhile; /* rewind or continue if all posts have been fetched */ ?>
		<div class="pagination index">
			<div class="alignleft">
				<?php previous_posts_link( __( '&larr; Newer entries', 'be_a_plugin' )); ?>
			</div>
			<div class="alignright">
				<?php next_posts_link( __( 'Older entries &rarr;', 'be_a_plugin' )); ?>
			</div>
		</div><!--end pagination-->
	<?php else : ?>
<?php endif; ?>