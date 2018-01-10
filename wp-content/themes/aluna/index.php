<?php get_header(); ?>

<div id="body">
	<div id="header">
    	<div id="logo">
        	<a href="<?php bloginfo('url'); ?>"><img src="<?php url(); ?>/images/logo.png" alt="Logo" width="334" height="91" /></a>
        </div><!-- #logo -->
        <p id="strap">
        	<?php _e('There is no life without thought'); ?>
        </p><!-- #strap -->
       <?php get_template_part('languageswitcher'); ?><div id="fb" style="float:right; margin-right:250px; text-decoration:none;"><a href="http://www.facebook.com/AlunaMovie" style="text-decoration:none; border-bottom:none;" target="_blank"><img  border="0" src="<?php url(); ?>/images/facebook.png" /></a></div>
	   <div id="tw" style="float:right; margin-right:10px; text-decoration:none;"><a href="http://twitter.com/alunathemovie" style="text-decoration:none; border-bottom:none;" target="_blank"><img  border="0" src="<?php url(); ?>/images/tweeter.png" /></a></div>
                <h4 id="tobereleased">TO BE RELEASED IN EARLY 2012<?php //_e('TO BE RELEASED IN EARLY 2012'); ?></h4>

    </div><!-- #header -->
    
      
    <div id="content">
    
        <div id="home-left" class="content-box">


			<?php
			/* Run the loop to output the posts.
			 * If you want to overload this in a child theme then include a file
			 * called loop-index.php and that will be used instead.
			 */
			 get_template_part( 'loop', 'index' );
			?>
 <?php if( !is_single() ){
				wp_paginate();
			}?> 
	   </div><!-- #home-left -->
	   
	   <div class="blog-right content-box">
	  
			<ul id="nav">
				<?php wp_list_pages("title_li=&depth=1"); ?>
			</ul>
	   </div>
	

<?php get_footer(); ?>
