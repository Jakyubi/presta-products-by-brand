<?php
if(!defined('_PS_VERSION_')){
    exit;
}

require_once __DIR__.'/vendor/autoload.php';

use ProductsByBrandModule\FormHandler;

class ProductsByBrand extends Module
{
    public $formHandler;

    public function __construct()
    {
        $this->name = 'productsbybrand';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'abc';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.7.0.0',
            'max' => _PS_VERSION_
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->trans('Products by brand', [], 'Modules.Productsbybrand.Admin');
        $this->description = $this->trans('Show products by brand', [], 'Modules.Productsbybrand.Admin');
        $this->confirmUninstall = $this->trans('Are you sure to uninstall?', [], 'Modules.Productsbybrand.Admin');

        if(!Configuration::get('PRODUCTSBYBRAND_MODULE_NAME')){
            $this->warning = $this->trans('No name provided', [], 'Modules.Productsbybrand.Admin');
        }

        $this->formHandler = new FormHandler($this);

    }

    public function install()
    {
        return(
            parent::install()
            && $this->registerHook('displayHome')
            && $this->registerHook('actionFrontControllerSetMedia')
            && Configuration::updateValue('PRODUCTSBYBRAND_MODULE_NAME', 'Products by brand')
            && Configuration::updateValue('PRODUCTSBYBRAND_MODULE_ENABLE', 1)
            && Configuration::updateValue('PRODUCTSBYBRAND_GRID_ENABLE', 1)
            && Configuration::updateValue('PRODUCTSBYBRAND_LIST_ENABLE', 1)
            && Configuration::updateValue('PRODUCTSBYBRAND_DESKTOP_ROWS', 2)
            && Configuration::updateValue('PRODUCTSBYBRAND_MOBILE_ROWS', 4)
        );
    }

    public function uninstall()
    {
        return(
            parent::uninstall()
            && Configuration::deleteByName('PRODUCTSBYBRAND_MODULE_NAME')
            && Configuration::deleteByName('PRODUCTSBYBRAND_MODULE_ENABLE')
            && Configuration::deleteByName('PRODUCTSBYBRAND_GRID_ENABLE')
            && Configuration::deleteByName('PRODUCTSBYBRAND_LIST_ENABLE')
            && Configuration::deleteByName('PRODUCTSBYBRAND_DESKTOP_ROWS')
            && Configuration::deleteByName('PRODUCTSBYBRAND_MOBILE_ROWS')
        );
    }

    public function hookActionFrontControllerSetMedia($params)
    {
        $this->context->controller->registerJavascript(
            'script-show-more-brands', 
            'modules/'.$this->name.'/dist/js/app.bundle.js',
            ['media'=>'all', 'priority' => 150]
        );

        $this->context->controller->registerStylesheet(
            'style-show-more-brands', 
            'modules/'.$this->name.'/dist/css/style.css',
            ['media'=>'all', 'priority' => 150]
        );
    }

    public function hookDisplayHome($params)
    {
        $enabled = (bool) Configuration::get('PRODUCTSBYBRAND_MODULE_ENABLE');
        if(!$enabled){
            return '';
        }

        $brands = Manufacturer::getManufacturers(false, $this->context->language->id ,false);
        $grouped = [];

        foreach($brands as $brand){
            $firstLetter = strtoupper(mb_substr($brand['name'], 0, 1, 'UTF-8'));
            if(!isset($grouped[$firstLetter])){
                $grouped[$firstLetter] = [];
            }
            $grouped[$firstLetter][] = $brand;
        }

        $this->context->smarty->assign([
            'brands' => $brands,
            'grouped_brands' => $grouped,
            'desktopRows' => (int) Configuration::get('PRODUCTSBYBRAND_DESKTOP_ROWS', 2),
            'mobileRows' => (int) Configuration::get('PRODUCTSBYBRAND_MOBILE_ROWS', 4), 
            'gridEnabled' => (int) Configuration::get('PRODUCTSBYBRAND_GRID_ENABLE', 1), 
            'listEnabled' => (int) Configuration::get('PRODUCTSBYBRAND_LIST_ENABLE', 1), 
        ]);

        return $this->display(__FILE__, 'views/templates/hook/templateFront.tpl');
    }


    public function getContent()
    {
        if(Tools::isSubmit('submitProductsbybrandSettingsForm')){
            $moduleEnabled = Tools::getValue('PRODUCTSBYBRAND_MODULE_ENABLE');
            $gridEnabled = Tools::getValue('PRODUCTSBYBRAND_GRID_ENABLE');
            $listEnabled = Tools::getValue('PRODUCTSBYBRAND_LIST_ENABLE');
            $desktopRows = Tools::getValue('PRODUCTSBYBRAND_DESKTOP_ROWS');
            $mobileRows = Tools::getValue('PRODUCTSBYBRAND_MOBILE_ROWS');


            Configuration::updateValue('PRODUCTSBYBRAND_MODULE_ENABLE', $moduleEnabled);
            Configuration::updateValue('PRODUCTSBYBRAND_GRID_ENABLE', $gridEnabled);
            Configuration::updateValue('PRODUCTSBYBRAND_LIST_ENABLE', $listEnabled);
            Configuration::updateValue('PRODUCTSBYBRAND_DESKTOP_ROWS', $desktopRows);
            Configuration::updateValue('PRODUCTSBYBRAND_MOBILE_ROWS', $mobileRows);
        }

        $this->context->smarty->assign([
            'displayForm' => $this->formHandler->displayForm(),
        ]);

        return $this->display(__FILE__, 'views/templates/admin/configure.tpl');
    }

    public function getContext()
    {
        return $this->context;
    }

    public function getTable() 
    {
        return $this->table;
    }

    public function getName()
    {
        return $this->name;
    }
}
