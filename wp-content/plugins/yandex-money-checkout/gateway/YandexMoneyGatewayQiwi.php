<?php

use YandexCheckout\Common\Exceptions\InvalidPropertyValueException;
use YandexCheckout\Model\ConfirmationType;
use YandexCheckout\Model\PaymentData\PaymentDataQiwi;

if (!class_exists('YandexMoneyCheckoutGateway')) {
    return;
}

class YandexMoneyGatewayQiwi extends YandexMoneyCheckoutGateway
{
    public $confirmationType = ConfirmationType::REDIRECT;

    public $id = 'ym_api_qiwi';
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
        $this->paymentMethod      = new PaymentDataQiwi();
        $this->icon               = YandexMoneyCheckout::$pluginUrl.'/assets/images/qw.png';
        $this->method_description = __('');
        $this->method_title       = __('QIWI Wallet', 'yandexcheckout');
        $this->defaultTitle       = __('QIWI Wallet', 'yandexcheckout');
        parent::__construct();
        $this->has_fields = true;
    }

    public function payment_fields()
    {
        if ($description = $this->get_description()) {
            echo wpautop(wptexturize($description));
        }

        $phone_field = '<p class="form-row">
            <label for="phone-'.$this->id.'">'.__('Телефон, который привязан к Qiwi Wallet', 'yandexcheckout').'<span class="required">*</span></label>
			<input id="phone-'.$this->id.'" name="phone-'.$this->id.'"class="input-text" maxlength="18"/>
		</p>';

        echo '<fieldset>'.$phone_field.'</fieldset>';
    }

    public function createPayment($order)
    {
        if (isset($_POST['phone-ym_api_qiwi'])) {
            $phone = preg_replace('/[^\d]/', '', $_POST['phone-ym_api_qiwi']);
            try {
                $this->paymentMethod->setPhone($phone);
            } catch (Exception $e) {
                wc_add_notice(__('Поле телефон заполнено неверно.', 'yandexcheckout'), 'error');
            }
        }

        return parent::createPayment($order);
    }
}