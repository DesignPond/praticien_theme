<?php  $link = get_ID_by_slug('resultats-recherche'); ?>

<div class="row">

	<div class="col-md-3" id="searchKey">	
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
	</div>
	
	<div class="col-md-9">	
	
		<form role="search" method="post" id="searchDetail" action="<?php echo get_permalink($link); ?>">

			<!-- clone terms inputs -->
			<a id="cloneForm" data-toggle="tooltip" data-placement="right" title="Ajouter des termes de recherche" href=""><span class="glyphicon glyphicon-plus"></span></a>
			<a id="deleteForm" data-toggle="tooltip" data-placement="right" title="Supprimer les termes de recherche" href=""><span class="glyphicon glyphicon-minus"></span></a>
			
			<label class="searchText">Recherche par article &nbsp;
				<span id="infoTip" data-toggle="tooltip" data-placement="right" 
					title="Exemple = article : <strong>405</strong> , loi: <strong>CPC</strong> , alinéa: <strong>1</strong>, chiffre: <strong>2</strong>, lettre: <strong>c</strong>" 
					class="glyphicon glyphicon-info-sign"></span><!-- info on search terms -->	
			</label>
			
			<div id="containerFormAtf">  
			
				<div class="formAtf">
					<p class="search-term"><input type="text" class="default-value form-control terms" name="article[]" rel="article" value="" placeholder="article"  /></p>
					<p class="search-term"><input type="text" class="default-value form-control terms" name="loi[]" 	rel="loi"     value="" placeholder="loi"  /></p>
					<p class="search-term"><input type="text" class="default-value form-control terms" name="alinea[]"  rel="alinea"  value="" placeholder="alinea"  /></p>
					<p class="search-term"><input type="text" class="default-value form-control terms" name="chiffre[]" rel="chiffre" value="" placeholder="chiffre"  /></p>
					<p class="search-term "><input type="text" class="default-value form-control terms" name="lettre[]"  rel="lettre"  value="" placeholder="lettre"  /></p>
				</div>
				<?php
				
					$terms = get_terms("annee", 'hide_empty=0');
					$count = count($terms);
					
					if ( $count > 0 )
					{
						echo '<p class="search-term search-term-select"><select name="annee" class="annee form-control" id="searchYear">';
						echo '<option value="">Toutes</option>';
						
						foreach ( $terms as $term ) 
						{
							echo '<option value="' . $term->slug .'">' . $term->name . ' </option>';
						}
						echo '</select></p>';
					}
				?>
				
				<input type="hidden" name="search" value="terms" />			
				<p class="search-term"><input type="submit" value="Ok" id="searchBtn" class="btn btn-blue" /></p>
			
				<div class="clearfix"></div>
			</div>
			
			<!-- container for cloned forms -->
			<div class="newForms"></div>
					
		</form>
	
	</div>
	<div class="clearfix"></div>
	<p class="shadow"></p>
	
</div>