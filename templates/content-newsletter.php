<div class="row">
  <div class="col-md-8">
  	<h1>Inscription Newsletter</h1>
  	<h3><?php echo roots_title(); ?></h3>
	<?php while (have_posts()) : the_post(); ?>
		<?php the_content(); ?>
	<?php endwhile; ?>
  </div>
  <div class="col-md-4">
	  <h1>Archives</h1>
	  <?php  echo do_shortcode( '[archives_newsletter list="'.$_REQUEST['id'].'"]' ); ?>
  </div>
</div>


