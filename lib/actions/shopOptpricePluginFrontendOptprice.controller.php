<?php

/**
 * @author wa-plugins.ru <support@wa-plugins.ru>
 * @link http://wa-plugins.ru/
 */
class shopOptpricePluginFrontendOptpriceController extends waJsonController {

    public function execute() {
        $product_id = waRequest::post('product_id');
        $sku_id = waRequest::post('sku_id');
        $opt_price = (float)shopOptpricePlugin::getOptPrice($product_id, $sku_id);
        if($opt_price) {
            $opt_price = shop_currency_html($opt_price);
        }
        $this->response['opt_price'] = $opt_price;
    }

}
