<?php
	
	// Type of search
	$typeSearch = $_POST['search'];
	
	$posts  = array();
	
	if($typeSearch == 'simple')
	{
		$s      = $_REQUEST['term'];	
		$paged  = (get_query_var('paged')) ? get_query_var('paged') : 1;	
		
		$args = array(
			'post_type'      => 'post',
			'posts_per_page' => 5,
			'paged'          =>  $paged,
			'is_paged'       => true,
			's'              =>  $s
		);	
		
		$search = new WP_Query( $args );

		if ( $search->have_posts() )
		{ 
		   	while ( $search->have_posts() ) : $search->the_post(); 
		   	
		   		$posts[] = $post->ID;
		   		
		   	endwhile;
		}
	} 

	if($typeSearch == 'terms')
	{	
		$s = trailSearch();
		
		$search = articleTermsSearch();
		
		if ( !empty($search) )
		{ 
		   	foreach($search as $post)
		   	{
			   	$posts[] = $post->ID;
		   	}
		}	
	} 	


?>
	
<div class="row">
   <div class="col-md-12">
   		
   	   <h3>Résultats pour: <small><?php echo $s; ?></small></h3>			
   	   	
	   <?php

		   if(!empty($posts))
		   {			   
		   		foreach($posts as $id)
		   		{
		   			$post = get_post($id);

			   		?><article><?php
		   				
		   				echo '<h2 class="sectionTitre">'.trailCategoriesPost($post->ID).'</h2>';
									
						// Get the atf if there is one
						$atf   = get_post_meta($post->ID, 'atf', true);
						// check if the custom field has a value
						$title = ($atf != '' ? $atf : $post->post_title );
						
						echo '<h1>'.$title.'</h1>';
						
						// the content of post 
						echo nl2br($post->post_content); 
						
						// The autor of post
						echo getAutor($id,$category,$annee); 
						
						// The comment if there is one
						$commentPost = get_field( "commentaire_pour_arrêt" , $post->ID );				
						$commentPost = ($commentPost ? '<h4>Commentaire</h4>'.$commentPost : '');
						
						echo $commentPost;
						
					?></article><?php					   			
		   		}
		   	}

	   ?>
   </div>

</div>

<?php

echo "<!--\n";
var_dump($wpdb->queries);
echo "\n-->";
?>
