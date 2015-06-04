<?php 
/**
* @author   Filipe Seabra
* @version  1.2.4
*/

add_action('admin_menu', 'fswp_menu');
function fswp_menu(){
	add_options_page(__('Quantidade e pre&ccedil;o de parcelas', 'woocommerce-parcelas'), __('Parcelas', 'woocommerce-parcelas'), 'manage_options', 'fswp', 'fswp_page_callback');
	// add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function);
}

function fswp_page_callback(){
?>
	<div class="section">
		<form action="options.php" method="post">
			<?php settings_fields('fswp_options'); ?>
			<?php // settings_fields( $option_group ); ?>

			<?php do_settings_sections('fswp'); ?>
			<?php // do_settings_sections( $page ); ?>

			<p class="submit">
				<input class="button-primary" type="submit" value="<?php echo _e('Save Changes'); ?>" />
			</p>
		</form>		
	</div>
	<div class="section">
		<script>
			<!--
			document.write(unescape("%3Cp%3EAchou%20algo%20%FAtil%3F%20Fa%E7a%20uma%20doa%E7%E3o...%20assim%20voc%EA%20ajuda%20na%20manuten%E7%E3o%20e%20cria%E7%E3o%20de%20todos%20os%20projetos%20e%20posts.%3C/p%3E%0A%3Cform%20action%3D%22https%3A//www.paypal.com/cgi-bin/webscr%22%20method%3D%22post%22%20target%3D%22_blank%22%3E%0A%3Cinput%20type%3D%22hidden%22%20name%3D%22cmd%22%20value%3D%22_s-xclick%22%3E%0A%3Cinput%20type%3D%22hidden%22%20name%3D%22hosted_button_id%22%20value%3D%22N8C7XKV57VWHQ%22%3E%0A%3Cinput%20style%3D%22border%3Anone%3B%20background%3Atransparent%3B%22%20type%3D%22image%22%20src%3D%22https%3A//www.paypalobjects.com/pt_BR/BR/i/btn/btn_donateCC_LG.gif%22%20border%3D%220%22%20name%3D%22submit%22%20alt%3D%22PayPal%20-%20A%20maneira%20f%E1cil%20e%20segura%20de%20enviar%20pagamentos%20online%21%22%3E%0A%3Cimg%20alt%3D%22%22%20border%3D%220%22%20src%3D%22https%3A//www.paypalobjects.com/pt_BR/i/scr/pixel.gif%22%20width%3D%221%22%20height%3D%221%22%3E%0A%3C/form%3E"));
			//-->
		</script>
	</div>
	<div class="section">
		<p>Encontrou um <strong>bug</strong> ou tem uma <strong>sugestão?</strong> Informe <a href="http://filipecsweb.com.br/contato/" target="_blank">aqui</a>.</p>
	</div>
<?php
}

add_action('admin_init', 'fswp_page_settings');
function fswp_page_settings(){
	register_setting('fswp_options', 'fswp_settings', 'fswp_options_sanitize');
	// register_setting( $option_group, $option_name, $sanitize_callback );

	add_settings_section('fswp_general_section', __('Geral', 'woocommerce-parcelas'), 'fswp_general_section_callback', 'fswp');
	// add_settings_section( $id, $title, $callback, $page );

	$fswp_ativar_args = array(
		'id'	=>	'fswp_ativar',
		'type'	=>	'checkbox',
		'label_for'	=>	'fswp_ativar',
		'desc'	=>	__('Mantenha marcado para ativar a funcionalidade do plugin', 'woocommerce-parcelas')
	);
	add_settings_field('fswp_ativar', __('Habilitar', 'woocommerce-parcelas'), 'fswp_ativar_callback', 'fswp', 'fswp_general_section', $fswp_ativar_args);
	// add_settings_field( $id, $title, $callback, $page, $section, $args );		

	$fswp_prefixo_args = array(
		'id'	=>	'fswp_prefixo',
		'type'	=>	'text',
		'label_for'	=>	'fswp_prefixo',		
		'desc'	=>	__('Escreva o texto que deve vir logo antes da quantidade. Ex.: Em at&eacute; ou Parcele em', 'woocommerce-parcelas'),
		'class'	=>	'regular-text fs-opcao_ativa'
	);
	add_settings_field('fswp_prefixo', __('Prefixo', 'woocommerce-parcelas'), 'fswp_prefixo_callback', 'fswp', 'fswp_general_section', $fswp_prefixo_args);
	// add_settings_field( $id, $title, $callback, $page, $section, $args );

	$fswp_parcelas_args = array(
		'id'	=>	'fswp_parcelas',
		'type'	=>	'number',
		'label_for'	=>	'fswp_parcelas',
		'desc'	=>	__('Insira a quantidade de parcelas. Valor m&iacute;nimo: 2', 'woocommerce-parcelas'),
		'class'	=>	'fs-opcao_ativa'
	);
	add_settings_field('fswp_parcelas', __('Quantidade de parcelas','woocommerce-parcelas'), 'fswp_parcelas_callback', 'fswp', 'fswp_general_section', $fswp_parcelas_args);
	// add_settings_field( $id, $title, $callback, $page, $section, $args );

	$fswp_sufixo_args = array(
		'id'	=>	'fswp_sufixo',
		'type'	=>	'text',
		'label_for'	=>	'fswp_sufixo',
		'desc'	=>	__('Escreva o texto que deve vir logo depois do pre&ccedil;o. Ex.: sem juros ou s/ juros', 'woocommerce-parcelas'),
		'class'	=>	'regular-text fs-opcao_ativa'
	);
	add_settings_field('fswp_sufixo', __('Sufixo', 'woocommerce-parcelas'), 'fswp_sufixo_callback', 'fswp', 'fswp_general_section', $fswp_sufixo_args);
	// add_settings_field( $id, $title, $callback, $page, $section, $args );

	$fswp_valor_minimo_args = array(
		'id'	=>	'fswp_valor_minimo',
		'type'	=>	'text',
		'label_for'	=>	'fswp_valor_minimo',
		'desc'	=>	__('Caso o preço de cada parcela tenha um valor mínimo insira-o aqui. Ex.: 5 ou 5,95', 'woocommerce-parcelas'),
		'class'	=>	'fs-opcao_ativa'
	);
	add_settings_field('fswp_valor_minimo', __('Valor m&iacute;nimo de cada parcela', 'woocommerce-parcelas'), 'fswp_valor_minimo_callback', 'fswp', 'fswp_general_section', $fswp_valor_minimo_args);
	// add_settings_field( $id, $title, $callback, $page, $section, $args );
}

function fswp_general_section_callback(){}

$fs_option_name = 'fswp_settings';
$fs_options = get_option($fs_option_name);

function fswp_ativar_callback($args){
	extract($args);
	
	global $fs_option_name;
	global $fs_options;

	$value = isset($fs_options[$id]) ? $fs_options[$id] : 0;

	echo "<input type='$type' id='".$id."-0' name='".$fs_option_name."[$id]' value='1'".checked('1', $value, false)." />";
	echo $desc != '' ? "<span class='description'>$desc</span>" : '';
}
function fswp_prefixo_callback($args){
	extract($args);
	
	global $fs_option_name;
	global $fs_options;

	$value = (isset($fs_options[$id])) ? $fs_options[$id] : '';

	echo "<input class='$class' type='$type' id='$id' name='".$fs_option_name."[$id]' value='$value' />";
	echo $desc != '' ? "<br /><span class='description'>$desc</span>" : '';
}
function fswp_parcelas_callback($args){
	extract($args);

	global $fs_option_name;
	global $fs_options;

	$value = (isset($fs_options[$id])) ? $fs_options[$id] : '';
	
	echo "<input type='$type' id='$id' name='".$fs_option_name."[$id]' value='$value' />";
	echo $desc != '' ? "<br /><span class='description'>$desc</span>" : '';
}
function fswp_sufixo_callback($args){
	extract($args);
	
	global $fs_option_name;
	global $fs_options;

	$value = (isset($fs_options[$id])) ? $fs_options[$id] : '';

	echo "<input class='$class' type='$type' id='$id' name='".$fs_option_name."[$id]' value='$value' />";
	echo $desc != '' ? "<br /><span class='description'>$desc</span>" : '';
}
function fswp_valor_minimo_callback($args){
	extract($args);

	global $fs_option_name;
	global $fs_options;

	$value = (isset($fs_options[$id])) ? $fs_options[$id] : '';	

	echo "<input type='$type' id='$id' name='".$fs_option_name."[$id]' value='$value' />";
	echo $desc != '' ? "<br /><span class='description'>$desc</span>" : '';
}

function fswp_options_sanitize($input){
	foreach($input as $k => $v){
		$newinput[$k] = trim($v);
	}
	return $newinput;
}

?>