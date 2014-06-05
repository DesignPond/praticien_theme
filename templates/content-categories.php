<div class="row">
   <div class="col-md-12">
   		<h1 class="centerTitle">Choisissez une rubrique</h1>
    </div>
</div>  

<div class="row">
   <div class="col-md-12" id="AllLinks">
  		
		<div class="row">
  		<?php
	  		
	  		$categories = getAllArretsCategories();
	  		
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
						
						echo '"><a href="'.$url.'#'.$categorie.'" class="link">'.$categorie.'</a></li>';						
					}
					
					echo '</ul>';													
				}
			}		  		
  		?>	  		
  		</div>
  		
   </div>
</div>


