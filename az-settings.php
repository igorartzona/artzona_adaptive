<?php
add_action( 'admin_init', 'az_options_init' );
add_action('admin_menu', 'az_theme_settings');

function az_options_init(){
	register_setting( 'wpuniq_options', 'wpuniq_theme_options');
}

function az_theme_settings() {
	add_theme_page(__( 'Дополнительные настройки темы', 'Artzona theme settings' ), __( 'Дополнительные настройки темы', 'Artzona theme settings' ), 'edit_theme_options', 'az_theme_id', 'az_theme_function');
}

function az_theme_function(){
	global $select_options; 
	if ( ! isset( $_REQUEST['settings-updated'] ) ) $_REQUEST['settings-updated'] = false;
	?>
	<div class="wrap">
	
		<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
		<div id="message" class="updated">
			<p><strong><?php _e( 'Настройки сохранены', 'WP-Unique' ); ?></strong></p>
		</div>
		<?php endif; ?>	
		
		<h2>Дополнительные настройки темы</h2>
		<form method="post" action="options.php">
		
		<?php settings_fields( 'wpuniq_options' ); ?>
		<?php $options = get_option( 'wpuniq_theme_options' ); ?>
				
			<table class="form-table">
			<tr>
				<th scope="row">
					<label>Цветовая схема :</label>
				</th>
			
				<td>
					<select name="wpuniq_theme_options[color]">
						<option value="default"<?php if($options[color]=='default') echo ' selected="selected"';?>>По умолчанию</option>
						<option value="orange"<?php if($options[color]=='orange') echo ' selected="selected"';?>>Оранжевая</option>
						<option value="blue"<?php if($options[color]=='blue') echo ' selected="selected"';?>>Синяя</option>
						<option value="green"<?php if($options[color]=='green') echo ' selected="selected"';?>>Зеленая</option>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan=2>
					<input type="submit" class="button button-primary" value="Применить" />
				</td>
			</tr>
			</table>		
		</form>		
	</div>
<?php } ?>