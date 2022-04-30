<?php
if (!defined('_PS_VERSION_')) {
    exit;
}
class MailModule extends Module
{
    private $ref_product;
    public function __construct()
    {
        $this->name = "MailModule";
        $this->tab = "emailing";
        $this->version = "1.0";
        $this->author = "Thibaut Chabrier";
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('MailModule');
        $this->description = $this->l("Envoie un mail lorsque la quantité d'un article change");
        $this->confirmUninstall = $this->l("Etes-vous sur de vouloir supprimer ce module ?");
    }

    public function install()
    {
        if (
            parent::install()
            && $this->registerHook("actionUpdateQuantity")
        ) {
            return true;
        }

        return false;
    }

    public function uninstall()
    {
        if (parent::uninstall()) {
            return true;
        }

        return false;
    }


    public function hookActionUpdateQuantity($products)
    {

        if (
            $products['id_product_attribute'] == 0
            && $this->ref_product->quantity != $products['quantity']
        ) {

            $this->ref_product->quantity = $products['quantity'];
            $singleOrPlurial = ($products['quantity'] > 1) ? " articles" : " article";
            Mail::Send(
                (int)(Configuration::get('PS_LANG_DEFAULT')),
                'contact',
                'Quantité Stock',
                array(
                    '{email}' => Configuration::get('PS_SHOP_EMAIL'),
                    '{message}' => "Le produit d'ID " . $products['id_product'] . " a un stock de " . $products['quantity'] . $singleOrPlurial
                ),
                'test@test.com',
                NULL,
                NULL,
                NULL
            );
        }
    }
}
