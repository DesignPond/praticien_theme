<div class="row">
   <div class="col-md-12">	
		<?php
		
			$categorie  = ( !empty($_REQUEST['cat']) ? $_REQUEST['cat'] : NULL);
			$dateStart  = ( !empty($_REQUEST['dateStart']) ? $_REQUEST['dateStart'] : NULL);
			$dateEnd    = ( !empty($_REQUEST['dateEnd']) ? $_REQUEST['dateEnd'] : NULL);
			$star       = ( !empty($_REQUEST['star']) ? $_REQUEST['star'] : 0);
		
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
						
			$returnUrl =  add_query_arg( array( 'categorie' => $category, 'dateStart' => $dateStart, 'dateEnd' => $dateEnd, 'star' => $star) , get_permalink(1143) );
	 	
		?>	
			
		<p><a class="btn btn-sm btn-blue" href="<?php echo $returnUrl; ?>">&lt;&lt; &nbsp;Retour à la liste</a></p>
			
		<div id="arret-text">
			<h4><?php echo $catName; ?></h4>
			<?php echo $texte_nouveaute; ?>
		</div>
		
   </div>
</div>