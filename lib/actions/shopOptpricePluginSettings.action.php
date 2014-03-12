<?php

/**
 * @author wa-plugins.ru <support@wa-plugins.ru>
 * @link http://wa-plugins.ru/
 */
class shopOptpricePluginSettingsAction extends waViewAction {

    protected $tmp_path = 'plugins/optprice/templates/FrontendProduct.html';
    protected $plugin_id = array('shop', 'optprice');

    public function execute() {
        // Categories
        $ccm = new waContactCategoryModel();
        $categories = array();
        foreach ($ccm->getAll() as $c) {
            if ($c['app_id'] == 'shop') {
                $categories[$c['id']] = $c;
            }
        }
        $app_settings_model = new waAppSettingsModel();
        $settings = $app_settings_model->get($this->plugin_id);

        $change_tpl = false;
        $template_path = wa()->getDataPath($this->tmp_path, false, 'shop', true);
        if (file_exists($template_path)) {
            $change_tpl = true;
        } else {
            $template_path = wa()->getAppPath($this->tmp_path, 'shop');
        }
        $selected_categories = isset($settings['selected_categories']) ? json_decode($settings['selected_categories'], true) : array();
        $template = file_get_contents($template_path);
        $this->view->assign('template', $template);
        $this->view->assign('change_tpl', $change_tpl);
        $this->view->assign('categories', $categories);
        $this->view->assign('settings', $settings);
        $this->view->assign('selected_categories', $selected_categories);
    }

}
