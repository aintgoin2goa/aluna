
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

	<?php if(is_single() ): ?>
		
		<h1 class="noUnderline"><?php the_title(); ?></h1>
        <p class="date"><?php the_date(); ?></p>
		<?php the_content(); ?>
		
		<p class="tags"><?php the_tags('Tags: ', ', ');?></p>
		<p class="next-post button"><?php next_post_link("%link", __('Next')." &rarr;"); ?>
		<p class="prev-post button"><?php previous_post_link("%link", "&larr; ".__('Previous') ); ?>
		<p style="clear:both"></p>
		<?php comments_template() ;?>
	
	<?php else: ?>
       
	
        <h2 class="noUnderline"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <p class="date"><?php the_date(); ?></p>
		<div class="first"><?php the_excerpt(); ?></div>
		<p class="tags"><?php the_tags('Tags: ', ', ');?></p>
		<p>
            <a href="<?php the_permalink(); ?>" class="button"><img src="<?php url(); ?>/images/read-more.png" style="border:0" alt="" title="read-more" width="117" height="26" class="alignnone size-full wp-image-124"></a>
        </p>
		<p>&nbsp;</p>
		
	<?php endif; ?>
            
           
		   <hr />
          
<?php endwhile; else: ?>
<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
<?php endif; ?>

