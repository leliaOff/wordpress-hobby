<?php
use YandexCheckout\Model\PaymentMethodType;

if ( ! class_exists('YandexMoneyCheckoutGateway')) {
    return;
}

class YandexMoneyGatewayInstallments extends YandexMoneyCheckoutGateway
{

    public $paymentMethod = PaymentMethodType::INSTALLMENTS;

    public $id = 'ym_api_installments';
    /**
     * Gateway title.
     * @var string
     */
    public $method_title;

    public $defaultTitle;

    /**
     * Gateway description.
     * @var string
     */
    public $method_description;

    public function __construct()
    {
        $this->icon = YandexMoneyCheckout::$pluginUrl.'/assets/images/installments.png';
        $this->method_description = __('Заплатить по частям', 'yandexcheckout');
        $this->method_title       = __('Заплатить по частям', 'yandexcheckout');
        $this->defaultTitle       = __('Заплатить по частям', 'yandexcheckout');
        parent::__construct();
    }
}