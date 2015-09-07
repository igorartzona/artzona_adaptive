<?php

if (comments_open()) {
	if (get_comments_number() == 0) echo "<h4 class='pre_comment'>Нет комментариев</h4>";
	else { ?> 
		<div class="az-comments_in">
			<h4 class="pre_comment">Комментарии :</h4><hr>
			<?php
				// стандартный вывод комментариев
				
				/*-----начало функции вывода----*/
				function az_comment($comment, $args, $depth){
					$GLOBALS['comment'] = $comment; ?>
						<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
						<div id="comment-<?php comment_ID(); ?>">
							<span class="comment-author vcard">
								<?php printf(__('<span class="fn">%s </span> '), get_comment_author_link()) ?> 
							</span>
							<?php if ($comment->comment_approved == '0') : ?>
							<em><?php _e('Ваш комментарий будет опубликован после одобрения администратором.') ?></em>
							<br />
							<?php endif; ?>	  
							<span class="comment-meta commentmetadata">[<?php printf(__('%1$s at %2$s'), get_comment_date(), get_comment_time()) ?><?php edit_comment_link(__('(Edit)'),'  ','') ?>]</span>
							<div class="az-commenttext">
								<?php comment_text() ?>
							</div>	<!-- конец az-commenttext-->					
							<div class="reply">
								<hr>
								<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
							</div> <!-- конец reply-->
						</div> <!-- конец id-comment-->
			<?php }
			/*---конец функции вызова-------*/
			?>
				<ul class="az-commentlist">
					<?php 
					$args = array(
						'walker'            => null,
						'max_depth'         => '5',
						'style'             => 'ul',
						'callback'          => 'az_comment',
						'end-callback'      => null,
						'type'              => 'all',
						'page'              => '',
						'per_page'          => '',
						'avatar_size'       => 32,
						'reverse_top_level' => null,
						'reverse_children'  => null, 
					);
					
					wp_list_comments ( $args );
					?>
					
				</ul>
	<?php
	}	
		$fields = array(
		  'author' => '<p class="comment-form-author"><label for="author">' . __( 'Name' ) . '</label> '.($req ? '<span class="required">*</span>' : '') . '<br><input type="text" id="author" name="author" class="author" value="' . esc_attr($commenter['comment_author']) . '" placeholder="" pattern="[A-Za-zА-Яа-я]{3,}" maxlength="30" autocomplete="on" tabindex="1" required' . $aria_req . '></p>',
		 'email'  => '<p class="comment-form-email"><label for="email">' . __( 'Email' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) .'<br><input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>',
		);
	 
		$args = array(
		  'comment_notes_before'=>'',
		  'comment_notes_after' => '',
		  'comment_field' => '</label><p><textarea id="comment" name="comment" class="comment-form" rows="8" aria-required="true" placeholder="Текст сообщения..."></textarea></p>',
		  'label_submit' => 'Отправить',
		  'fields' => apply_filters('comment_form_default_fields', $fields)
		);
		comment_form($args);
		
} else echo "<h4>Комментирование данного материала отключено</h4>";
	?>
	





<?php
/*---заготовка для добавления нового поля
function add_comment_fields($fields) {	  
	    $fields['nick'] = '<p class="comment-form-nick"><label for="nick">' . __( 'Nick' ) . '</label>' .
	    '<br><input id="nick" name="nick" type="text" size="30" /></p>';
	    return $fields;
	  
}
add_filter('comment_form_default_fields','add_comment_fields');
*/
?>    

     
   
