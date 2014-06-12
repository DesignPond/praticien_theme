<?php $search = $_REQUEST['term']; ?>
	
<div class="row">
  <div class="col-md-12">
    	
    	<h3>Résultats pour : <small><?php echo $search; ?></small></h3>	
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
			
			<?php 
			
				// Query with params
				$query = decisionSearch($search);
				
				// Page to link decision to
				$page_decision = get_ID_by_slug('decision');
				
				// list
				echo prepareListDecisions($query , $page_decision , $post->ID , $search );
				
			 ?>	
					
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

</div>

