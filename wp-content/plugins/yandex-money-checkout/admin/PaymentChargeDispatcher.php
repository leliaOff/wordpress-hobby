<?php


use YandexCheckout\Client;
use YandexCheckout\Common\Exceptions\ApiException;
use YandexCheckout\Model\PaymentStatus;

/**
 * Class PaymentChargeDispatcher
 */
class PaymentChargeDispatcher
{
    /**
     * @var Client
     */
    private $apiClient;

    /**
     * PaymentChargeDispatcher constructor.
     */
    public function __construct()
    {
        $this->apiClient = $this->getApiClient();
    }

    /**
     * @param $paymentId
     */
    public function tryChargePayment($paymentId)
    {
        try {
            $order   = $this->getOrderIdByPayment($paymentId);
            $payment = $this->apiClient->getPaymentInfo($paymentId);
            if ($payment->status == PaymentStatus::WAITING_FOR_CAPTURE) {
                $captureResult = YandexMoneyCheckoutPayment::capturePayment(
                    $paymentId,
                    $payment->getAmount(),
                    $order
                );

                if ($captureResult->status == PaymentStatus::SUCCEEDED) {
                    YandexMoneyLogger::info(
                        'Платеж подтвержден. Id заказа - '.$order->get_id().'. Id платежа - '.$paymentId.'.'
                    );
                    $order->payment_complete($paymentId);
                    $order->add_order_note(
                        'Номер транзакции в Яндекс.Кассе: '.$paymentId.'. Сумма: '.$payment->getAmount()->getValue()
                    );
                } elseif ($captureResult->status == PaymentStatus::CANCELED) {
                    YandexMoneyLogger::info(
                        'Платеж отменен. Id заказа - '.$order->get_id().'. Id платежа - '.$paymentId.'.'
                    );
                    $order->update_status(YandexMoneyCheckoutPayment::WC_STATUS_CANCELLED);
                }
            } elseif ($payment->status == PaymentStatus::CANCELED) {
                YandexMoneyLogger::warning(
                    'Неуспешный платеж. Id заказа - '.$order->get_id().'. Данные платежа - '.json_encode($payment).'.'
                );
                $order->update_status(YandexMoneyCheckoutPayment::WC_STATUS_CANCELLED);
            } elseif ($payment->status == PaymentStatus::SUCCEEDED) {
                YandexMoneyLogger::info(
                    'Успешный платеж. Id заказа - '.$order->get_id().'. Данные платежа - '.json_encode($payment).'.'
                );
                $order->payment_complete($paymentId);
                $order->add_order_note(
                    'Номер транзакции в Яндекс.Кассе: '.$paymentId.'. Сумма: '.$payment->getAmount()->getValue()
                );
            }
        } catch (ApiException $e) {
            YandexMoneyLogger::error('Api error: '.$e->getMessage());
        }
    }

    /**
     * @return Client
     */
    private function getApiClient()
    {
        $shopId       = get_option('ym_api_shop_id');
        $shopPassword = get_option('ym_api_shop_password');
        $apiClient    = new Client();
        $apiClient->setLogger(new YandexMoneyLogger());
        $apiClient->setAuth($shopId, $shopPassword);

        return $apiClient;
    }

    /**
     * @param $id
     *
     * @return null|WC_Order
     */
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
}