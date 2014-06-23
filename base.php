<?php get_template_part('templates/head'); ?>
<body <?php body_class(); ?>>

  <?php
  
    do_action('get_header');
	
	// Primary menu
    get_template_part('templates/header-top-navbar');
	
	// Homepage header with book
  	if ( is_front_page() ) 
  	{
	  	get_template_part('templates/home', 'header'); 
	} 
  	
  	// Links to two sorts of arrets
	get_template_part('templates/page', 'arrets'); 
	
	// User menu bar
	if(is_user_logged_in())
	{		
		get_template_part('templates/header', 'user'); 
	}
	
	// Sessions for user suscribed to newsletter	
	$suscribedToNewsletter = (!empty($_SESSION['suscribedToNewsletter']) ? $_SESSION['suscribedToNewsletter'] : null );
	if($suscribedToNewsletter)
	{
		get_template_part('templates/header', 'session'); 
	}
	
  ?>

  <div id="mainContent" class="wrap container" role="document">
  
    <div class="content row">
    	
      <!-- MAIN CONTENT -->
      <main class="main <?php echo roots_main_class(); ?>" role="main">
        <?php include roots_template_path(); ?>
      </main><!-- /.main -->
      
      <!-- SIDEBAR -->
      <?php if (roots_display_sidebar()) : ?>
      
        <aside class="sidebar <?php echo roots_sidebar_class(); ?>" role="complementary">
          <?php include roots_sidebar_path(); ?>
        </aside><!-- /.sidebar -->
        
      <?php endif; ?>
      
    </div><!-- /.content -->
    
  </div><!-- /.wrap -->

  <?php get_template_part('templates/footer'); ?>

</body>
</html>
