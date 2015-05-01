<?php 

if(!defined('ABSPATH')){
    exit;
}

/* Inlcude Styleshets */
function include_stylesheets(){
    wp_enqueue_style('fswp-style', WOO_PARCELAS_URL.'assets/css/fswp-style.css', '', false);
}
add_action('wp_enqueue_scripts', 'include_stylesheets');

/**
 * Calculates the installments price.
 *
 * @return string with the installments price based in installments quantity.
 */
function fswp_calc() {
    $product = get_product();

    global $fs_options;

    $fswp_parcelas = $fs_options['fswp_parcelas'];
    $fswp_valor_minimo = $fs_options['fswp_valor_minimo'];
    $fswp_prefixo = $fs_options['fswp_prefixo'];

    if($product->has_child()){
        if($product->get_variation_price('min') != $product->get_variation_price('max')){
            $fswp_prefixo = __('A partir de', 'woocommerce-parcelas');
        }
    }

    $fswp_sufixo = $fs_options['fswp_sufixo'];    

    if($product->get_price_including_tax()){
        $preco = $product->get_price_including_tax();

        if($preco <= $fswp_valor_minimo){
            $output = '';
        }
        elseif($preco > $fswp_valor_minimo){
            $preco_parcelado = $preco / $fswp_parcelas;
            $preco_parcelado_formatado = woocommerce_price($preco / $fswp_parcelas);

            if($preco_parcelado < $fswp_valor_minimo){
                while($fswp_parcelas > 1 && $preco_parcelado < $fswp_valor_minimo){
                    $fswp_parcelas--;
                    $preco_parcelado = $preco / $fswp_parcelas;
                    $preco_parcelado_formatado = woocommerce_price($preco / $fswp_parcelas);
                }

                if($preco_parcelado > $fswp_valor_minimo){
                    $output = "<p class='price fswp_calc'>".sprintf(__('%s %sx de ', 'woocommerce-parcelas'), $fswp_prefixo, $fswp_parcelas).$preco_parcelado_formatado." ".$fswp_sufixo."</p>";
                }
                else{
                    $output = '';
                }
            }
            else{
                $output = "<p class='price fswp_calc'>".sprintf(__('%s %sx de ', 'woocommerce-parcelas'), $fswp_prefixo, $fswp_parcelas).$preco_parcelado_formatado." ".$fswp_sufixo."</p>";
            }      
        }            

        return $output;
    }
}

/**
 * Displays the installments price on loop
 * 
 * @return string with the installments price based in installments quantity.
 */
function fswp_in_loop(){
    echo fswp_calc();
}

/**
 * Displays the installments price on single product.
 *
 * @return string with the installments price based in installments quantity.
 */
function fswp_in_single(){
    echo fswp_calc();
}

/**
* @return space to attach correct installments price
*/
function output_installment_on_variation_change(){
    echo "<div class='fswp_variable_installment'></div>";
}

/**
* @return javascript to calculate and attach correct installments price
*/
function js_after_single_variation(){
    $product = get_product();

    global $fs_options;

    if($product->get_variation_price('min') != $product->get_variation_price('max')){
        $fswp_x_de = __('x de', 'woocommerce-parcelas');
        $sep_dec = get_option('woocommerce_price_decimal_sep');
        $cur_symbol = get_woocommerce_currency_symbol();            
?>
        <script type="text/javascript">
            // Below variables are being used on variable.js file

            var fswp_parcelas = <?php echo $fs_options['fswp_parcelas']; ?>;
            var fswp_valor_minimo = <?php echo $fs_options['fswp_valor_minimo']; ?>;
            var fswp_prefixo = <?php echo "'".$fs_options['fswp_prefixo']."'"; ?>;
            var fswp_sufixo = <?php echo "'".$fs_options['fswp_sufixo']."'"; ?>;
            var fswp_x_de = <?php echo "'".$fswp_x_de."'"; ?>;
            var sep_dec = <?php echo "'".$sep_dec."'"; ?>;
            var cur_symbol = <?php echo "'".$cur_symbol."'"; ?>

            // Ponto por vírgula
            function pv(fswp_var){
                fswp_var = fswp_var.replace('.', ',');

                return fswp_var;
            }           
        </script>
<?php
        wp_enqueue_script('fswp-variable', WOO_PARCELAS_URL.'assets/js/variable.js', '', false, true);
    }
}

add_action('woocommerce_after_shop_loop_item_title', 'fswp_in_loop', 20);
add_action('woocommerce_single_product_summary', 'fswp_in_single', 10);
add_action('woocommerce_before_single_variation', 'output_installment_on_variation_change');
add_action('woocommerce_after_single_variation', 'js_after_single_variation');

?>