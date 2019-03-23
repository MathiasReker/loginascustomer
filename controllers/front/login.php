<?php
/**
 * 2019 Mathias R.
 *
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement
 *
 * @author    Mathias R.
 * @copyright Mathias R.
 * @license   Commercial license (You can not resell or redistribute this software.)
 */

class LoginAsCustomerLoginModuleFrontController extends ModuleFrontControllerCore
{
    public function initContent()
    {
        parent::initContent();
        $id_customer = (int) Tools::getValue('id_customer');
        $token = $this->module->makeToken($id_customer);
        if ($id_customer && (Tools::getValue('xtoken') == $token)) {
            $customer = new Customer((int) $id_customer);
            if (Validate::isLoadedObject($customer)) {
                $customer->logged = 1;
                $this->context->customer = $customer;
                $this->context->cookie->id_customer = (int) $customer->id;
                $this->context->cookie->customer_lastname = $customer->lastname;
                $this->context->cookie->customer_firstname = $customer->firstname;
                $this->context->cookie->logged = 1;
                $this->context->cookie->check_cgv = 1;
                $this->context->cookie->is_guest = $customer->isGuest();
                $this->context->cookie->passwd = $customer->passwd;
                $this->context->cookie->email = $customer->email;
                Tools::redirect('index.php?controller=my-account');
            }
        }
    }
}
