<?php get_header(); ?>

<div id="az-content">

    <input type="checkbox" id="menu-checkbox" />
    <nav class="az-primary-nav" role="navigation">
		 <label for="menu-checkbox" class="toggle-button" onclick></label>
		 <div class="az-clear"></div>
		<?php wp_nav_menu( array('theme_location' => 'az_menu') ); ?>
    </nav>
	
	<?php if (have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>       
        
        
        	<article <?php post_class() ?> id="post-<?php the_ID(); ?>">
            	<h3><a href="<?php the_permalink() ?>"> <?php the_title();?> </a> </h3>        
                               
                <div class="entry">
                	<?php the_excerpt(); ?>
                </div>   
                                
                
                <div class="az-article-meta">
					<em> Опубликовано: </em> <?php echo get_the_date(); ?>  
                    Категория : <?php the_category(',')?>
                    Комментарии : <?php comments_popup_link('Нет комментариев', '1 комментарий','% комментариев'); ?>
                </div>
                   
            </article>
		<hr>
        <?php endwhile; ?>
        <?php else: ?>
        <h2>Извините, ничего не найдено.</h2>
	<?php endif; ?>


</div> <!-- конец az-content-->



<?php get_footer(); ?>