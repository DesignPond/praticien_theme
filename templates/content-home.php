<?php

	//$lastDayUpdated = lastDayUpdated();

?>

<div id="homeContent">
	<div class="row">
	  <div class="col-md-4">
		  <div class="bloc tf_bloc">
			  <h3>Derniers arrêts rendus</h3>
			  <h4>Tribunal fédéral - Jurisprudence</h4>
			  <p>Liste des dernières décisions du <?php echo lastDayUpdated(); ?></p>
			  <a target="_blank" href="http://www.bger.ch/fr/index/juridiction/jurisdiction-inherit-template/jurisdiction-recht/jurisdiction-recht-urteile2000neu.htm">Voir la liste</a>
			  <p class="calendar">Publications du <?php echo lastDayUpdated(); ?></p>
		  </div>
	  </div>
	  <div class="col-md-4">
		  <div class="bloc tf_bloc">
			  <h3>Arrêts destinés à la publication</h3>
			  <h4>Tribunal fédéral - Jurisprudence</h4>
			  <p>Liste des décisions destinées à la publication</p>
			  <a href="">Voir la liste</a>
			  <p class="calendar">Publications du <?php echo lastDayUpdated(); ?></p>
		  </div>
	  </div>
	  
	  			  
	  <!-- Bloc ad newsletter NLRCAS -->
	  <div class="col-md-4">
		  <div class="bloc newsletterHome">
		  	  <h3>Newsletter</h3>
		  	  
			  <div class="row">
			  	 <div class="col-md-6">
			  	 	Responsabilité civile<br/>
					Assurances sociales<br/>
					Assurances privées
			  	 </div>
			  	 <div class="col-md-6">
			  	 	<img src="<?php echo get_bloginfo('template_directory');?>/assets/img/newsletter_logo.png" />
			  	 </div>
			  </div>

			  <div class="row newsletterLinks">
			  	 <div class="col-md-6">
			  	 	<a class="btn btn-default btn-xs pull-right" href="">Inscription</a>
			  	 	<a class="btn btn-praticien btn-xs pull-right" href="">Assurances sociales</a>
			  	 </div>
			  	 <div class="col-md-6">
			  	 	<a class="btn btn-default btn-xs" href="">Archives</a>
			  	 	<a class="btn btn-praticien btn-xs" href="">Responsabilité civile</a>
			  	 </div>
			  </div>
			  
		  </div>
	  </div>
	</div>
</div>