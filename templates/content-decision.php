<div class="row">
   <div class="col-md-12">	
		<?php
		
			$categorie  = ( !empty($_REQUEST['cat']) ? $_REQUEST['cat'] : NULL);
			$dateStart  = ( !empty($_REQUEST['dateStart']) ? $_REQUEST['dateStart'] : NULL);
			$dateEnd    = ( !empty($_REQUEST['dateEnd']) ? $_REQUEST['dateEnd'] : NULL);
			$star       = ( !empty($_REQUEST['star']) ? $_REQUEST['star'] : 0);
			
			$term       = ( !empty($_REQUEST['term']) ? $_REQUEST['term'] : NULL);
			$retour     = ( !empty($_REQUEST['retour']) ? $_REQUEST['retour'] : NULL);
		
			$reference = ( !empty($_REQUEST['arret']) ? $_REQUEST['arret'] : '');
			
			$decision  = getDecisionByRef($reference);
			
			$catName         = $decision->nameCat;
			$subcatName      = $decision->nameSub;
			$texte_nouveaute = $decision->texte_nouveaute;	
						
			$returnUrl =  add_query_arg( array( 'categorie' => $category, 'term' => $term  ,'dateStart' => $dateStart, 'dateEnd' => $dateEnd, 'star' => $star) , get_permalink($retour) );
	 	
		?>	
			
		<p><a class="btn btn-sm btn-blue" href="<?php echo $returnUrl; ?>">&lt;&lt; &nbsp;Retour à la liste</a></p>
			
		<div id="arret-text">
			<h4><?php echo $catName; ?></h4>
			<h5><?php echo $subcatName; ?></h5>
			<?php echo $texte_nouveaute; ?>
		</div>
		
   </div>
</div>