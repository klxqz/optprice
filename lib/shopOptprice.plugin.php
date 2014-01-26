<?php

/**
 * @author wa-plugins.ru <support@wa-plugins.ru>
 * @link http://wa-plugins.ru/
 */
class shopOptpricePlugin extends shopPlugin {

    public function orderCalculateDiscount($params) {
        $total_discount = 0;
        $def_currency = wa('shop')->getConfig()->getCurrency(true);
        foreach ($params['order']['items'] as $item) {
            $opt_price = shopOptpricePlugin::getOptPrice($item['product_id'], $item['sku_id']);
            if ($opt_price) {
                $orig_price = shop_currency($item['price'], $item['currency'], $def_currency, false);
                $product_discount = $orig_price - $opt_price;
                $total_discount += $product_discount * $item['quantity'];
            }
        }
        return shop_currency($total_discount, null, null, false);
    }

    public function frontendProduct($product) {
        if ($this->getSettings('frontend_product')) {
            return array('cart' => self::display($product->id));
        }
    }

    public static function getOptPrice($product_id, $sku_id = null, $contact_id = null) {
        $plugin_id = array('shop', 'optprice');
        $app_settings_model = new waAppSettingsModel();
        $selected_categories_j = $app_settings_model->get($plugin_id, 'selected_categories');
        $selected_categories = json_decode($selected_categories_j, true);
        if (!$selected_categories) {
            return false;
        }

        if (!$contact_id) {
            $contact_id = wa()->getUser()->getId();
        }
        $ids = array_map('intval', array_keys($selected_categories));
        $model = new waModel();
        $sql = "SELECT * FROM `wa_contact_categories` WHERE `contact_id` = '" . $contact_id . "' AND `category_id` IN (" . implode(',', $ids) . ")";
        $exist = $model->query($sql)->fetch();
        if (!$exist) {
            return false;
        }

        $product = new shopProduct($product_id);
        if (!$product) {
            return false;
        }

        if (!$sku_id) {
            $sku_id = $product->sku_id;
        }

        if (!isset($product->skus[$sku_id])) {
            return false;
        }

        $def_currency = wa('shop')->getConfig()->getCurrency(true);
        $sku = $product->skus[$sku_id];
        $opt_price = shop_currency($sku['purchase_price'], $product->currency, $def_currency, false);
        return $opt_price;
    }

    public static function display($product_id = null) {
        $tmp_path = 'plugins/optprice/templates/FrontendProduct.html';
        $plugin_id = array('shop', 'optprice');
        $app_settings_model = new waAppSettingsModel();

        if ($app_settings_model->get($plugin_id, 'status')) {
            $opt_price = shopOptpricePlugin::getOptPrice($product_id);
            if ($opt_price !== false) {
                if (!$opt_price) {
                    $product = new shopProduct($product_id);
                    $opt_price = $product['price'];
                }
                $view = wa()->getView();
                $view->assign('opt_price', $opt_price);
                $view->assign('settings', $app_settings_model->get($plugin_id));
                $template_path = wa()->getDataPath($tmp_path, false, 'shop', true);
                if (!file_exists($template_path)) {
                    $template_path = wa()->getAppPath($tmp_path, 'shop');
                }
                $html = $view->fetch($template_path);
                return $html;
            }
        }
    }

}
