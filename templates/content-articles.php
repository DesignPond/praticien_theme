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
				
	// The categorie for anchor	
	$parentName = '';
	
	$cat_id  = get_query_var('cat');
	$name    = get_cat_name( $cat_id );
	$child   = get_top_parent_category($cat_id);	
	
	if(!empty($cat_id) && ($child != $cat_id))
	{	  	
		$parentName = get_cat_name( $child ); 
		$parentName = $parentName.' / ';		  		
	}
		
	echo '<a id="anchorTitle"><h2 class="sectionTitre">'.$parentName.''.$name.'</h2></a>';
	
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
