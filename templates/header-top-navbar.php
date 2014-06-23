<header id="head" class="banner navbar navbar-default navbar-static-top" role="banner">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo home_url(); ?>/"><strong>Droit</strong> pour le <b>Praticien</b></a>
    </div>

    <nav class="collapse navbar-collapse" role="navigation">
      
      <ul class="nav navbar-nav logos navbar-right">
        <li>
        	<a target="_blank" href="http://www.unine.ch">
				<img src="<?php echo get_bloginfo('template_directory');?>/assets/img/unine.png"  alt="unine logo">
			</a>
		</li>   			
        <li>
	        <a target="_blank" href="http://www.unine.ch/cemaj">
				<img src="<?php echo get_bloginfo('template_directory');?>/assets/img/cemaj.png"  alt="unine cemaj">
			</a>
		</li>   	
      </ul>
    
      <?php
        if (has_nav_menu('primary_navigation')) :
          wp_nav_menu(array('theme_location' => 'primary_navigation', 'menu_class' => 'nav navbar-nav main-nav navbar-right'));
        endif;
      ?>

    </nav>
     
  </div>
</header>