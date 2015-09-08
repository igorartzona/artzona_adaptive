<?php
//Вешаем на хук функцию добавления пункта меню
add_action('admin_menu', 'az_theme_settings');

//Описываем функцию добавления пункта меню
function az_theme_settings() {
	add_theme_page(__( 'Дополнительные настройки темы', 'Artzona theme settings' ), __( 'Дополнительные настройки темы', 'Artzona theme settings' ), 'edit_theme_options', 'az_theme_id', 'az_theme_function');
}

//Формируем страницу настроек
function az_theme_function(){
	//установка переменной для проверки сохранения состояния
	global $select_options; 
	if ( ! isset( $_REQUEST['settings-updated'] ) ) $_REQUEST['settings-updated'] = false;	
	?>
	<div class="wrap">
	
	<!-- Вывод сообщения при сохранении настроек -->
		<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
		<div id="message" class="updated">
			<p><strong><?php _e( 'Настройки сохранены', 'WP-Unique' ); ?></strong></p>
		</div>
		<?php endif; ?>	
	<!-- Форма данных -->	
		<fieldset style="border:1px solid #e5e5e5;padding:10px;background:#f9f9f9;box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);">
		<legend style="font-size:1.2em;font-weight:400;"><img src="<?php echo get_template_directory_uri(); ?>/img/logo_stork.png" width="24" height="24">
		<span style="background:#BD2447;color:white;padding:0 10px;"><?php echo get_admin_page_title() ?></span></legend>

		<form action="options.php" method="POST" >
			<?php settings_fields( 'az_group' ); ?>
			<?php do_settings_sections( 'az_page' ); ?>
			<?php submit_button(); ?>
		</form>
		</fieldset>
	</div>
	<?
 } 
 
 function az_settings(){ 	
	register_setting( 'az_group', 'az_theme_options' );	
	add_settings_section( 'az_id', 'Оформление', '', 'az_page' );	
	add_settings_field('az_field1', 'Цветовая схема :', 'fill_az_field1', 'az_page', 'az_id' );
	
	add_settings_section( 'az_id2', 'Данные в шапке (header)', 'az_id2_description', 'az_page' );
	add_settings_field('az_field2', 'Адрес :', 'fill_az_field2', 'az_page', 'az_id2' );
	add_settings_field('az_field3', 'Телефон :', 'fill_az_field3', 'az_page', 'az_id2' );
}
add_action('admin_init', 'az_settings');


function az_id2_description(){
	echo "HTML-тэги разрешены.";
}

// заполняем опцию 1
function fill_az_field1(){
	$val = get_option('az_theme_options');
	$val = $val['color'];
	
	?>
	<select name="az_theme_options[color]">
		<option value="default" <?php selected( $val, 'default' ); ?>>По умолчанию</option>
		<option value="orange"  <?php selected( $val, 'orange' ); ?>>Оранжевая</option>
		<option value="blue"    <?php selected( $val, 'blue' ); ?>>Синяя</option>
		<option value="green"   <?php selected( $val, 'green' ); ?>>Зеленая</option>
	</select>
	<?
}
// заполняем опцию 2
function fill_az_field2(){
	$val = get_option('az_theme_options');
	$val = $val['address'];
	?>
	<textarea name="az_theme_options[address]"/> <?php echo $val; ?>  </textarea>
	<?
}
// заполняем опцию 3
function fill_az_field3(){
	$val = get_option('az_theme_options');
	$val = $val['tel'];
	?>
	<input type="tel" name="az_theme_options[tel]" value="<?php echo $val; ?>"  />
	<?
}
 
 
 ?>