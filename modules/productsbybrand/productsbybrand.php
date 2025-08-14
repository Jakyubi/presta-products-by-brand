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

    public function hookDisplayHome($params)
    {
        $brands = Manufacturer::getManufacturers(false, $this->context->language->id);
        $this->context->smarty->assign([
            'brands' => $brands,
        ]);
        return $this->display(__FILE__, 'views/templates/hook/templateFront.tpl');

    }
}


