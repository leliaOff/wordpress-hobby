<?php
use YandexCheckout\Model\PaymentMethodType;

if ( ! class_exists('YandexMoneyCheckoutGateway')) {
    return;
}

class YandexMoneyGatewayCard extends YandexMoneyCheckoutGateway
{

    public $paymentMethod = PaymentMethodType::BANK_CARD;

    public $id = 'ym_api_bank_card';
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
    public $method_description = 'Оплата с произвольной банковской карты';

    public function __construct()
    {
        $this->icon = YandexMoneyCheckout::$pluginUrl.'/assets/images/ac.png';
        $this->method_description = __('Оплата с произвольной банковской карты', 'yandexcheckout');
        $this->method_title       = __('Банковские карты', 'yandexcheckout');
        $this->defaultTitle       = __('Банковские карты — Visa, Mastercard и Maestro, «Мир»', 'yandexcheckout');
        parent::__construct();
    }
}