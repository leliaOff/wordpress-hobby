<?php
use YandexCheckout\Model\ConfirmationType;
use YandexCheckout\Model\PaymentData\PaymentDataAlfabank;

if (!class_exists('YandexMoneyCheckoutGateway')) {
    return;
}

class YandexMoneyGatewayAlfabank extends YandexMoneyCheckoutGateway
{
    public $has_fields = true;

    public $confirmationType = ConfirmationType::EXTERNAL;

    public $id = 'ym_api_alfabank';
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
        $this->paymentMethod      = new PaymentDataAlfabank();
        $this->icon               = YandexMoneyCheckout::$pluginUrl.'/assets/images/ab.png';
        $this->method_description = __('Оплата через Альфа банк', 'yandexcheckout');
        $this->method_title       = __('Альфа-Клик', 'yandexcheckout');
        $this->defaultTitle       = __('Альфа-Клик', 'yandexcheckout');
        parent::__construct();
        $this->has_fields = true;
    }

    public function payment_fields()
    {
        if ($description = $this->get_description()) {
            echo wpautop(wptexturize($description));
        }

        $phone_field = '<p class="form-row">
            <label for="login-'.$this->id.'"> '.__('Укажите логин, и мы выставим счет в Альфа-Клике. После этого останется подтвердить платеж на сайте интернет-банка.', 'yandexcheckout')
                       .'<span class="required">*</span></label>
			<input id="login-'.$this->id.'" name="login-'.$this->id.'" class="input-text" inputmode="numeric" autocomplete="off" autocorrect="no" autocapitalize="no" spellcheck="no" type="tel" maxlength="12"/>
		</p>';

        echo '<fieldset>'.$phone_field.'</fieldset>';
    }

    public function createPayment($order)
    {
        if (isset($_POST['login-ym_api_alfabank'])) {
            try {
                $this->paymentMethod->setLogin($_POST['login-ym_api_alfabank']);
            } catch (Exception $e) {
                wc_add_notice(__('Поле логин заполнено неверно.', 'yandexcheckout'), 'error');
            }
        }

        return parent::createPayment($order);
    }
}