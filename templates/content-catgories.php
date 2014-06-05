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
					
					$rows = array_chunk($categories, 7);
 
					foreach($rows as $row)
					{
											
						echo '<ul id="links" class="col-md-3">';
						
						foreach($row as $id => $categorie){

							echo '<li class="';
							
							if (strlen($categorie) >= 40) { echo ' doubleLine ';}
							
							echo '"><a href="' .get_category_link( $id ). '#'.$categorie.'"  class="link">'.$categorie.'</a></li>';						
						}
						
						echo '</ul>';													
					}

				}

		  		
	  		?>	  		
	  		</div>
  		
   </div>
</div>


