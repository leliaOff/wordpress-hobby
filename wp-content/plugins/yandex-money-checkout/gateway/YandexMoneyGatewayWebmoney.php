<?php
use YandexCheckout\Model\PaymentMethodType;

if ( ! class_exists('YandexMoneyCheckoutGateway')) {
    return;
}


class YandexMoneyGatewayWebmoney extends YandexMoneyCheckoutGateway
{

    public $paymentMethod = PaymentMethodType::WEBMONEY;

    public $id = 'ym_api_webmoney';
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
        $this->icon = YandexMoneyCheckout::$pluginUrl.'/assets/images/wm.png';
        $this->method_description = __('');
        $this->method_title       = __('Webmoney', 'yandexcheckout');
        $this->defaultTitle       = __('Webmoney', 'yandexcheckout');
        parent::__construct();
    }
}