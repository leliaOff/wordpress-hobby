<?php

if ( ! class_exists('YandexMoneyCheckoutGateway')) {
    return;
}

class YandexMoneyGatewayEPL extends YandexMoneyCheckoutGateway
{
    public $paymentMethod = '';

    public $id = 'ym_api_epl';
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
        $this->icon = YandexMoneyCheckout::$pluginUrl.'/assets/images/kassa.png';
        $this->method_description = __('');
        $this->method_title       = __('Яндекс.Касса (банковские карты, электронные деньги и другое)', 'yandexcheckout');
        $this->defaultTitle       = __('Яндекс.Касса (банковские карты, электронные деньги и другое)', 'yandexcheckout');
        parent::__construct();
    }
}