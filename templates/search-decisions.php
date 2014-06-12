<?php  
	
	$link       = get_ID_by_slug('resultats-recherche'); 
	$categorie  = ( !empty($_REQUEST['categorie']) ? $_REQUEST['categorie'] : NULL);
	$dateStart  = ( !empty($_REQUEST['dateStart']) ? $_REQUEST['dateStart'] : NULL);
	$dateEnd    = ( !empty($_REQUEST['dateEnd']) ? $_REQUEST['dateEnd'] : NULL);
	$star       = ( !empty($_REQUEST['star']) ? $_REQUEST['star'] : 0);	
?>

<div class="row">

	<div class="col-md-4">	
		<label class="searchText">Recherche par mots-clés</label>
		<form role="search" method="post" id="searchform" class="search-form form-inline" action="<?php echo get_permalink($link); ?>">
		    <div class="input-group">
				<input class="search-field form-control" type="search" placeholder="" name="term" value="">
				<input type="hidden" name="search" value="simple" />
				<label class="hide">Rechercher :</label>
				<span class="input-group-btn">
					<button class="search-submit btn btn-blue" type="submit">Ok</button>
				</span>
			</div>
		</form>	
		<br/>	
	</div>
	<div class="col-md-8">
  		<!-- Bloc search -->
  		<div id="filterByDates">
  		  	<label class="searchText">Filtrer par date</label>	         
			<form class="form-inline" id="choixPeriode" method="post" action="<?php echo get_permalink(); ?>" role="form">
				
				<div class="row">
				
					<div class="form-group col-sm-3">
						&nbsp;<input placeholder="Du" type="text" class="form-control" name="dateStart" id="dateStart">
					</div>
					<div class="form-group col-sm-3">
						<input placeholder="au" type="text" class="form-control" name="dateEnd" id="dateEnd">
					</div>					
					<div class="checkbox col-sm-4">
						<label><input value="1" name="star" <?php if($star){echo ' checked="checked" ';} ?> type="checkbox"> Que proposé pour la publication</label>
					</div>
					
					<?php if($categorie){ echo '<input type="hidden" value="'.$categorie.'" name="categorie" />'; } ?>
					
					<div class="col-sm-1 text-right">
						<button type="submit" class="btn btn-default btn-blue">Ok</button>	
					</div>
					
				</div>
							
			</form>			         
  		</div>	
  		
	</div>	
	
	<div class="clearfix"></div>
	<p class="shadow"></p>
	
</div>