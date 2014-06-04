<h1><?php echo roots_title(); ?></h1>

<div class="row">
  <div class="col-md-9">
	  
  		<table id="arrets" class="display" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th>Date publication</th>
					<th>Date décision</th>
					<th>Référence</th>
					<th>Catégorie</th>
					<th>Sous-catégorie</th>
					<th>Langues</th>
				</tr>
			</thead>
<?php

	$page = get_permalink();
	
	echo getLastArrets(15 , $page , $categorie = NULL , $annee = NULL , $mois = NULL);
	
?>
			<tfoot>
				<tr>
					<th>Date publication</th>
					<th>Date décision</th>
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
	
		/**
		 * Sidebar for nouveautes arrets
		*/
	
		// variables in url
		$categorie  = ( !empty($_REQUEST['categorie']) ? $_REQUEST['categorie'] : NULL);
		$star       = ( !empty($_REQUEST['star']) ? $_REQUEST['star'] : 0);
	
		$categories = getAllCategories();	
		$html       = prepareSidebar($categories, $categorie , $star);
		
		echo $html;
		
	?>
	  
  </div>
</div>


