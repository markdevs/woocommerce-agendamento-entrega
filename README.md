# woocommerce-agendamento-entrega



<ul>
<li>Para esse script funcionar é preciso instalar o WordPress e Woocommerce</li>
<li>Cadastrar um produto demo</li>
<li>Esse código adiciona um campo de data a páina do produto.</li>
<li>A Data seleciona será inserida no resumo do pedido parado usuário, nos detalhes do pedido no woocommerce e no email de confirmação de pedido</li>
<ul>





## Hooks usados no functions.php


As funções de callback estão no arquivo functions.php desse repositório.


1 - enqueue scripts necessários

```
add_action( 'wp_enqueue_scripts', 'enqueue_datepicker' );

```

2 - Adicionar o campo personalizado de data a página de produtos

```
add_action( 'woocommerce_before_variations_form', 'mostrar_campo_extra_apos_form_variacao' , 10, 1 );

```

3 - Salvando valor do campo de data na tabela wp_postmeta


```
add_action( 'woocommerce_checkout_update_order_meta', 'add_valor_data_date_na_tabela' , 10, 1);

```

4 - Adicionando data ao campo ao email de notificação de pedido

```
add_filter( 'woocommerce_email_order_meta_fields', 'add_valor_data_no_email' , 10, 3 );

```

5 - Mostrar o valor na página de agradecimento

```
add_filter( 'woocommerce_order_details_after_order_table', 'add_data_pagina_de_agradecimento', 10 , 1 );

```

6 - Mostrar a data na página do pedido no woocommerce

```
add_action( 'woocommerce_admin_order_data_after_billing_address', 'adicionar_campo_pagina_admin_pedido', 10, 1 );

