<div class="row">
   <div class="col-md-12">	
	<?php
	
		$reference = ( !empty($_REQUEST['arret']) ? $_REQUEST['arret'] : '');
		
		$decision  = getDecisionByRef($reference);
		
		
		$catName             =  $decision->nameCat;
		$subcatName          =  $decision->nameSub;
		$id_nouveaute        =  $decision->id_nouveaute;
		$categorie_nouveaute =  $decision->categorie_nouveaute;
		$texte_nouveaute     =  $decision->texte_nouveaute;
		$numero_nouveaute    =  $decision->numero_nouveaute;
		$link_nouveaute      =  $decision->link_nouveaute;
		$datep_nouveaute     =  $decision->datep_nouveaute;
		$dated_nouveaute     =  $decision->dated_nouveaute;
 	
	?>
		
		<div id="arret-text">
			<h4><?php echo $catName; ?></h4>
			<?php echo $texte_nouveaute; ?>
		</div>
		
   </div>
</div>