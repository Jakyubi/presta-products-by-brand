<?php
if(!defined('_PS_VERSION_')){
    exit;
}

class ProductsByBrand extends Module
{

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

    }

    public function install()
    {
        return(
            parent::install()
            && $this->registerHook('displayHome')
            && $this->registerHook('actionFrontControllerSetMedia')
            && Configuration::updateValue('PRODUCTSBYBRAND_MODULE_NAME', 'Products by brand')
        );
    }

    public function uninstall()
    {
        return(
            parent::uninstall()
            && Configuration::deleteByName('PRODUCTSBYBRAND_MODULE_NAME')
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
        ]);
        return $this->display(__FILE__, 'views/templates/hook/templateFront.tpl');

    }
}


