<?php

/**
 * @author wa-plugins.ru <support@wa-plugins.ru>
 * @link http://wa-plugins.ru/
 */
return array(
    'name' => 'Скидка на товар',
    'description' => 'Скидка на товара для выбранных групп пользователей',
    'vendor' => '985310',
    'version' => '1.0.0',
    'img' => 'img/optprice.png',
    'shop_settings' => true,
    'frontend' => true,
    'handlers' => array(
        'frontend_product' => 'frontendProduct',
        'order_calculate_discount' => 'orderCalculateDiscount',
    ),
);
//EOF
