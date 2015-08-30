<?php get_header(); ?>

<div id="az-content">

    <input type="checkbox" id="menu-checkbox" />
    <nav class="az-primary-nav" role="navigation">
		 <label for="menu-checkbox" class="toggle-button" onclick></label>
		 <div class="az-clear"></div>
		<?php wp_nav_menu( array('theme_location' => 'az_menu') ); ?>
    </nav>	
	
	<?php if (have_posts() ) : ?>
	
	<?php if (function_exists('dimox_breadcrumbs')) dimox_breadcrumbs(); ?>
	
		<?php while ( have_posts() ) : the_post(); ?>
        	<article <?php post_class() ?> id="post- <?php the_ID(); ?>">
            	<h3 class="az-posttitle"> <?php the_title();?>  </h3>            
                                
                <div class="entry clearfix">
                    <div class="az-thumbnail"><?php the_post_thumbnail(); ?></div>
                	<div class="az-singlepost"><?php the_content(); ?></div>                    
                </div>             
              
                <div class="az-article-meta">
					<em> Опубликовано: </em> <?php echo get_the_date(); ?>  
                    <?php the_tags('Метки:',',',' ');?>
                    Категория : <?php the_category(',')?>
                    <?php comments_popup_link('Нет комментариев', '1 комментарий','% комментариев'); ?>
                </div>
                
                <?php wp_link_pages(array('before' => 'Pages: ', 'next_or_number' => 'number')); ?>
                   
            </article>
            
			<hr>
			
            <div class="az-comments">
				<?php comments_template(); ?>
            </div>
        
			
			
        <?php endwhile; ?>
        <?php else: ?>
        <h2>Извините, ничего не найдено.</h2>
	<?php endif; ?>


</div> <!-- конец az-content-->



<?php get_footer(); ?>