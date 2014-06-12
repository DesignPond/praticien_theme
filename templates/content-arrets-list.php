<?php
			
	// variables in url
	$categorie  = ( !empty($_REQUEST['categorie']) ? $_REQUEST['categorie'] : NULL);
	$dateStart  = ( !empty($_REQUEST['dateStart']) ? $_REQUEST['dateStart'] : NULL);
	$dateEnd    = ( !empty($_REQUEST['dateEnd']) ? $_REQUEST['dateEnd'] : NULL);
	$star       = ( !empty($_REQUEST['star']) ? $_REQUEST['star'] : 0);

?>

<h1><?php echo roots_title(); ?></h1>

<div class="row">
  <div class="col-md-9">
    		
  		<!-- Table list arrêts -->
  		<table id="arrets" class="hover table" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th>Date publ.</th>
					<th>Date déc.</th>
					<th>Référence</th>
					<th>Catégorie</th>
					<th>Sous-catégorie</th>
					<th>Langues</th>
				</tr>
			</thead>
			
			<?php echo getLastArrets( get_permalink() , $categorie , $dateStart , $dateEnd , $star); ?>	
					
			<tfoot>
				<tr>
					<th>Date publ.</th>
					<th>Date déc.</th>
					<th>Référence</th>
					<th>Catégorie</th>
					<th>Sous-catégorie</th>
					<th>Langues</th>
				</tr>
			</tfoot>
		</table>
	  
  </div>
  <div class="col-md-3">
  
	<?php
			
		// Sidebar for nouveautes arrets	
		
		$categories = getAllCategories();	
		$html       = prepareSidebar($categories, $categorie , $star);
		
		echo $html;
		
	?>
	  
  </div>
</div>


