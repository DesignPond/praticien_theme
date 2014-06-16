<?php 

// Retrive search terms arguments
$search_terms = $_REQUEST['term']; 				
// Format query string
$format = formatSearch($search_terms);
// Query with params							
$query  = newDecisionSearch($format);
// Page to link decision to
$page_decision = get_ID_by_slug('decision');

// Url params
$terms = http_build_query( array('term' => $format) ,'','&');

?>
	
<div class="row">
  <div class="col-md-12">
    	
    	<h3>Résultats pour : <small><?php echo $searching; ?></small></h3>	
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
			
			<?php echo prepareListDecisions($query , $page_decision , $post->ID , $terms ); ?>	
					
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

