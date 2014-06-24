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
					<th class="hideOnSmall">Sous-catégorie</th>
					<th class="hideOnSmall">Langues</th>
				</tr>
			</thead>
			
			<?php 
			
				// Query with params
				$query = getLastArrets( $categorie , $dateStart , $dateEnd , $star);
				
				// Page to link decision to
				$page_decision = get_ID_by_slug('decision');
				
				// echo list
				echo prepareListDecisions($query , $page_decision , $post->ID, NULL , $dateStart , $dateEnd );
				
			 ?>	
					
			<tfoot>
				<tr>
					<th>Date publ.</th>
					<th>Date déc.</th>
					<th>Référence</th>
					<th>Catégorie</th>
					<th class="hideOnSmall">Sous-catégorie</th>
					<th class="hideOnSmall">Langues</th>
				</tr>
			</tfoot>
		</table>
	  
  </div>
  <div class="col-md-3">
  	
  	<h2 class="sectionTitle">Catégories</h2>
	<?php
			
		// Sidebar for nouveautes arrets	
		
		$categories = getAllCategories();	
		$html       = prepareSidebar($categories, $categorie , $star);
		
		echo $html;
		
	?>
	  
  </div>
</div>


