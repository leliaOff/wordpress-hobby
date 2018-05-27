<?php
use YandexCheckout\Model\PaymentMethodType;

if ( ! class_exists('YandexMoneyCheckoutGateway')) {
    return;
}

class YandexMoneyGatewaySberbank extends YandexMoneyCheckoutGateway
{

    public $paymentMethod = PaymentMethodType::SBERBANK;

    public $id = 'ym_api_sberbank';
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
    public $method_description = 'Оплата через Сбербанк';

    public function __construct()
    {
        $this->icon = YandexMoneyCheckout::$pluginUrl.'/assets/images/sb.png';
        $this->method_description = __('Оплата через Сбербанк', 'yandexcheckout');
        $this->method_title       = __('Сбербанк Онлайн', 'yandexcheckout');
        $this->defaultTitle       = __('Сбербанк Онлайн', 'yandexcheckout');
        parent::__construct();
    }
}