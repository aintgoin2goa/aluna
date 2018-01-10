<?php
/*
Template Name: Subpage
*/
?>

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
                <div id="fb" style="float:right; margin-right:250px; text-decoration:none;"><a href="http://www.facebook.com/AlunaMovie" style="text-decoration:none; border-bottom:none;" target="_blank"><img  border="0" src="<?php url(); ?>/images/facebook.png" /></a></div>
				 <div id="tw" style="float:right; margin-right:10px; text-decoration:none;"><a href="http://twitter.com/alunathemovie" style="text-decoration:none; border-bottom:none;" target="_blank"><img  border="0" src="<?php url(); ?>/images/tweeter.png" /></a></div>
				<h4 id="tobereleased">TO BE RELEASED IN EARLY 2012<?php //_e('TO BE RELEASED AT THE END OF 2011'); ?></h4>

    </div><!-- #header -->
    
    <div id="content">
    
	     <div id="left-column">
<ul id="nav" class="content-box">
<?php wp_list_pages("title_li=&depth=1"); ?>
</ul>
   
	<?php
	 $id = get_post_meta($post->ID, 'preview_page', true);
	 $nextpage = get_page($id);
	 $fields = get_post_custom($nextpage->ID);
	 if($id):
	?>
         	<div class="content-box">
                <h3><?php echo $nextpage->post_title; ?></h3>
                	<a href="<?php echo get_permalink($id); ?>"></a>
                    <p><?php echo $fields['excerpt_text'][0]; ?></p>
                <?php echo $nextpage->post_excerpt; ?>
                <p class="noBottom">
                    <a href="<?php echo get_permalink($id); ?>" class="button"><img src="<?php url(); ?>/images/read-more.png" alt="Read More" /></a>
                </p>
                </div><!--.content-box -->
	<?php endif; ?>
        </div><!-- #left-column -->

    
        <div id="right-column" class="content-box">
          <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		    <h1><?php the_title(); ?></h1>
			<?php the_content(); ?>
		
	
			<?php endwhile; else: ?>
			<p><?php _e('Page Not Found'); ?></p>
			<?php endif; ?>
        </div><!-- #home-left -->
        
    
    	<?php get_footer(); ?>
        
    
