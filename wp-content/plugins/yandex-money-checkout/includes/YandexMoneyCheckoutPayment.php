<?php
use YandexCheckout\Client;
use YandexCheckout\Model\Notification\NotificationSucceeded;
use YandexCheckout\Model\Notification\NotificationWaitingForCapture;
use YandexCheckout\Model\PaymentStatus;
use YandexCheckout\Request\Payments\Payment\CreateCaptureRequest;

/**
 * The payment-facing functionality of the plugin.
 */
class YandexMoneyCheckoutPayment
{
    const WC_STATUS_COMPLETED = 'wc-completed';
    const WC_STATUS_PENDING = 'wc-pending';
    const WC_STATUS_CANCELLED = 'wc-cancelled';
    const WC_STATUS_ON_HOLD = 'wc-on-hold';

    const INSTALLMENTS_MIN_AMOUNT = 3000;

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     *
     * @param      string $plugin_name The name of the plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version     = $version;
    }

    public function addGateways($methods)
    {
        global $woocommerce;
        $installmentsOn = !isset($woocommerce->cart)
            || !isset($woocommerce->cart->total)
            || $woocommerce->cart->total >= self::INSTALLMENTS_MIN_AMOUNT;

        $shopPassword = get_option('ym_api_shop_password');
        $prefix       = substr($shopPassword, 0, 4);

        $testMode = $prefix == "test";
        if ($testMode) {
            $methods[] = 'YandexMoneyGatewayCard';
            $methods[] = 'YandexMoneyGatewayWallet';
        } else {
            if (get_option('ym_api_pay_mode') == '1') {
                $methods[] = 'YandexMoneyGatewayEPL';
                if ((get_option('ym_api_epl_installments') == '1') && $installmentsOn) {
                    $methods[] = 'YandexMoneyGatewayInstallments';
                }
            } else {
                $methods[] = 'YandexMoneyGatewayCard';
                $methods[] = 'YandexMoneyGatewayAlfabank';
                $methods[] = 'YandexMoneyGatewayQiwi';
                $methods[] = 'YandexMoneyGatewayCash';
                $methods[] = 'YandexMoneyGatewayWebmoney';
                $methods[] = 'YandexMoneyGatewaySberbank';
                $methods[] = 'YandexMoneyGatewayWallet';
                if ($installmentsOn) {
                    $methods[] = 'YandexMoneyGatewayInstallments';
                }
            }
        }

        return $methods;
    }

    public function loadGateways()
    {
        require_once plugin_dir_path(dirname(__FILE__)).'gateway/YandexMoneyCheckoutGateway.php';
        require_once plugin_dir_path(dirname(__FILE__)).'gateway/YandexMoneyGatewayCard.php';
        require_once plugin_dir_path(dirname(__FILE__)).'gateway/YandexMoneyGatewayAlfabank.php';
        require_once plugin_dir_path(dirname(__FILE__)).'gateway/YandexMoneyGatewayQiwi.php';
        require_once plugin_dir_path(dirname(__FILE__)).'gateway/YandexMoneyGatewayWebmoney.php';
        require_once plugin_dir_path(dirname(__FILE__)).'gateway/YandexMoneyGatewayCash.php';
        require_once plugin_dir_path(dirname(__FILE__)).'gateway/YandexMoneyGatewaySberbank.php';
        require_once plugin_dir_path(dirname(__FILE__)).'gateway/YandexMoneyGatewayWallet.php';
        require_once plugin_dir_path(dirname(__FILE__)).'gateway/YandexMoneyGatewayEPL.php';
        require_once plugin_dir_path(dirname(__FILE__)).'gateway/YandexMoneyGatewayInstallments.php';
    }

    public function processCallback()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == "POST" &&
            isset($_REQUEST['yandex_money'])
            && $_REQUEST['yandex_money'] == 'callback'
        ) {

            YandexMoneyLogger::info('Notification init');
            $body           = @file_get_contents('php://input');
            $callbackParams = json_decode($body, true);
            YandexMoneyLogger::info('Notification body: '.$body);

            if (!json_last_error()) {
                try {
                    $this->processNotification($callbackParams);
                } catch (Exception $e) {
                    YandexMoneyLogger::error("Error while process notification: ".$e->getMessage());
                }
            } else {
                header("HTTP/1.1 400 Bad Request");
                header("Status: 400 Bad Request");
            }
            exit();
        }
    }

    private function getOrderIdByPayment($id)
    {
        global $wpdb;

        $query  = "
			SELECT *
			FROM {$wpdb->prefix}postmeta
			WHERE meta_value = %s AND meta_key = '_transaction_id'
		";
        $sql    = $wpdb->prepare($query, $id);
        $result = $wpdb->get_row($sql);

        if ($result) {
            $orderId = $result->post_id;
            $order   = new WC_Order($orderId);

            return $order;
        }

        return null;
    }

    public function returnUrl()
    {
        global $wp;
        if (!empty($wp->query_vars['order-pay'])) {

            $this->order_pay($wp->query_vars['order-pay']);

        }
    }

    /**
     * @param $paymentId
     * @param $amount
     * @param WC_Order $order
     *
     * @return null|\YandexCheckout\Request\Payments\Payment\CreateCaptureResponse
     */
    public static function capturePayment($paymentId, $amount, $order)
    {
        $shopId       = get_option('ym_api_shop_id');
        $shopPassword = get_option('ym_api_shop_password');
        $apiClient    = new Client();
        $apiClient->setAuth($shopId, $shopPassword);
        $apiClient->setLogger(new YandexMoneyLogger());
        $captureRequest = CreateCaptureRequest::builder()->setAmount($amount)->build();

        $result = null;
        $tries  = 0;
        do {
            $result = $apiClient->capturePayment(
                $captureRequest,
                $paymentId,
                $paymentId
            );
            if ($result === null) {
                $tries++;
                YandexMoneyLogger::info(
                    sprintf(__('Попытка подтвеждения платежа № %1$s. Id заказа - %2$s. Id платежа - %3$s.',
                        'yandexcheckout'), $tries, $order->get_id(), $paymentId)
                );
                if ($tries > 3) {
                    YandexMoneyLogger::warning(sprintf(
                            __('Достигнуто максимальное количество попыток подтверждения. Id заказа - %1$s. Id платежа - %2$s.',
                                'yandexcheckout'), $order->get_id(), $paymentId)
                    );
                    break;
                }
                sleep(2);
            }
        } while ($result === null);

        return $result;
    }

    /**
     * @param $callbackParams
     */
    protected function processNotification($callbackParams)
    {
        try {
            $notificationModel = ($callbackParams['event'] === YandexCheckout\Model\NotificationEventType::PAYMENT_SUCCEEDED)
                ? new NotificationSucceeded($callbackParams)
                : new NotificationWaitingForCapture($callbackParams);

        } catch (\Exception $e) {
            YandexMoneyLogger::error('Invalid notification object - ' . $e->getMessage());
            header("HTTP/1.1 400 Bad Request");
            header("Status: 400 Bad Request");
            exit();
        }

        $paymentResponce = $notificationModel->getObject();
        $shopId          = get_option('ym_api_shop_id');
        $shopPassword    = get_option('ym_api_shop_password');
        $apiClient       = new Client();
        $apiClient->setAuth($shopId, $shopPassword);
        $apiClient->setLogger(new YandexMoneyLogger());
        $paymentId = $paymentResponce->id;
        $order     = $this->getOrderIdByPayment($paymentId);
        if (!$order) {
            header("HTTP/1.1 404 Not Found");
            header("Status: 404 Not Found");
            exit();
        }

        $paymentInfo = $apiClient->getPaymentInfo($paymentId);

        if ($paymentInfo->status == PaymentStatus::SUCCEEDED) {
            YandexMoneyLogger::info(sprintf(__('Платеж подтвержден. Id заказа - %1$s. Id платежа - %2$s.', 'yandexcheckout'),
                $order->get_id(), $paymentId));
            $order->payment_complete($order->get_transaction_id());
        } elseif ($paymentInfo->status == PaymentStatus::CANCELED) {
            YandexMoneyLogger::info(sprintf(
                    __('Платеж отменен. Id заказа - %1$s. Id платежа - %2$s.', 'yandexcheckout'),
                    $order->get_id(), $paymentId)
            );
            $order->update_status(self::WC_STATUS_CANCELLED);
        }


        if ($order->get_status() !== self::WC_STATUS_COMPLETED) {
            switch ($paymentInfo->status) {
                case PaymentStatus::WAITING_FOR_CAPTURE:
                    $paymentInfoResult = self::capturePayment(
                        $paymentResponce->getId(),
                        $paymentResponce->getAmount(),
                        $order
                    );
                    if ($paymentInfoResult->status == PaymentStatus::SUCCEEDED) {
                        YandexMoneyLogger::info(sprintf(
                                __('Платеж подтвержден. Id заказа - %1$s. Id платежа - %2$s.','yandexcheckout'), $order->get_id(),
                                $paymentResponce->id)
                        );
                        $order->payment_complete($paymentId);
                        $order->add_order_note(sprintf(
                                __('Номер транзакции в Яндекс.Кассе: %1$s. Сумма: %2$s', 'yandexcheckout'
                                ), $paymentId, $paymentInfo->getAmount()->getValue())
                        );
                        YandexMoneyLogger::info(sprintf(
                                __('Статус заказа %1$s'), $order->get_status())
                        );
                        header("HTTP/1.1 200 OK");
                        header("Status: 200 OK");
                    } elseif ($paymentInfoResult->status == PaymentStatus::CANCELED) {
                        YandexMoneyLogger::info(sprintf(
                                __('Платеж отменен. Id заказа - %1$s. Id платежа - %2$s.', 'yandexcheckout'), $order->get_id(),
                                $paymentResponce->id)
                        );
                        $order->update_status(self::WC_STATUS_CANCELLED);
                        YandexMoneyLogger::info(sprintf(
                                __('Статус заказа. %1$s', 'yandexcheckout'), self::WC_STATUS_CANCELLED)
                        );
                        header("HTTP/1.1 200 OK");
                        header("Status: 200 OK");
                    }
                    break;
                case PaymentStatus::PENDING:
                    $order->update_status(self::WC_STATUS_ON_HOLD);
                    YandexMoneyLogger::info(sprintf(
                            __('Статус заказа. %1$s', 'yandexcheckout'), self::WC_STATUS_ON_HOLD)
                    );
                    header("HTTP/1.1 402 Payment Required");
                    header("Status: 402 Payment Required");
                    break;
                case PaymentStatus::SUCCEEDED:
                    YandexMoneyLogger::info(sprintf(
                            __('Платеж подтвержден. Id заказа - %1$s. Id платежа - %2$s.', 'yandexcheckout'), $order->get_id(),
                            $paymentResponce->id)
                    );
                    $order->payment_complete($paymentId);
                    $order->add_order_note(sprintf(
                            __('Номер транзакции в Яндекс.Кассе: %1$s. Сумма: %2$s', 'yandexcheckout'), $paymentId,
                            $paymentInfo->getAmount()->getValue())
                    );
                    YandexMoneyLogger::info(sprintf(
                            __('Статус заказа. %1$s', 'yandexcheckout'), $order->get_status())
                    );
                    header("HTTP/1.1 200 OK");
                    header("Status: 200 OK");
                    break;
                case PaymentStatus::CANCELED:
                    YandexMoneyLogger::info(sprintf(
                            __('Платеж отменен. Id заказа - %1$s. Id платежа - %2$s.', 'yandexcheckout'), $order->get_id(),
                            $paymentResponce->id)
                    );
                    $order->update_status(self::WC_STATUS_CANCELLED);
                    YandexMoneyLogger::info(sprintf(
                            __('Статус заказа. %1$s', 'yandexcheckout'), self::WC_STATUS_CANCELLED)
                    );
                    header("HTTP/1.1 200 OK");
                    header("Status: 200 OK");
                    break;
            }
        } else {
            header("HTTP/1.1 200 OK");
            header("Status: 200 OK");
        }
        exit();
    }

    public function validStatuses($statuses, $order)
    {
        $statuses[] = 'processing';
        $statuses[] = 'completed';

        return $statuses;
    }

    function showInstallmentsInfo()
    {
        $this->enqueue_styles();
        $this->enqueue_scripts();

        global $product;

        $showInfoInKassaMode = (get_option('ym_api_pay_mode') === '1') && (get_option('ym_api_epl_installments') === '1');

        $options = (array)get_option('woocommerce_ym_api_installments_settings');
        $installments_enabled = (!empty($options['enabled']) && $options['enabled'] === 'yes');
        $showInfoInShopMode = (get_option('ym_api_pay_mode') === '0') && $installments_enabled;

        if (!$showInfoInKassaMode && !$showInfoInShopMode) {
            return;
        }

        $shopId = get_option('ym_api_shop_id');
        $price = $product->get_price();
        $language = mb_substr(get_bloginfo('language'), 0, 2);

        echo <<<END
<div class="installments-info"></div>
<script>
    jQuery(document).ready(function(){
        const yamoneyCheckoutCreditUI = YandexCheckoutCreditUI({
            shopId: $shopId,
            sum: $price,
            language: '$language'
        });
        yamoneyCheckoutCreditUI({
            type: 'info',
            domSelector: '.installments-info'
        });
    });
</script>
END;
    }

    function showExtraInstallmentsCheckoutInfo()
    {
        global $woocommerce;
        $sum = (float)$woocommerce->cart->total;

        $shopId = get_option('ym_api_shop_id');

        $extraInfo = __(' (%s ₽ в месяц)', 'yandexcheckout');

        echo <<<END
<script>
        jQuery.get("https://money.yandex.ru/credit/order/ajax/credit-pre-schedule?shopId="
            + $shopId + "&sum=" + $sum, function (data) {
            const ym_installments_amount_text = "$extraInfo";
            if (ym_installments_amount_text && data && data.amount) {
                jQuery('label[for=payment_method_ym_api_installments] img').before(ym_installments_amount_text.replace('%s', data.amount));
            }
        });
</script>
END;

    }

    /**
     * Register the stylesheets
     */
    private function enqueue_styles()
    {
        wp_enqueue_style(
            $this->plugin_name,
            YandexMoneyCheckout::$pluginUrl . '/assets/css/yandex-checkout.css'
        );
    }

    /**
     * Register the JavaScript
     */
    private function enqueue_scripts()
    {
        wp_enqueue_script(
            $this->plugin_name,
            'https://static.yandex.net/kassa/pay-in-parts/ui/v1/'
        );
    }
}
