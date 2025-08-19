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
        ]);
        return $this->display(__FILE__, 'views/templates/hook/templateFront.tpl');

    }


    public function getContent()
    {
        if(Tools::isSubmit('submitProductsbybrandSettingsForm')){
            $enabled = Tools::getValue('PRODUCTSBYBRAND_MODULE_ENABLE');

            Configuration::updateValue('PRODUCTSBYBRAND_MODULE_ENABLE', $enabled);
        }

        $this->context->smarty->assign([
            'displayForm' => $this->displayForm(),
        ]);

        return $this->display(__FILE__, 'views/templates/admin/configure.tpl');
    }

    public function displayForm()
    {
        $form = [
            'form' => [
                'legend' => [
                    'title' => $this->l('Settings'),
                ],
                'input' => [
                    [
                        'type' => 'switch',
                        'label' => $this->l('Enable module'),
                        'name' => 'PRODUCTSBYBRAND_MODULE_ENABLE',
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ],
                            [
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            ],
                        ],
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right',
                    'name' => 'submitProductsbybrandSettingsForm',
                ],
            ],
        ];


        $helper = new HelperForm();
        
        $helper->table = $this->table;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&' . http_build_query(['configure' => $this->name]);
        $helper->submit_action = 'submitProductsbybrandSettingsForm';
        $helper->default_form_language = (int) Configuration::get('PS_LANG_DEFAULT');

        $helper->fields_value['PRODUCTSBYBRAND_MODULE_ENABLE'] = 
        Tools::getValue('PRODUCTSBYBRAND_MODULE_ENABLE', Configuration::get('PRODUCTSBYBRAND_MODULE_ENABLE'));

        return $helper->generateForm([$form]);

    }


}


