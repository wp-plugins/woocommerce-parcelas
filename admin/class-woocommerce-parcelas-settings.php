<?php 
/**
 * Build plugin page settings
 *
 * @since 	1.0.0
 * @author   Filipe Seabra <eu@filipecsweb.com.br>
 * @version  1.2.5
 */
class Woocommerce_Parcelas_Settigns{
	/**
	 * Initialize option name, option group and plugin page name
	 */
	public $option_group = 'fswp_settings';
	public $option_name = 'fswp_settings';
	public $page = 'fswp';

	public function __construct(){
		/**
		 * Add settings page menu
		 */
		add_action('admin_menu', array($this, 'fswp_admin_menu'), 100);

		/**
		 * Add the function that builds settings page content
		 */
		add_action('admin_init', array($this, 'fswp_page_settings'));
	}

	/**
	 * Load settings page menu
	 */
	public function fswp_admin_menu(){
		add_submenu_page('woocommerce', __('Quantidade e pre&ccedil;o de parcelas', 'woocommerce-parcelas'), __('Parcelas', 'woocommerce-parcelas'), 'manage_options', $this->page, array($this, 'fswp_page_callback'));
		// add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function);
	}

	/**
	 * Output the content for settings page
	 */
	public function fswp_page_callback(){
	?>
		<div class="section">
			<form action="options.php" method="post">
				<?php settings_fields($this->option_group); ?>
				<?php // settings_fields( $option_group ); ?>

				<?php do_settings_sections($this->page); ?>
				<?php // do_settings_sections( $page ); ?>

				<?php submit_button(); ?>
			</form>		
		</div>
		<div class="section">
			<div class="fswp_rodape">
				<p>Achou a ferramenta útil? Faça uma doação... assim você ajuda com a manutenção e criação de todos os projetos gratuitos.</p>
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
					<input type="hidden" name="cmd" value="_s-xclick">
					<input type="hidden" name="hosted_button_id" value="QM6NM5RMLQ9L4">
					<input type="image" src="https://www.paypalobjects.com/pt_BR/BR/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="Doe para FilipeCS Web">
					<img alt="" border="0" src="https://www.paypalobjects.com/pt_BR/i/scr/pixel.gif" width="1" height="1">
				</form>
				<hr />
				<?php echo '<a class="button-secondary" href="//filipecsweb.com.br/?p=43" target="_blank">' . __('Bugs e Sugestões', 'woocommerce-parcelas') . '</a>'; ?>
			</div>
		</div>
	<?php
	}

	/**
	 * Create the content for settings page
	 */
	public function fswp_page_settings(){
		register_setting(
			$this->option_group, 
			$this->option_name, 
			array($this, 'fswp_options_sanitize')
		);
		// register_setting( $option_group, $option_name, $sanitize_callback );

		add_settings_section(
			'fswp_general_section', 
			__('Geral', 'woocommerce-parcelas'), 
			array($this, 'fswp_general_section_callback'), 
			$this->page
		);
		// add_settings_section( $id, $title, $callback, $page );

		add_settings_field(
			'fswp_ativar', 
			__('Habilitar', 'woocommerce-parcelas'), 
			array($this, 'fswp_checkbox_callback'), 
			$this->page, 
			'fswp_general_section', 
			array(
				'id'	=>	'fswp_ativar',
				'label_for'	=>	'fswp_ativar',
				'desc'	=>	__('Marque para ativar a funcionalidade de Parcelas', 'woocommerce-parcelas')
			)
		);	

		add_settings_field(
			'fswp_prefixo',
			 __('Prefixo', 'woocommerce-parcelas'), 
			 array($this, 'fswp_text_callback'), 
			 $this->page, 
			 'fswp_general_section', 
			 array(
			 	'id'			=>	'fswp_prefixo',
				'label_for'		=>	'fswp_prefixo',		
				'desc'			=>	__('Escreva o texto que deve vir logo antes da quantidade. Ex.: Em at&eacute; ou Parcele em', 'woocommerce-parcelas'),
				'class'			=>	'regular-text fs-opcao_ativa'
			 )
		);
		// add_settings_field( $id, $title, $callback, $page, $section, $args );

		add_settings_field(
			'fswp_parcelas',
			__('Quantidade de parcelas','woocommerce-parcelas'), 
			array($this, 'fswp_number_callback'), 
			$this->page, 
			'fswp_general_section', 
			array(
				'id'	=>	'fswp_parcelas',
				'label_for'	=>	'fswp_parcelas',
				'desc'	=>	__('Insira a quantidade de parcelas. Valor m&iacute;nimo: 2', 'woocommerce-parcelas'),
				'class'	=>	'fs-opcao_ativa'
			)
		);
		// add_settings_field( $id, $title, $callback, $page, $section, $args );

		add_settings_field(
			'fswp_sufixo', 
			__('Sufixo', 'woocommerce-parcelas'), 
			array($this, 'fswp_text_callback'), 
			$this->page, 
			'fswp_general_section', 
			array(
				'id'	=>	'fswp_sufixo',
				'label_for'	=>	'fswp_sufixo',
				'desc'	=>	__('Escreva o texto que deve vir logo depois do pre&ccedil;o. Ex.: sem juros ou s/ juros', 'woocommerce-parcelas'),
				'class'	=>	'regular-text fs-opcao_ativa'
			)
		);
		// add_settings_field( $id, $title, $callback, $page, $section, $args );

		add_settings_field(
			'fswp_valor_minimo', 
			__('Valor m&iacute;nimo de cada parcela', 'woocommerce-parcelas'), 
			array($this, 'fswp_text_callback'), 
			$this->page, 
			'fswp_general_section', 
			array(
				'id'	=>	'fswp_valor_minimo',
				'label_for'	=>	'fswp_valor_minimo',
				'desc'	=>	__('Caso o preço de cada parcela tenha um valor mínimo insira-o aqui. Ex.: 5 ou 5,95.<br />Use apenas o separador decimal, não use separador de milhar.', 'woocommerce-parcelas'),
				'class'	=>	'fs-opcao_ativa'
			)
		);
		// add_settings_field( $id, $title, $callback, $page, $section, $args );
	}

	public function fswp_general_section_callback(){}
	
	public function fswp_checkbox_callback($args){
		extract($args);
	
		$fswp_settings = get_option($this->option_name);

		$value = isset($fswp_settings[$id]) ? $fswp_settings[$id] : 0;

		echo "<input type='checkbox' id='".$id."-0' name='".$this->option_name."[$id]' value='1'".checked('1', $value, false)." />";
		echo $desc != '' ? "<span class='description'>$desc</span>" : '';
	}
	public function fswp_text_callback($args){
		extract($args);

		$fswp_settings = get_option($this->option_name);

		$value = (isset($fswp_settings[$id])) ? $fswp_settings[$id] : '';

		echo "<input class='$class' type='text' id='$id' name='".$this->option_name."[$id]' value='$value' />";
		echo $desc != '' ? "<br /><span class='description'>$desc</span>" : '';
	}
	public function fswp_number_callback($args){
		extract($args);

		$fswp_settings = get_option($this->option_name);

		$value = (isset($fswp_settings[$id])) ? $fswp_settings[$id] : '';
		
		echo "<input type='number' id='$id' name='".$this->option_name."[$id]' value='$value' />";
		echo $desc != '' ? "<br /><span class='description'>$desc</span>" : '';
	}

	public function fswp_options_sanitize($input){
		foreach($input as $k => $v){
			if($k == 'fswp_parcelas'){
				if($v < 2){
					$v = 2;
				}
			}

			$newinput[$k] = trim($v);			
		}

		return $newinput;
	}
}