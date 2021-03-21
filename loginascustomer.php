<?php
/**
 * 2019 Mathias R.
 *
 * NOTICE OF LICENSE
 *
 * This file is licensed under the Software License Agreement
 * With the purchase or the installation of the software in your application
 * you accept the license agreement.
 *
 * @author    Mathias R.
 * @copyright Mathias R.
 * @license   Commercial license (You can not resell or redistribute this software.)
 */

if (!\defined('_PS_VERSION_')) {
    exit;
}

class LoginAsCustomer extends Module
{
    public function __construct()
    {
        $this->name = 'loginascustomer';
        $this->tab = 'administration';
        $this->version = '1.0.1';
        $this->author = 'Mathias Reker';
        $this->module_key = '';
        $this->need_instance = 0;
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l('Login as customer');
        $this->description = $this->l('Allows you login as a customer.');
        $this->ps_versions_compliancy = [
            'min' => '1.7',
            'max' => _PS_VERSION_,
        ];
    }

    public function install()
    {
        return parent::install() && $this->registerHook('displayAdminCustomers');
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    public function hookDisplayAdminCustomers($params)
    {
        $customer = new Customer((int) $params['id_customer']);
        $link = $this->context->link->getModuleLink(
            $this->name,
            'login',
            [
                'id_customer' => $customer->id,
                'xtoken' => $this->makeToken($customer->id),
            ]
        );
        if (!Validate::isLoadedObject($customer)) {
            return;
        }

        // Support 1.7.6+ with Symfony customer controller.
        if (0 === \strpos(_PS_VERSION_, '1.7.6')) {
            return '
                <div class="col">
                    <div class="card">
                        <h3 class="card-header">
                            <i class="material-icons">launch</i>
                            ' . $this->l('Login As Customer') . '
                        </h3>
                        <div class="card-body">
                            <a class="btn btn-primary" href="' . $link . '" target="_blank">' . $this->l('Login as') . ' ' . $customer->firstname . ' ' . $customer->lastname . '</a>
                        </div>
                    </div>
                </div>
            ';
            // Older versions.
        }

        return '
                <div class="col-lg-6">
                    <div class="panel">
                        <div class="panel-heading">
                            <i class="icon-file"></i> ' . $this->l('Login As Customer') . ' <span class="badge"></span>
                        </div>
                        <div class="btn-group">
                            <a class="btn btn-default pull-right" href="' . $link . '" target="_blank"><i class="icon-user"></i> ' . $this->l('Login as') . ' ' . $customer->firstname . ' ' . $customer->lastname . '</a>
                        </div>
                    </div>
                </div>
            ';
    }

    public function makeToken($id_customer)
    {
        return \md5(_COOKIE_KEY_ . $id_customer . \date('Ymd'));
    }
}
