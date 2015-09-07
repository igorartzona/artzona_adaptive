<?php

/*-------удаление мусора из секции <head>----------*/
function remheadlink() {
	     remove_action('wp_head', 'rsd_link');
	     remove_action('wp_head', 'wlwmanifest_link');
  	     remove_action('wp_head', 'wp_generator');
     	 remove_action( 'wp_head', 'wp_shortlink_wp_head');
	     remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head');
	     remove_action( 'wp_head', 'feed_links_extra', 3);
	}

add_action('init', 'remheadlink');

/*------------ Отключаем лишние стили из Contact Form 7 на страницах без форм -------------*/
$allowed_cf7_pages_or_posts = array();
$allowed_cf7_pages_or_posts[] = 'send-message';
 
add_action( 'wp_print_styles', 'cf7_deregister_styles', 100 );
function cf7_deregister_styles() {
    global $allowed_cf7_pages_or_posts;
    if ( ! is_page( $allowed_cf7_pages_or_posts ) && ! is_single( $allowed_cf7_pages_or_posts ) ) {
        wp_deregister_style( 'contact-form-7' );
    }  
}
 
add_action( 'wp_print_scripts', 'cf7_deregister_scripts', 100 );
function cf7_deregister_scripts() {
    global $allowed_cf7_pages_or_posts;
    if ( ! is_page( $allowed_cf7_pages_or_posts ) && ! is_single( $allowed_cf7_pages_or_posts ) ) {
        wp_deregister_script( 'contact-form-7' );
    }  
}



/*--------Замена логотипа на странице входа-----------------*/
function loginLogo() {
    echo '<style type="text/css">
           #login h1 a { background-image:url('.get_bloginfo('template_directory').'/img/logo.png) !important; 
		   height:150px;
		   width:330px;
		   background-size:330px 150px;
		   }
    </style>';
}

 
add_action('login_head', 'loginLogo');

/*----------------Отключение редакций----------------------*/
function my_revisions_to_keep( $revisions ) {
    return 0;
}
add_filter( 'wp_revisions_to_keep', 'my_revisions_to_keep' );

/*--------Поддержка настройки фона-----------------*/
$defaults = array( 
	'default-color' => '',
 	'default-image' => '', 
 	'wp-head-callback' => '_custom_background_cb', 
 	'admin-head-callback' => '', 'admin-preview-callback' => '' 
 ); 
add_theme_support( 'custom-background', $defaults );

/*--------Поддержка настройки логотипа-----------------*/
$args = array(  
  'uploads'       => true,
);
add_theme_support( 'custom-header', $args );

//Добавление поддержки миниатюр
add_theme_support('post-thumbnails');
set_post_thumbnail_size(320, 200);

//html5-тэги в комментариях и поиске
add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );

/*--------Регистрация меню----------------------*/
register_nav_menus( 
	array('az_menu' => 'Основное меню')
 );

/*----Регистрация сайдбара---*/
if (function_exists('register_sidebar')) {
	register_sidebar(array(
		'name' => 'Sidebar',
		'id' => 'sidebar',
		'description' => 'Сайдбар',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<div class="az-widget">',
		'after_title' => '</div>',
	));
}

/*---Изменение троеточия при показе неполных записей (the_excerpt)----*/

function new_excerpt_more($more){
	global $post;
	return '... <br><a href="'.get_permalink($post->ID).'"><span style="font-size:0.8em;padding-left:1%;"> Читать далее&rArr;</span></a>';
}
add_filter('excerpt_more','new_excerpt_more');

/*--- Обрезка the content ---*/
function content($limit) {
  $content = explode(' ', get_the_content(), $limit);
  if (count($content)>=$limit) {
    array_pop($content);
    $content = implode(" ",$content).'...<br><a href="'.get_permalink($post->ID).'"><span style="font-size:0.8em;padding-left:1%;"> Читать далее&rArr;</span></a>';
  } else {
    $content = implode(" ",$content);
  }           
  $content = preg_replace('/\[.+\]/','', $content);
  $content = apply_filters('the_content', $content);
  $content = str_replace(']]>', ']]&gt;', $content);
  return $content;
}

/*---функция проверки картинки заголовка (для совместимости со старыми версиями WP)---*/
function az_has_header_image() { return (bool) get_header_image(); }




// Функция пагинации
function wp_corenavi() {  
  global $wp_query, $wp_rewrite;  
  $pages = '';  
  $max = $wp_query->max_num_pages;  
  if (!$current = get_query_var('paged')) $current = 1;  
  $a['base'] = str_replace(999999999, '%#%', get_pagenum_link(999999999));  
  $a['total'] = $max;  
  $a['current'] = $current;  
  
  $total = 0; //1 - выводить текст "Страница N из N", 0 - не выводить  
  $a['mid_size'] = 3; //сколько ссылок показывать слева и справа от текущей  
  $a['end_size'] = 1; //сколько ссылок показывать в начале и в конце  
  $a['prev_text'] = '&laquo;'; //текст ссылки "Предыдущая страница"  
  $a['next_text'] = '&raquo;'; //текст ссылки "Следующая страница"  
  
  if ($max > 1) echo '<div class="navigation">';  
  if ($total == 1 && $max > 1) $pages = '<span class="pages">Страница ' . $current . ' из ' . $max . '</span>'."\r\n";  
  echo $pages . paginate_links($a);  
  if ($max > 1) echo '</div>';  
}  

/*-----------Отображение миниатюр в админке--------------------*/
if ( !function_exists('fb_AddThumbColumn') && function_exists('add_theme_support') ) {
 
    // for post and page
    add_theme_support('post-thumbnails', array( 'post', 'page' ) );
 
    function fb_AddThumbColumn($cols) {
 
        $cols['thumbnail'] = __('Thumbnail');
 
        return $cols;
    }
 
    function fb_AddThumbValue($column_name, $post_id) {
 
            $width = (int) 75;
            $height = (int) 75;
 
            if ( 'thumbnail' == $column_name ) {
                // thumbnail of WP 2.9
                $thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );
                // image from gallery
                $attachments = get_children( array('post_parent' => $post_id, 'post_type' => 'attachment', 'post_mime_type' => 'image') );
                if ($thumbnail_id)
                    $thumb = wp_get_attachment_image( $thumbnail_id, array($width, $height), true );
                elseif ($attachments) {
                    foreach ( $attachments as $attachment_id => $attachment ) {
                        $thumb = wp_get_attachment_image( $attachment_id, array($width, $height), true );
                    }
                }
                    if ( isset($thumb) && $thumb ) {
                        echo $thumb;
                    } else {
                        echo __('None');
                    }
            }
    }
 
    // for posts
    add_filter( 'manage_posts_columns', 'fb_AddThumbColumn' );
    add_action( 'manage_posts_custom_column', 'fb_AddThumbValue', 10, 2 );
 
    // for pages
    add_filter( 'manage_pages_columns', 'fb_AddThumbColumn' );
    add_action( 'manage_pages_custom_column', 'fb_AddThumbValue', 10, 2 );
}

//Отключение полей в форме комментариев

function remove_comment_fields($fields) {
unset($fields['url']);
return $fields;
}
add_filter('comment_form_default_fields', 'remove_comment_fields');

//Подключение genericons
function my_scripts_and_styles() {
    wp_enqueue_style( 'genericons', get_template_directory_uri() . '/genericons/genericons.css' );
}
add_action( 'wp_enqueue_scripts', 'my_scripts_and_styles' );

/*
 * "Хлебные крошки" для WordPress
 * автор: Dimox
 * версия: 2015.05.21
*/
function dimox_breadcrumbs() {

 /* === ОПЦИИ === */
 $text['home'] = 'Главная'; // текст ссылки "Главная"
 $text['category'] = 'Архив рубрики "%s"'; // текст для страницы рубрики
 $text['search'] = 'Результаты поиска по запросу "%s"'; // текст для страницы с результатами поиска
 $text['tag'] = 'Записи с тегом "%s"'; // текст для страницы тега
 $text['author'] = 'Статьи автора %s'; // текст для страницы автора
 $text['404'] = 'Ошибка 404'; // текст для страницы 404
 $text['page'] = 'Страница %s'; // текст 'Страница N'
 $text['cpage'] = 'Страница комментариев %s'; // текст 'Страница комментариев N'

 $delimiter = '›'; // разделитель между "крошками"
 $delim_before = '<span class="divider">'; // тег перед разделителем
 $delim_after = '</span>'; // тег после разделителя
 $show_home_link = 1; // 1 - показывать ссылку "Главная", 0 - не показывать
 $show_on_home = 0; // 1 - показывать "хлебные крошки" на главной странице, 0 - не показывать
 $show_title = 1; // 1 - показывать подсказку (title) для ссылок, 0 - не показывать
 $show_current = 1; // 1 - показывать название текущей страницы, 0 - не показывать
 $before = '<span class="current">'; // тег перед текущей "крошкой"
 $after = '</span>'; // тег после текущей "крошки"
 /* === КОНЕЦ ОПЦИЙ === */

 global $post;
 $home_link = home_url('/');
 $link_before = '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">';
 $link_after = '</span>';
 $link_attr = ' itemprop="url"';
 $link_in_before = '<span itemprop="title">';
 $link_in_after = '</span>';
 $link = $link_before . '<a href="%1$s"' . $link_attr . '>' . $link_in_before . '%2$s' . $link_in_after . '</a>' . $link_after;
 $frontpage_id = get_option('page_on_front');
 $parent_id = $post->post_parent;
 $delimiter = ' ' . $delim_before . $delimiter . $delim_after . ' ';

 if (is_home() || is_front_page()) {

 if ($show_on_home == 1) echo '<div class="breadcrumbs"><a href="' . $home_link . '">' . $text['home'] . '</a></div>';

 } else {

 echo '<div class="breadcrumbs">';
 if ($show_home_link == 1) echo sprintf($link, $home_link, $text['home']);

 if ( is_category() ) {
 $cat = get_category(get_query_var('cat'), false);
 if ($cat->parent != 0) {
 $cats = get_category_parents($cat->parent, TRUE, $delimiter);
 $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
 $cats = preg_replace('#<a([^>]+)>([^<]+)<\/a>#', $link_before . '<a$1' . $link_attr .'>' . $link_in_before . '$2' . $link_in_after .'</a>' . $link_after, $cats);
 if ($show_title == 0) $cats = preg_replace('/ title="(.*?)"/', '', $cats);
 if ($show_home_link == 1) echo $delimiter;
 echo $cats;
 }
 if ( get_query_var('paged') ) {
 $cat = $cat->cat_ID;
 echo $delimiter . sprintf($link, get_category_link($cat), get_cat_name($cat)) . $delimiter . $before . sprintf($text['page'], get_query_var('paged')) . $after;
 } else {
 if ($show_current == 1) echo $delimiter . $before . sprintf($text['category'], single_cat_title('', false)) . $after;
 }

 } elseif ( is_search() ) {
 if ($show_home_link == 1) echo $delimiter;
 echo $before . sprintf($text['search'], get_search_query()) . $after;

 } elseif ( is_day() ) {
 if ($show_home_link == 1) echo $delimiter;
 echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
 echo sprintf($link, get_month_link(get_the_time('Y'), get_the_time('m')), get_the_time('F')) . $delimiter;
 echo $before . get_the_time('d') . $after;

 } elseif ( is_month() ) {
 if ($show_home_link == 1) echo $delimiter;
 echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
 echo $before . get_the_time('F') . $after;

 } elseif ( is_year() ) {
 if ($show_home_link == 1) echo $delimiter;
 echo $before . get_the_time('Y') . $after;

 } elseif ( is_single() && !is_attachment() ) {
 if ($show_home_link == 1) echo $delimiter;
 if ( get_post_type() != 'post' ) {
 $post_type = get_post_type_object(get_post_type());
 $slug = $post_type->rewrite;
 printf($link, $home_link . '/' . $slug['slug'] . '/', $post_type->labels->singular_name);
 if ($show_current == 1) echo $delimiter . $before . get_the_title() . $after;
 } else {
 $cat = get_the_category(); $cat = $cat[0];
 $cats = get_category_parents($cat, TRUE, $delimiter);
 if ($show_current == 0 || get_query_var('cpage')) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
 $cats = preg_replace('#<a([^>]+)>([^<]+)<\/a>#', $link_before . '<a$1' . $link_attr .'>' . $link_in_before . '$2' . $link_in_after .'</a>' . $link_after, $cats);
 if ($show_title == 0) $cats = preg_replace('/ title="(.*?)"/', '', $cats);
 echo $cats;
 if ( get_query_var('cpage') ) {
 echo $delimiter . sprintf($link, get_permalink(), get_the_title()) . $delimiter . $before . sprintf($text['cpage'], get_query_var('cpage')) . $after;
 } else {
 if ($show_current == 1) echo $before . get_the_title() . $after;
 }
 }

 // custom post type
 } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
 $post_type = get_post_type_object(get_post_type());
 if ( get_query_var('paged') ) {
 echo $delimiter . sprintf($link, get_post_type_archive_link($post_type->name), $post_type->label) . $delimiter . $before . sprintf($text['page'], get_query_var('paged')) . $after;
 } else {
 if ($show_current == 1) echo $delimiter . $before . $post_type->label . $after;
 }

 } elseif ( is_attachment() ) {
 if ($show_home_link == 1) echo $delimiter;
 $parent = get_post($parent_id);
 $cat = get_the_category($parent->ID); $cat = $cat[0];
 if ($cat) {
 $cats = get_category_parents($cat, TRUE, $delimiter);
 $cats = preg_replace('#<a([^>]+)>([^<]+)<\/a>#', $link_before . '<a$1' . $link_attr .'>' . $link_in_before . '$2' . $link_in_after .'</a>' . $link_after, $cats);
 if ($show_title == 0) $cats = preg_replace('/ title="(.*?)"/', '', $cats);
 echo $cats;
 }
 printf($link, get_permalink($parent), $parent->post_title);
 if ($show_current == 1) echo $delimiter . $before . get_the_title() . $after;

 } elseif ( is_page() && !$parent_id ) {
 if ($show_current == 1) echo $delimiter . $before . get_the_title() . $after;

 } elseif ( is_page() && $parent_id ) {
 if ($show_home_link == 1) echo $delimiter;
 if ($parent_id != $frontpage_id) {
 $breadcrumbs = array();
 while ($parent_id) {
 $page = get_page($parent_id);
 if ($parent_id != $frontpage_id) {
 $breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));
 }
 $parent_id = $page->post_parent;
 }
 $breadcrumbs = array_reverse($breadcrumbs);
 for ($i = 0; $i < count($breadcrumbs); $i++) {
 echo $breadcrumbs[$i];
 if ($i != count($breadcrumbs)-1) echo $delimiter;
 }
 }
 if ($show_current == 1) echo $delimiter . $before . get_the_title() . $after;

 } elseif ( is_tag() ) {
 if ($show_current == 1) echo $delimiter . $before . sprintf($text['tag'], single_tag_title('', false)) . $after;

 } elseif ( is_author() ) {
 if ($show_home_link == 1) echo $delimiter;
 global $author;
 $author = get_userdata($author);
 echo $before . sprintf($text['author'], $author->display_name) . $after;

 } elseif ( is_404() ) {
 if ($show_home_link == 1) echo $delimiter;
 echo $before . $text['404'] . $after;

 } elseif ( has_post_format() && !is_singular() ) {
 if ($show_home_link == 1) echo $delimiter;
 echo get_post_format_string( get_post_format() );
 }

 echo '</div><!-- .breadcrumbs -->';

 }
} // end dimox_breadcrumbs

// подключение файла настроек темы
 include_once('az-settings.php');
 
 
?>