<?php
/**
 * This file is part of the loginascustomer package.
 *
 * @author Mathias Reker
 * @copyright Mathias Reker
 * @license https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
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
        $this->version = '2.0.0';
        $this->author = 'Mathias Reker';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.6',
            'max' => _PS_VERSION_,
        ];
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l('Login as customer');
        $this->description = $this->l('Allows you login as a customer.');
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

        $customerButtonText = \sprintf('%s %s %s', $this->l('Login as'), $customer->firstname, $customer->lastname);
        $customerLoginText = $this->l('Login As Customer');

        if (Tools::version_compare(_PS_VERSION_, '1.7.6.0')) {
            return '
                <div class="col-lg-6">
                    <div class="panel">
                        <div class="panel-heading">
                            <i class="icon-file"></i> ' . $customerLoginText . ' <span class="badge"></span>
                        </div>
                        <div class="btn-group">
                            <a class="btn btn-default pull-right" href="' . $link . '" target="_blank" rel="noopener noreferrer nofollow"><i class="icon-user"></i> ' . $customerButtonText . '</a>
                        </div>
                    </div>
                </div>
            ';
        }

        return '
            <div class="col">
                <div class="card">
                    <h3 class="card-header">
                        <i class="material-icons">launch</i>
                        ' . $customerLoginText . '
                    </h3>
                    <div class="card-body">
                        <a class="btn btn-primary" href="' . $link . '" target="_blank" rel="noopener noreferrer nofollow">' . $customerButtonText . '</a>
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
