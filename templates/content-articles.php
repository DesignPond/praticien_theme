<?php
	
	// Main query for all posts in current category
	
	// Get current category
	$category  = get_query_var('cat');
	
	// Paged ? Yes please...
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;	
			
	$args = array(
		'post_type' => 'post',
		'paged'     =>  $paged,
		'is_paged'  => true,
		'cat'       => $category,
		// 'tax_query' => $tax_query 
	);	

	// Reste of query
	$temp     = $wp_query;
	$wp_query = null;
	
	// Main query
	$wp_query = new WP_Query( $args );

?>
	
<div class="row">
   <div class="col-md-8">
	<?php 
	
	// Loop over results	
	if ( $wp_query->have_posts() ){
		while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
			
		<article>
		<?php
		
			$atf   = get_post_meta($post->ID, 'atf', true);
			// check if the custum field has a value
			$title = ($atf != '' ? $atf : get_the_title() );
			
		?>
			<h1><?php echo $title; ?></h1>
			<?php the_content(); ?>
		</article>			
				
						
	<?php endwhile; } else { ?>	
		<p>Rien trouvé</p>		
	<?php }	?>
	
	<div class="nav-previous alignleft"><?php next_posts_link( 'Older posts' ); ?></div>
	<div class="nav-next alignright"><?php previous_posts_link( 'Newer posts' ); ?></div>	
	
	<?php wp_reset_query(); ?>

   </div>
    <div class="col-md-4">
   </div>
</div>
