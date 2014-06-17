<section id="AllLinks">
	<div class="row">
	   <div class="col-md-12">	
	
			<div class="row">
			
			<h1 class="centerTitle">Choisissez une rubrique</h1>
			   		
	  		<?php
		  		
		  		// Sessions for user suscribed to newsletter	
				$suscribedToNewsletter = (!empty($_SESSION['suscribedToNewsletter']) ? $_SESSION['suscribedToNewsletter'] : null );
				$idListNewsletter      = (!empty($_SESSION['idListNewsletter']) ? $_SESSION['idListNewsletter'] : null );
				
		  		$list = ($suscribedToNewsletter ? getAllCatsByNewsletterList($idListNewsletter) : null );
		  		
		  		if($list){$list = implode(",", $list);}
		  		
		  		$categories = getAllArretsCategories($list);
		  		
		  		$current    = get_query_var('cat');		
		  		
		  		if(!empty($current))
		  		{
		  			// Make sur current is a top category	  	
		  			$current = get_top_parent_category($current);			  		
		  		}
		  		
		  		if( !empty($categories)){
					
					$rows = array_chunk($categories, 7 , true);
	
					foreach($rows as $row)
					{											
						echo '<ul id="links" class="col-md-3">';
						
						foreach($row as $id => $categorie)
						{
							
							$url = add_query_arg( array( 'cat' => $id ) , get_permalink() );
							
							echo '<li class="';
							
							if (strlen($categorie) >= 40) { echo ' doubleLine ';}
							
							echo '"><a href="'.$url.'#'.$categorie.'" class="link ';
							
							if($current == $id){ echo 'active'; }
							
							echo '">'.$categorie.'</a></li>';						
						}
						
						echo '</ul>';													
					}
				}	
						
	  		?>	  		
	  		</div>
	  		
	   </div>
	</div>
</section>

