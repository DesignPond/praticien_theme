<div class="row">
  <div class="col-md-6">
  	<h3>Inscription newsletter</h3>
	<?php echo do_shortcode('[unsuscribe_newsletter newsletter="suscribe"]'); ?>
  </div>
  <div class="col-md-6">
  <?php
  	
  	  $campaigns = getAllCampaignName();
  	  
	  echo '<pre>';
	  print_r($campaigns);
	  echo '</pre>';

  ?>
  </div>
</div>


