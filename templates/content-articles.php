<?php
			
	// Get current category
	$category  = get_query_var('cat');
	
	// Paged ? Yes please...
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;	
    
    // Filter for years
	$annee     = (!empty($_REQUEST['annee']) ? $_REQUEST['annee'] : null );
	$tax_query = array();
								
	if (!empty($annee))
	{
		$tax_query[] = 	array(
			'relation' => 'AND',
			'taxonomy' => 'annee',
			'field'    => 'slug',
			'terms'    => $annee
		);
	}
			
	$args = array(
		'post_type' => 'post',
		'paged'     => $paged,
		'is_paged'  => true,
		'cat'       => $category,
		'tax_query' => $tax_query 
	);	

	// Reste of query
	$temp     = $wp_query;
	$wp_query = null;
		
	// Main query for all posts in current category
	$wp_query = new WP_Query( $args );

?>
	
<div class="row">
   <div class="col-md-9">
  
	<?php 
	
	// Loop over results	
	if ( $wp_query->have_posts() )
	{
		while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
			
		<article>
		<?php
		
			$atf   = get_post_meta($post->ID, 'atf', true);
			// check if the custum field has a value
			$title = ($atf != '' ? $atf : get_the_title() );
			
		?>
			<h1><?php echo $title; ?></h1>
			
			<!-- the content of post -->
		<?php 
				
			the_content(); 
			echo getAutor($post,$category,$annee); 
			
			$commentPost = get_field( "commentaire_pour_arrêt" , $post->ID );				
			$commentPost = ($commentPost ? '<h4>Commentaire</h4>'.$commentPost : '');
			
			echo $commentPost;
			
		?>
			
		</article>			
	
	<?php endwhile;  ?>	
					
	<?php } else { ?><article><p>Aucun arrêt ne correspond à votre demande</p></article><?php }	?>

	<?php 
	
		  if ($wp_query->max_num_pages > 1){ wpc_pagination(); }
		  
		  wp_reset_query(); 
		
	?>

   </div>
    <div class="col-md-3">
		
		 <h2 class="sectionTitle">Sections</h2>
    	 <!-- Filter list by years -->
         <ul id="listAnnee">
         	 <li><a class="" href="<?php echo $url.'/?cat='.$category; ?>">Tout</a></li>
         	 <?php
				         	 
				$args = array(
				    'orderby'       => 'name', 
				    'order'         => 'ASC',
				    'hierarchical'  => true
				);         	 
       	 
         	    $terms = get_terms('annee',$args);
         	    
				foreach($terms as $term) 
				{
					$anneeName = $term->name;
					$anneeSlug = $term->slug;				
					         	    
					$url = add_query_arg( array( 'cat' => $category, 'annee' => $anneeSlug) , get_permalink() );
					
					echo '<li><a ';					
						if($annee == $anneeSlug) { echo 'class="active"';}
					echo ' href="'.$url.'">'.$anneeName.'</a></li>';
				}
				
         	 ?>
         </ul>
         
         <div class="clearfix"></div>
         
		 <?php  echo getResumesSidebat($category); ?>
         
   </div>
</div>
