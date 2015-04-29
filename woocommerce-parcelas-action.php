<?php 

if(!defined('ABSPATH')){
    exit;
}

/* Inlcude Styleshets */
wp_enqueue_style('fswp-style', WOO_PARCELAS_URL.'assets/css/fswp-style.css', '', false);

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
    $fswp_sufixo = $fs_options['fswp_sufixo'];

    if($product->get_price_including_tax()){
        $price = $product->get_price_including_tax();

        if($price <= $fswp_valor_minimo){
            $output = '';
        }
        elseif($price > $fswp_valor_minimo){
            $preco_parcelado = $price / $fswp_parcelas;
            $preco_parcelado_formatado = woocommerce_price($price / $fswp_parcelas);

            if($preco_parcelado < $fswp_valor_minimo){
                while($fswp_parcelas > 1 && $preco_parcelado < $fswp_valor_minimo){
                    $fswp_parcelas--;
                    $preco_parcelado = $price / $fswp_parcelas;
                    $preco_parcelado_formatado = woocommerce_price($price / $fswp_parcelas);
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
    global $fs_options;

    echo fswp_calc();
}

/**
 * Displays the installments price on single product.
 *
 * @return string with the installments price based in installments quantity.
 */
function fswp_in_single(){
    global $fs_options;

    echo fswp_calc();
}

add_action('woocommerce_after_shop_loop_item_title', 'fswp_in_loop', 20);
add_action('woocommerce_single_product_summary', 'fswp_in_single', 10);

?>