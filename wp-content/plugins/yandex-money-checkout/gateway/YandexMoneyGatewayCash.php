<?php
use YandexCheckout\Model\PaymentMethodType;

if ( ! class_exists('YandexMoneyCheckoutGateway')) {
    return;
}


class YandexMoneyGatewayCash extends YandexMoneyCheckoutGateway
{

    public $paymentMethod = PaymentMethodType::CASH;

    public $id = 'ym_api_cash';
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
    public $method_description = '';

    public function __construct()
    {
        $this->icon = YandexMoneyCheckout::$pluginUrl.'/assets/images/gp.png';
        $this->method_description = __('Наличные', 'yandexcheckout');
        $this->method_title       = __('Наличные', 'yandexcheckout');
        $this->defaultTitle       = __('Наличные', 'yandexcheckout');
        parent::__construct();
    }
}