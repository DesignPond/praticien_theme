<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>

	<?php
		
		// Get the atf if there is one
		$atf   = get_post_meta($post->ID, 'atf', true);
		// check if the custom field has a value
		$title = ($atf != '' ? $atf : get_the_title() );
		
		echo '<h1>'.$title.'</h1>';
		
		// the content of post 
		the_content(); 

		if(!isset($_REQUEST['wysija-page']))
		{
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
		}
		
	?>
		
	</article>			

<?php endwhile; ?>
