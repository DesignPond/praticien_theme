<div class="row">
  <div class="col-md-8">
  <h1>Lois</h1>
  <?php 
	
	global $post;
	
	$list = array(0);
	
	$loi  = (!empty( $_REQUEST['loi']) ?  $_REQUEST['loi'] : null);	

	if($loi)
	{
		$list = getLoiArticles($loi);
	}
	
	// Search all the id and paginate 				
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;	
	
	$args  = array(
	    'post_type'      => 'post',
		'paged'          => $paged,
		'is_paged'       => true,
		'posts_per_page' => 10, 
		'post__in'       => $list,
		'orderby'        => 'post__in'
	);	
	
	if(!empty($list))
	{
		// Reste of query
		$temp     = $wp_query;
		$wp_query = null;
	
		$wp_query = new WP_Query( $args );
	
							
		// Loop over results	
		if ( $wp_query->have_posts() )
		{
			while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
				
			<article>
			<?php
				
				// Get the atf if there is one
				$atf   = get_post_meta($post->ID, 'atf', true);
				// check if the custom field has a value
				$title = ($atf != '' ? $atf : get_the_title() );
				
				echo '<h1>'.$title.'</h1>';
				
				// the content of post 
				the_content(); 
				
				// Get top categorie for autor
				$categories = wp_get_post_categories( $post->ID );
				$category   = get_top_parent_category($categories[0]);
				
				// Get annee for autor
				$annee = getAnne( $post->ID );
				
				// The autor of post
				echo getAutor($post,$category,$annee); 
				
				// The comment if there is one
				$commentPost = get_field( "commentaire_pour_arrêt" , $post->ID );				
				$commentPost = ($commentPost ? '<h4>Commentaire</h4>'.$commentPost : '');
				
				echo $commentPost;
				
			?>
			
		</article>			
	
		<?php
			 endwhile;  
		 } 
		 else if(!$loi){ echo '<article><h3>Choissisez une loi</h3></article>'; }
		 else {  echo '<article><p>Aucun arrêt ne correspond à votre demande</p></article>'; } // end if post exist	
			
			// Set pagination links
		 	if ($wp_query->max_num_pages > 1){ wpc_pagination(); }
		    // Reste query
			wp_reset_query(); 
		  
	 } // end if request loi exist
	 
	?>
  </div>
  
  <div class="col-sm-4">
  
  	<h2 class="sectionTitle">Lois</h2>
  	<?php echo getListLois(); ?>
  	
  </div>

</div>


