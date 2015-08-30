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
				<div class="az-article-meta">
					<span class="az-posttitle-date"><?php echo get_the_date(); ?></span>
				</div>
            	<h3 class="az-posttitle"><a href="<?php the_permalink() ?>"> <?php the_title();?> </a></h3> 	
				
                <div class="entry">
                    <div class="az-thumbnail"><a href="<?php the_permalink() ?>"><?php the_post_thumbnail(); ?></a></div>
                	<div class="az-post clearfix">					
						<?php	
						     /*---Игноирирование тэга "more" в прилепленной записи ---*/
							if (is_sticky()) { global $more; $more = 1;}  else $more =0;
							
							/*---Обработка записей перед выводом - если есть цитаты, выводим, иначе - полный текст ---*/
							if(has_excerpt()) { 
							  $length = apply_filters('excerpt_length',40);
							  echo wp_trim_words(get_the_excerpt(),$length); 
							} else { 							  
							  the_content('Читать далее&rArr;');
							}						
						?>
					</div>                   
                </div>                                
                                
                <div class="az-article-meta">
                    Категория : <?php the_category(',')?>
                    Комментарии : <?php comments_popup_link('Нет комментариев', '1 комментарий','% комментариев'); ?>
                </div>                        
                             
            </article>         
        
		<hr>          
            
            
        <?php endwhile; ?>
        <?php else: ?>
        <h2>Извините, ничего не найдено.</h2>
	<?php endif; ?>

<div class="az-pagi"><?php if (function_exists('wp_corenavi')) wp_corenavi(); ?> </div>

</div> <!-- конец az-content-->



<?php get_footer(); ?>