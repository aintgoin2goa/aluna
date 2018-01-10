<?php
/*
Template Name: Homepage
*/
?>

<?php get_header(); ?>

<div id="body" class="homepage-alternate">



	<div id="header" class="homepage">



    	<div id="logo">



        	<a href="#"><img src="<?php url(); ?>/images/logo.png" alt="Logo" width="334" height="91" /></a>



        </div><!-- #logo -->



        <p id="strap">



        	There is no life without thought



        </p><!-- #strap -->



    <div id="youtube">
		<iframe src="http://player.vimeo.com/video/36960419?title=0&amp;byline=0&amp;portrait=0" width="549" height="309" frameborder="0"></iframe>
	</div>

	

<?php

    function getHeadlines(){
    	$posts = get_posts(array('posts_per_page'   => 10));

    	$headlines = array();
	    foreach($posts as $post){
	    	$custom_fields = get_post_custom($post->ID);
	    	if(array_key_exists("excerpt_text", $custom_fields) && count($custom_fields["excerpt_text"]) > 0){
	    		$headlines[] = array("excerpt" => $custom_fields["excerpt_text"][0], "id" => $post->ID);

	    		if(count($headlines) === 3){
	    			return $headlines;
	    		}
	    	}
	    }

	    return count($headlines) > 0 ? $headlines : false;
    }

?>




	<div id="release-info" class="content-box">
	    <h2>Aluna is now available for you to watch, download and share. </h2>
	    <p>You can download it in many territories, and it is available on DVD from Amazon in
the UK and the USA.  Click on this link to find it:</p>
        <p><a href="www.alunathemovie.com/find/">www.alunathemovie.com/find</a></p>
        <p>Other countries, including Latin America, will follow soon.</p>
	</div>
	





       <?php get_template_part('languageswitcher'); ?>



	   <div id="fb" style="float:right; margin-right:250px; text-decoration:none;">
	   	<a href="http://www.facebook.com/AlunaMovie" style="text-decoration:none; border-bottom:none;" target="_blank">
	   		<img  border="0" src="<?php url(); ?>/images/facebook.png" />
	   	</a>
	   </div>

	   <div id="tw" style="float:right; margin-right:10px; text-decoration:none;">
	   		<a href="http://twitter.com/alunathemovie" style="text-decoration:none; border-bottom:none;" target="_blank">
	   			<img  border="0" src="<?php url(); ?>/images/tweeter.png" />
	   		</a>
	   	</div>

		<div style="clear:both"></div>

			<ul id="nav" class="content-box">

			<?php wp_list_pages("title_li=&depth=1&exclude=8,522,516"); ?>

		</ul>

    </div><!-- #header -->


    <div id="content">



<!--iframe width='640' height='360' frameborder="0" scrolling="no" src="http://edge1.streamingtank.tv/mission/5421674e186d6bb92d5b1370/"></iframe-->	



	<div id="home-left">

     

 <div class="content-box">
 			


		  		 <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
					<?php the_content(); ?>
				<?php endwhile; else: ?>
					<p><?php _e('Page Not Found'); ?></p>
				<?php endif; ?>

                  <p>&nbsp;</p>
				<hr s/>
		<p>
			<a href="http://tairona.myzen.co.uk/index.php/about/support_the_trust/" style="border:0" target="_blank">
				<img style="margin-left:5px; margin-top:17px;border:0; float:none" src="http://tairona.myzen.co.uk/images/support-the-trust.png" border="0" />
			</a>
		</p>
		<p>
        		Donors who contribute a minimum of &pound;26 (&pound;27 outside the UK) to the Trust receive a &ldquo;thank you&rdquo; DVD of the original documentary 'From the Heart of the World - the Elder Brothers' Warning' in PAL or NTSC format as requested.</span><br />
        		<a href="http://tairona.myzen.co.uk/index.php/about/support_the_trust/" title="support the trust" target="_blank">
        			<strong>Please click here to support the Trust</strong>
        		</a>
        </p>

		  </div>
		

	</div> <!-- #home-left -->



	



	<div id="home-right">

		<!--div id="opt-in">
			<?php echo do_shortcode( '[contact-form-7 id="439" title="Screening Signup Form"]' ); ?>
		</di-->

		<div class="blog-right content-box">
			<!--
			<h2><?php _e("Alan's Colombia Diary"); ?></h2>
			<?php

			$myposts = get_posts('numberposts=4&cat=5');

				foreach($myposts as $post) : ?>

					<h3><?php echo $post->post_title; ?></h3>
					<p>
                        <?php echo $post->post_excerpt; ?>
						</p>

				<p>
					<a href="<?php echo get_permalink($post->id); ?>" class="button"><img src="<?php url(); ?>/images/read-more.png" alt="Read More" /></a>
				</p>

			<?php endforeach; ?>

			-->
			<h2><?php _e('Latest News'); ?></h2>
			<?php

			$myposts = get_posts('numberposts=4&cat=1');

				foreach($myposts as $post) : ?>

					<h3><?php echo $post->post_title; ?></h3>

				<p>
					<a href="<?php echo get_permalink($post->id); ?>" class="button"><img src="<?php url(); ?>/images/read-more.png" alt="Read More" /></a>
				</p>

			<?php endforeach; ?>



		
        </div>

		 
		

	</div><!-- #home-right -->	


  <?php get_footer(); ?>  



    