<?php
/**
 * Carregando datapicker e scripts
 */

add_action( 'wp_enqueue_scripts', 'enqueue_datepickers' );

function enqueue_datepickers() {
    
    if ( is_product()) {
        // Load the datepicker script (pre-registered in WordPress).
         wp_enqueue_script( 'jquery-ui-datepicker' );
         // You need styling for the datepicker. For simplicity I've linked to Google's hosted jQuery UI CSS.
         wp_register_style( 'jquery-ui', '//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css' );
         wp_enqueue_style( 'jquery-ui' );  
    
        }  

}


/**
 * Adicionar o campo personalizado a página de produtos
 */

add_action( 'woocommerce_before_variations_form', 'mostrar_campo_extra_apos_form_variacao' , 10, 1 );
function mostrar_campo_extra_apos_form_variacao () {
    
    echo '<div class="visit">';

	_e( "<div><label>Data de Visita:</label></div>", "add_extra_fields");
	
     ?>
	
    <input type="text" name="add_delivery_date" class="add_delivery_date input" placeholder="Agende uma data">
	
    <script>
	
        jQuery(document).ready(function( $ ) {
            
	        $( ".add_delivery_date").datepicker( {
	        	minDate: 2,
                autoHide: true,
                beforeShowDay: $.datepicker.noWeekends
	        } );
	    });
	
    </script>
	
    <?php 
    
    echo '</div>'; 
}


/**
 * Salvando valor do campo na tabela wp_postmeta
 */

add_action( 'woocommerce_checkout_update_order_meta', 'add_valor_data_date_na_tabela' , 10, 1);

function add_valor_data_na_tabela ( $order_id ) {
	
    if ( isset( $_POST ['add_delivery_date'] ) &&  '' != $_POST ['add_delivery_date'] ) {
		
        add_post_meta( $order_id, '_delivery_date',  sanitize_text_field( $_POST ['add_delivery_date'] ) );
	
    }
}

/**
 * Adicionando valor do campo ao email de notificação
 */

add_filter( 'woocommerce_email_order_meta_fields', 'add_valor_data_no_email' , 10, 3 );

function add_valor_data_no_email ( $fields, $sent_to_admin, $order ) {
    
    if( version_compare( get_option( 'woocommerce_version' ), '3.0.0', ">=" ) ) {            
    
        $order_id = $order->get_id();
    
    } else {
    
        $order_id = $order->id;
    
    }
    
    $delivery_date = get_post_meta( $order_id, '_delivery_date', true );
    
    if ( '' != $delivery_date ) {
	
        $fields[ 'Delivery Date' ] = array(
	    
        'label' => __( 'Agende seu retorno', 'add_extra_fields' ),
	    'value' => $delivery_date,
	
         );
    }
    
    return $fields;
}


/**
 * Mostrar o valor na página de agradecimento
 */

add_filter( 'woocommerce_order_details_after_order_table', 'add_data_pagina_de_agradecimento', 10 , 1 );

function add_data_pagina_de_agradecimento ( $order ) {
	
    if( version_compare( get_option( 'woocommerce_version' ), '3.0.0', ">=" ) ) {            
    
        $order_id = $order->get_id();
    
    } else {
    
        $order_id = $order->id;
    
    }
    
    $delivery_date = get_post_meta( $order_id, '_delivery_date', true );
    
    if ( '' != $delivery_date ) {
    	echo '<p><strong>' . __( 'Retorno', 'add_extra_fields' ) . ':</strong> ' . $delivery_date;
	}
}


/**
 * Mostrar a data na página do pedido no woocommerce
 */

 add_action( 'woocommerce_admin_order_data_after_billing_address', 'adicionar_campo_pagina_admin_pedido', 10, 1 );


 function adicionar_campo_pagina_admin_pedido($order){

    echo '<p><strong>'.__('Data de preferência').':</strong> <br/>' . get_post_meta( $order->get_id(), '_delivery_date', true ) . '</p>';

}