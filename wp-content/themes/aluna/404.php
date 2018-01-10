<?php get_header(); ?>

<div id="body">
	<div id="header">
    	<div id="logo">
        	<a href="<?php bloginfo('url'); ?>"><img src="<?php url(); ?>/images/logo.png" alt="Logo" width="334" height="91" /></a>
        </div><!-- #logo -->
        <p id="strap">
        	<?php _e('There is no life without thought'); ?>
        </p><!-- #strap -->
       <?php get_template_part('languageswitcher'); ?>
           

    </div><!-- #header -->
    
    <div id="content">
    
        </div><!-- #left-column -->

    
        <div id="right-column" class="content-box">
			<h1><?php _e('Page Not Found'); ?></h1>
			<p><?php _e('Sorry, the page you requested is not here'); ?></p>
			<p><a href="<?php bloginfo('url'); ?>"><?php _e('Go to homepage'); ?></a></p>
		
	
			
			<p><?php _e('Page Not Found'); ?></p>
			
        </div><!-- #home-left -->
        
    
    	<?php get_footer(); ?>
        
    
