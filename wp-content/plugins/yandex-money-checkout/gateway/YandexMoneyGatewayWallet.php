<?php
use YandexCheckout\Model\PaymentMethodType;

if ( ! class_exists('YandexMoneyCheckoutGateway')) {
    return;
}

class YandexMoneyGatewayWallet extends YandexMoneyCheckoutGateway
{

    public $paymentMethod = PaymentMethodType::YANDEX_MONEY;

    public $id = 'ym_api_wallet';
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
        $this->icon = YandexMoneyCheckout::$pluginUrl.'/assets/images/pc.png';
        $this->method_description = __('');
        $this->method_title       = __('Кошелек Яндекс.Деньги', 'yandexcheckout');
        $this->defaultTitle       = __('Яндекс.Деньги', 'yandexcheckout');
        parent::__construct();
    }
}