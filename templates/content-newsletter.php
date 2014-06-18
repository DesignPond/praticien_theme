<?php

// get links to page
$page_pub    = get_ID_by_slug('inscription-newsletter-derniers-arrets-proposes-pour-la-publication');
$page_nlrcas = get_ID_by_slug('inscription-a-la-newsletter-droit-pour-le-praticien');

$news_nlrcas = add_query_arg( array( 'id' => 6) , get_permalink($page_nlrcas) );

?>

<h1>Inscription newsletter</h1>
<div class="row">
  <div class="col-md-6">
  
  	<a href="<?php echo get_permalink($page_pub); ?>" class="list_newsletter list_newsletter_praticien gradient">	  	
	    <span>
	    	<i>Tribunal fédéral - Jurisprudence</i><br/>
	    	Chaque semaine les derniers arrêts proposés<br/> pour la publication</span>				  			 
  		<img src="<?php echo get_bloginfo('template_directory');?>/assets/img/newsletter_pub.png" alt="publications" />	
  	</a>
  	
  </div>
  
  <div class="col-md-6">
   	  
	<a href="<?php echo $news_nlrcas; ?>" class="list_newsletter list_newsletter_nlrcas">
	  	<span>Responsabilité civile<br/>Assurances sociales<br/>Assurances privées</span>
  		<img src="<?php echo get_bloginfo('template_directory');?>/assets/img/newsletter_nrlcas.png" alt="nlrcas" />					
  	</a>  
  		
  </div>
</div>


