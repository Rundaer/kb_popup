<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0).
 * It is also available through the world-wide-web at this URL: https://opensource.org/licenses/AFL-3.0
 */

declare(strict_types=1);

if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\Module\Kb_Popup\Install\InstallerFactory;
use PrestaShop\Module\Kb_Popup\Manager\TabManager;

require_once __DIR__.'/vendor/autoload.php';

class Kb_Popup extends Module
{
    public function __construct()
    {
        $this->name = 'kb_popup';
        $this->author = 'Konrad Babiarz';
        $this->tab = 'others';
        $this->version = '1.0.0';
        $this->ps_versions_compliancy = [
            'min' => '1.7.5.0',
            'max' => _PS_VERSION_
        ];

        parent::__construct();
        $this->displayName = $this->l('Popup module');
        $this->description = $this->l('Display popup banners in store');
    }

    public function install()
    {
        $installer = InstallerFactory::create();

        return parent::install() &&
            $installer->install($this) &&
            TabManager::installTab($this, 'Kb_Popup');
    }

    public function uninstall()
    {
        $installer = InstallerFactory::create();

        return $installer->uninstall() &&
            parent::uninstall() &&
            TabManager::uninstallTab();
    }

    public function hookActionFrontControllerSetMedia()
    {

        if ('product' === $this->context->controller->php_self) {
            $this->context->controller->registerStylesheet(
                'kb-popup-css',
                'modules/'.$this->name.'/views/css/front.css',
                [
                    'media' => 'all',
                    'priority' => 200,
                ]
            );

            $this->context->controller->registerJavascript(
                'kb-popup-js',
                'modules/'.$this->name.'/views/js/front.js',
                [
                    'priority' => 200,
                    'attribute' => 'async',
                ]
            );
        }
    }

    public function hookDisplayFooterProduct($params)
    {
        if('product' === $this->context->controller->php_self){
            $id_lang = $this->context->language->id;
            $id_shop = $this->context->shop->id;
    
            $popup = Db::getInstance()->executeS('
                SELECT `id`,`link`,`text`,`image`,`is_image`, `id_product`, `starts_at`, `ends_at`, `background_color` FROM `kb_popup`
                WHERE `id_lang` = '.$id_lang.' 
                AND `page_select` = \'product\' 
                AND `id_product` = '.$params['product']->id.'
                AND `id_shop` = '.$id_shop.'
            ');

            // modify link to image
            if (!empty($popup[0]['image']) && isset($popup[0]['image']) ){
                $popup[0]['image_show'] = $this->updateUrl(_PS_BASE_URL_ . _MODULE_DIR_ . $this->name . '/views/img/' . $popup['image']);
            }

            // show only in placed time
            $today = new DateTime();
            $startDate = new DateTime($popup[0]['starts_at']);
            $endDate = new DateTime($popup[0]['ends_at']);
            if ($startDate <= $today && $endDate >= $today){

                // if not empty
                if(isset($popup) && !empty($popup)){

                    $this->smarty->assign(array(
                        'popup' => $popup[0]
                    ));

                    return $this->fetch('module:kb_popup/views/templates/hook/popup.tpl', $this->getCacheId($this->name));
                }
            } 
        }
    }

    private function updateUrl($link)
    {
        if (substr($link, 0, 7) !== "http://" && substr($link, 0, 8) !== "https://") {
            $link = "http://" . $link;
        }

        return $link;
    }
}
