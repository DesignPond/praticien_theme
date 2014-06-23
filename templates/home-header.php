<?php $apropos = get_ID_by_slug('a-propos'); ?>
<div id="home-header">
	<div class="container">
	
		<div class="row">				
			<div class="col-sm-3">
				<div id="buyTheBook">
					<div class="row">
						<a href="http://www.publications-droit.ch/#/cat/publications/item/229" target="_blank">
							<img src="<?php echo get_bloginfo('template_directory');?>/assets/img/book.png" alt="droit pour le praticien" />
						</a>
					</div>	
				</div>					
			</div>						
			<div class="col-sm-8 buythebook">					
				<h1>Accès</h1>
				<h2>Le droit pour le praticien</h2>
				
				<p>Avec l’achat, pour seulement CHF 79, de la dernière édition de l’ouvrage <strong>Le droit pour le praticien</strong>, vous pourrez accéder, 
				durant l’année en cours, au site grâce au code indiqué sur le livre. <br/>
				Vous y trouverez la jurisprudence du Tribunal fédéral, classée par thèmes et consultable par mots-clés.</p>
				
				<p>
					<a href="http://www.publications-droit.ch/#/cat/publications/item/229" target="_blank" class="btn btn-buy">Acheter</a>
					<a href="<?php echo get_permalink($apropos); ?>" class="btn btn-default">En savoir plus</a>
				</p>				
			</div>								
		</div>
					
	</div>						
</div>

