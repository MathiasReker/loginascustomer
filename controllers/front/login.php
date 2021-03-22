<?php
/**
 * This file is part of the loginascustomer package.
 *
 * @author Mathias Reker
 * @copyright Mathias Reker
 * @license https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 */

class LoginAsCustomerLoginModuleFrontController extends ModuleFrontControllerCore
{
    public function initContent()
    {
        parent::initContent();
        $id_customer = (int) Tools::getValue('id_customer');
        $token = $this->module->makeToken($id_customer);
        if ($id_customer && (Tools::getValue('xtoken') === $token)) {
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
                $this->context->updateCustomer($customer);
                Tools::redirect('index.php?controller=my-account');
            }
        }
    }
}
