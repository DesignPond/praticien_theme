<?php
			
	// variables in url
	$categorie  = ( !empty($_REQUEST['categorie']) ? $_REQUEST['categorie'] : NULL);
	$dateStart  = ( !empty($_REQUEST['dateStart']) ? $_REQUEST['dateStart'] : NULL);
	$dateEnd    = ( !empty($_REQUEST['dateEnd']) ? $_REQUEST['dateEnd'] : NULL);
	$mois       = ( !empty($_REQUEST['mois']) ? $_REQUEST['mois'] : NULL);
	$star       = ( !empty($_REQUEST['star']) ? $_REQUEST['star'] : 0);

?>

<h1><?php echo roots_title(); ?></h1>

<div class="row">
  <div class="col-md-9">
  		
  		<!-- Bloc search -->
  		<div class="bloc blocAutoHeight">	         
			<form class="form-inline" id="choixPeriode" method="post" action="<?php echo get_permalink(); ?>" role="form">
				
				<div class="row">
				
					<div class="form-group col-sm-4">
						Du &nbsp;<input type="text" class="form-control" name="dateStart" id="dateStart">
					</div>
					<div class="form-group col-sm-4">
						au &nbsp;<input type="text" class="form-control" name="dateEnd" id="dateEnd">
					</div>					
					<div class="checkbox col-sm-3">
						<label><input value="1" name="star" <?php if($star){echo ' checked="checked" ';} ?> type="checkbox"> Que proposé pour la publication</label>
					</div>
					
					<?php if($categorie){ echo '<input type="hidden" value="'.$categorie.'" name="categorie" />'; } ?>
					
					<div class=" col-sm-1">
						<button type="submit" class="btn btn-default btn-blue">Ok</button>	
					</div>
					
				</div>
							
			</form>			         
  		</div>
  		
  		<!-- Table list arrêts -->
  		<table id="arrets" class="hover table" cellspacing="0" width="100%">
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
			
			<?php echo getLastArrets( get_permalink() , $categorie , $dateStart , $dateEnd , $star); ?>	
					
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
			
		// Sidebar for nouveautes arrets	
		
		$categories = getAllCategories();	
		$html       = prepareSidebar($categories, $categorie , $star);
		
		echo $html;
		
	?>
	  
  </div>
</div>


