<?php
namespace ProductsBybrandModule;
use Configuration;
use Tools;
use HelperForm;
use AdminController;

class FormHandler
{
    protected $module;

    public function __construct($module)
    {
        $this->module = $module;
    }
    
    public function displayForm()
    {
        $form = [
            'form' => [
                'legend' => [
                    'title' => $this->module->l('Settings'),
                ],
                'input' => [
                    [
                        'type' => 'switch',
                        'label' => $this->module->l('Enable module'),
                        'name' => 'PRODUCTSBYBRAND_MODULE_ENABLE',
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => 'module_active_on',
                                'value' => 1,
                                'label' => $this->module->l('Enabled')
                            ],
                            [
                                'id' => 'module_active_off',
                                'value' => 0,
                                'label' => $this->module->l('Disabled')
                            ],
                        ],
                    ],
                    [
                        'type' => 'switch',
                        'label' => $this->module->l('Enable grid'),
                        'name' => 'PRODUCTSBYBRAND_GRID_ENABLE',
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => 'grid_active_on',
                                'value' => 1,
                                'label' => $this->module->l('Enabled')
                            ],
                            [
                                'id' => 'grid_active_off',
                                'value' => 0,
                                'label' => $this->module->l('Disabled')
                            ],
                        ],
                    ],
                    [
                        'type' => 'switch',
                        'label' => $this->module->l('Enable list'),
                        'name' => 'PRODUCTSBYBRAND_LIST_ENABLE',
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => 'list_active_on',
                                'value' => 1,
                                'label' => $this->module->l('Enabled')
                            ],
                            [
                                'id' => 'list_active_off',
                                'value' => 0,
                                'label' => $this->module->l('Disabled')
                            ],
                        ],
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->module->l('Visible rows on desktop'),
                        'name' => 'PRODUCTSBYBRAND_DESKTOP_ROWS',
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->module->l('Visible rows on mobile'),
                        'name' => 'PRODUCTSBYBRAND_MOBILE_ROWS',
                    ],
                ],
                'submit' => [
                    'title' => $this->module->l('Save'),
                    'class' => 'btn btn-default pull-right',
                    'name' => 'submitProductsbybrandSettingsForm',
                ],
            ],
        ];

        $helper = $this->getHelperForm();
        $helper->fields_value = $this->getFormValues();

        return $helper->generateForm([$form]);
    }

    protected function getFormValues()
    {
        $defaults = [
            'PRODUCTSBYBRAND_MODULE_ENABLE' => 1,
            'PRODUCTSBYBRAND_GRID_ENABLE' => 1,
            'PRODUCTSBYBRAND_LIST_ENABLE' => 1,
            'PRODUCTSBYBRAND_DESKTOP_ROWS' => 2,
            'PRODUCTSBYBRAND_MOBILE_ROWS' => 4,
        ];

        $values = [];
        foreach($defaults as $key => $default){
            $values[$key] = Tools::getValue($key, Configuration::get($key, $default));
        }

        return $values;
    }

    protected function getHelperForm()
    {
        $helper = new HelperForm();
        $helper->table = $this->module->getTable();
        $helper->name_controller = $this->module->getName();
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&' . http_build_query(['configure' => $this->module->getName()]);
        $helper->submit_action = 'submitProductsbybrandSettingsForm';
        $helper->default_form_language = (int) Configuration::get('PS_LANG_DEFAULT');
        return $helper;
    }
}