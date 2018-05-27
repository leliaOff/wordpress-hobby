<?php

namespace YaMoney\Request\Payments;

use YaMoney\Common\AbstractRequest;
use YaMoney\Common\Exceptions\InvalidPropertyValueException;
use YaMoney\Common\Exceptions\InvalidPropertyValueTypeException;
use YaMoney\Helpers\TypeCast;
use YaMoney\Model\AmountInterface;
use YaMoney\Model\PaymentData\AbstractPaymentData;
use YaMoney\Model\ConfirmationAttributes\AbstractConfirmationAttributes;
use YaMoney\Model\Metadata;
use YaMoney\Model\Receipt;
use YaMoney\Model\ReceiptInterface;
use YaMoney\Model\RecipientInterface;

/**
 * Класс объекта запроса к API на проведение нового платежа
 *
 * @package YaMoney\Request\Payments
 *
 * @property RecipientInterface $recipient Получатель платежа, если задан
 * @property AmountInterface $amount Сумма создаваемого платежа
 * @property ReceiptInterface $receipt Данные фискального чека 54-ФЗ
 * @property string $referenceId Айди заказа на стороне мерчанта
 * @property string $paymentToken Одноразовый токен для проведения оплаты, сформированный Yandex.Checkout JS widget
 * @property string $paymentMethodId Идентификатор записи о сохраненных платежных данных покупателя
 * @property AbstractPaymentData $paymentMethodData Данные используемые для создания метода оплаты
 * @property AbstractConfirmationAttributes $confirmation Способ подтверждения платежа
 * @property bool $savePaymentMethod Сохранить платежные данные для последующего использования. Значение true
 * инициирует создание многоразового payment_method.
 * @property bool $capture Автоматически принять поступившую оплату
 * @property string $clientIp IPv4 или IPv6-адрес покупателя. Если не указан, используется IP-адрес TCP-подключения.
 * @property Metadata $metadata Метаданные привязанные к платежу
 */
class CreatePaymentRequest extends AbstractRequest implements CreatePaymentRequestInterface
{
    /**
     * @var RecipientInterface Получатель платежа
     */
    private $_recipient;

    /**
     * @var AmountInterface Сумма платежа
     */
    private $_amount;

    /**
     * @var Receipt Данные фискального чека 54-ФЗ
     */
    private $_receipt;

    /**
     * @var string Одноразовый токен для проведения оплаты, сформированный Yandex.Checkout JS widget
     */
    private $_paymentToken;

    /**
     * @var string Идентификатор записи о сохраненных платежных данных покупателя
     */
    private $_paymentMethodId;

    /**
     * @var AbstractPaymentData Данные используемые для создания метода оплаты
     */
    private $_paymentMethodData;

    /**
     * @var AbstractConfirmationAttributes Способ подтверждения платежа
     */
    private $_confirmation;

    /**
     * @var bool Сохранить платежные данные для последующего использования. Значение true инициирует создание многоразового payment_method.
     */
    private $_savePaymentMethod;

    /**
     * @var bool Автоматически принять поступившую оплату
     */
    private $_capture;

    /**
     * @var string IPv4 или IPv6-адрес покупателя. Если не указан, используется IP-адрес TCP-подключения.
     */
    private $_clientIp;

    /**
     * @var Metadata Метаданные привязанные к платежу
     */
    private $_metadata;

    /**
     * Возвращает объект получателя платежа
     * @return RecipientInterface|null Объект с информацией о получателе платежа или null если получатель не задан
     */
    public function getRecipient()
    {
        return $this->_recipient;
    }

    /**
     * Проверяет наличие получателя платежа в запросе
     * @return bool True если получатель платежа задан, false если нет
     */
    public function hasRecipient()
    {
        return !empty($this->_recipient);
    }

    /**
     * Возвращает сумму заказа
     * @return AmountInterface Сумма заказа
     */
    public function getAmount()
    {
        return $this->_amount;
    }

    /**
     * Устанавливает сумму платежа
     * @param AmountInterface $value Сумма платежа
     */
    public function setAmount(AmountInterface $value)
    {
        $this->_amount = $value;
    }

    /**
     * Возвращает чек, если он есть
     * @return ReceiptInterface|null Данные фискального чека 54-ФЗ или null если чека нет
     */
    public function getReceipt()
    {
        return $this->_receipt;
    }

    /**
     * Устанавливает чек
     * @param ReceiptInterface $value Данные фискального чека 54-ФЗ
     */
    public function setReceipt(ReceiptInterface $value)
    {
        $this->_receipt = $value;
    }

    /**
     * Проверяет наличие чека в создаваемом платеже
     * @return bool True если чек есть, false если нет
     */
    public function hasReceipt()
    {
        return $this->_receipt !== null;
    }

    /**
     * Удаляет чек из запроса
     */
    public function removeReceipt()
    {
        $this->_receipt = null;
    }

    /**
     * Устанавливает объект с информацией о получателе платежа
     * @param RecipientInterface|null $value Инстанс объекта информации о получателе платежа или null
     */
    public function setRecipient($value)
    {
        if ($value === null || $value === '') {
            $this->_recipient = null;
        } elseif (is_object($value) && $value instanceof RecipientInterface) {
            $this->_recipient = $value;
        } else {
            throw new \InvalidArgumentException('Invalid recipient value type');
        }
    }

    /**
     * Возвращает одноразовый токен для проведения оплаты
     * @return string Одноразовый токен для проведения оплаты, сформированный Yandex.Checkout JS widget
     */
    public function getPaymentToken()
    {
        return $this->_paymentToken;
    }

    /**
     * Проверяет наличие одноразового токена для проведения оплаты
     * @return bool True если токен установлен, false если нет
     */
    public function hasPaymentToken()
    {
        return !empty($this->_paymentToken);
    }

    /**
     * Устанавливает одноразовый токен для проведения оплаты, сформированный Yandex.Checkout JS widget
     * @param string $value Одноразовый токен для проведения оплаты
     *
     * @throws InvalidPropertyValueException Выбрасывается если переданное значение длинее 200 символов
     * @throws InvalidPropertyValueTypeException Выбрасывается если переданное значение не является строкой
     */
    public function setPaymentToken($value)
    {
        if ($value === null || $value === '') {
            $this->_paymentToken = null;
        } elseif (TypeCast::canCastToString($value)) {
            $length = mb_strlen((string)$value, 'utf-8');
            if ($length > 200) {
                throw new InvalidPropertyValueException(
                    'Invalid paymentToken value', 0, 'CreatePaymentRequest.paymentToken', $value
                );
            }
            $this->_paymentToken = (string)$value;
        } else {
            throw new InvalidPropertyValueTypeException(
                'Invalid paymentToken value type', 0, 'CreatePaymentRequest.paymentToken', $value
            );
        }
    }

    /**
     * Устанавливает идентификатор закиси платёжных данных покупателя
     * @return string Идентификатор записи о сохраненных платежных данных покупателя
     */
    public function getPaymentMethodId()
    {
        return $this->_paymentMethodId;
    }

    /**
     * Проверяет наличие идентификатора записи о платёжных данных покупателя
     * @return bool True если идентификатор задан, false если нет
     */
    public function hasPaymentMethodId()
    {
        return !empty($this->_paymentMethodId);
    }

    /**
     * Устанавливает идентификатор записи о сохранённых данных покупателя
     * @param string $value Идентификатор записи о сохраненных платежных данных покупателя
     *
     * @throws InvalidPropertyValueTypeException Генерируется если переданные значение не является строкой или null
     */
    public function setPaymentMethodId($value)
    {
        if ($value === null || $value === '') {
            $this->_paymentMethodId = null;
        } elseif (TypeCast::canCastToString($value)) {
            $this->_paymentMethodId = (string)$value;
        } else {
            throw new InvalidPropertyValueTypeException(
                'Invalid paymentMethodId value type in CreatePaymentRequest',
                0,
                'CreatePaymentRequest.CreatePaymentRequest',
                $value
            );
        }
    }

    /**
     * Возвращает данные для создания метода оплаты
     * @return AbstractPaymentData Данные используемые для создания метода оплаты
     */
    public function getPaymentMethodData()
    {
        return $this->_paymentMethodData;
    }

    /**
     * Проверяет установлен ли объект с методом оплаты
     * @return bool True если объект метода оплаты установлен, false если нет
     */
    public function hasPaymentMethodData()
    {
        return !empty($this->_paymentMethodData);
    }

    /**
     * Устанавливает объект с информацией для создания метода оплаты
     * @param AbstractPaymentData|null $value Объект с создания метода оплаты или null
     *
     * @throws InvalidPropertyValueTypeException Выбрасывается если был передан объект невалидного типа
     */
    public function setPaymentMethodData($value)
    {
        if ($value === null || $value === '') {
            $this->_paymentMethodData = null;
        } elseif (is_object($value) && $value instanceof AbstractPaymentData) {
            $this->_paymentMethodData = $value;
        } else {
            throw new InvalidPropertyValueTypeException(
                'Invalid paymentMethodData value type in CreatePaymentRequest',
                0,
                'CreatePaymentRequest.paymentMethodData',
                $value
            );
        }
    }

    /**
     * Возвращает способ подтверждения платежа
     * @return AbstractConfirmationAttributes Способ подтверждения платежа
     */
    public function getConfirmation()
    {
        return $this->_confirmation;
    }

    /**
     * Проверяет был ли установлен способ подтверждения платежа
     * @return bool True если способ подтверждения платежа был установлен, false если нет
     */
    public function hasConfirmation()
    {
        return $this->_confirmation !== null;
    }

    /**
     * Устанавливает способ подтверждения платежа
     * @param AbstractConfirmationAttributes|null $value Способ подтверждения платежа
     *
     * @throws InvalidPropertyValueTypeException Выбрасывается если переданное значение не является объектом типа
     * AbstractConfirmationAttributes или null
     */
    public function setConfirmation($value)
    {
        if ($value === null || $value === '') {
            $this->_confirmation = null;
        } elseif (is_object($value) && $value instanceof AbstractConfirmationAttributes) {
            $this->_confirmation = $value;
        } else {
            throw new InvalidPropertyValueTypeException(
                'Invalid confirmation value type in CreatePaymentRequest',
                0,
                'CreatePaymentRequest.confirmation',
                $value
            );
        }
    }

    /**
     * Возвращает флаг сохранения платёжных данных
     * @return bool Флаг сохранения платёжных данных
     */
    public function getSavePaymentMethod()
    {
        return $this->_savePaymentMethod;
    }

    /**
     * Проверяет был ли установлен флаг сохранения платёжных данных
     * @return bool True если флыг был установлен, false если нет
     */
    public function hasSavePaymentMethod()
    {
        return $this->_savePaymentMethod !== null;
    }

    /**
     * Устанавливает флаг сохранения платёжных данных. Значение true инициирует создание многоразового payment_method.
     * @param bool $value Сохранить платежные данные для последующего использования
     *
     * @throws InvalidPropertyValueTypeException Генерируется если переданный аргумент не кастится в bool
     */
    public function setSavePaymentMethod($value)
    {
        if ($value === null || $value === '') {
            $this->_savePaymentMethod = null;
        } elseif (TypeCast::canCastToBoolean($value)) {
            $this->_savePaymentMethod = (bool)$value;
        } else {
            throw new InvalidPropertyValueTypeException(
                'Invalid savePaymentMethod value type in CreatePaymentRequest',
                0,
                'CreatePaymentRequest.savePaymentMethod',
                $value
            );
        }
    }

    /**
     * Возвращает флаг автоматического принятия поступившей оплаты
     * @return bool True если требуется автоматически принять поступившую оплату, false если нет
     */
    public function getCapture()
    {
        return $this->_capture;
    }

    /**
     * Проверяет был ли установлен флаг автоматического приняти поступившей оплаты
     * @return bool True если флаг автоматического принятия оплаты был установлен, false если нет
     */
    public function hasCapture()
    {
        return $this->_capture !== null;
    }

    /**
     * Устанавливает флаг автоматического принятия поступившей оплаты
     * @param bool $value Автоматически принять поступившую оплату
     *
     * @throws InvalidPropertyValueTypeException Генерируется если переданный аргумент не кастится в bool
     */
    public function setCapture($value)
    {
        if ($value === null || $value === '') {
            $this->_capture = null;
        } elseif (TypeCast::canCastToBoolean($value)) {
            $this->_capture = (bool)$value;
        } else {
            throw new InvalidPropertyValueTypeException(
                'Invalid capture value type in CreatePaymentRequest', 0, 'CreatePaymentRequest.capture', $value
            );
        }
    }

    /**
     * Возвращает IPv4 или IPv6-адрес покупателя
     * @return string IPv4 или IPv6-адрес покупателя
     */
    public function getClientIp()
    {
        return $this->_clientIp;
    }

    /**
     * Проверяет был ли установлен IPv4 или IPv6-адрес покупателя
     * @return bool True если IP адрес покупателя был установлен, false если нет
     */
    public function hasClientIp()
    {
        return $this->_clientIp !== null;
    }

    /**
     * Устанавливает IP адрес покупателя
     * @param string $value IPv4 или IPv6-адрес покупателя
     *
     * @throws InvalidPropertyValueTypeException Выбрасывается если переданный аргумент не является строкой
     */
    public function setClientIp($value)
    {
        if ($value === null || $value === '') {
            $this->_clientIp = null;
        } elseif (TypeCast::canCastToString($value)) {
            $this->_clientIp = (string)$value;
        } else {
            throw new InvalidPropertyValueTypeException(
                'Invalid clientIp value type in CreatePaymentRequest', 0, 'CreatePaymentRequest.clientIp', $value
            );
        }
    }

    /**
     * Возвращает данные оплаты установленные мерчантом
     * @return Metadata Метаданные, привязанные к платежу
     */
    public function getMetadata()
    {
        return $this->_metadata;
    }

    /**
     * Проверяет были ли установлены метаданные заказа
     * @return bool True если метаданные были установлены, false если нет
     */
    public function hasMetadata()
    {
        return !empty($this->_metadata) && $this->_metadata->count() > 0;
    }

    /**
     * Устанавливает метаданные, привязанные к платежу
     * @param Metadata|null $value Метаданные платежа, устанавливаемые мерчантом
     *
     * @throws InvalidPropertyValueTypeException Выбрасывается если переданные данные не удалось интерпретировать как
     * метаданные платежа
     */
    public function setMetadata($value)
    {
        if ($value === null || (is_array($value) && empty($value))) {
            $this->_metadata = null;
        } elseif (is_object($value) && $value instanceof Metadata) {
            $this->_metadata = $value;
        } elseif (is_array($value)) {
            $this->_metadata = new Metadata();
            foreach ($value as $key => $val) {
                $this->_metadata->offsetSet($key, (string)$val);
            }
        } else {
            throw new InvalidPropertyValueTypeException(
                'Invalid metadata value type in CreatePaymentRequest', 0, 'CreatePaymentRequest.metadata', $value
            );
        }
    }

    /**
     * Проверяет на валидность текущий объект
     * @return bool True если объект запроса валиден, false если нет
     */
    public function validate()
    {
        $amount = $this->_amount;
        if ($amount === null) {
            $this->setValidationError('Payment amount not specified');
            return false;
        }
        if ($amount->getValue() <= 0.0) {
            $this->setValidationError('Invalid payment amount value: ' . $amount->getValue());
            return false;
        }
        if ($this->_receipt !== null && $this->_receipt->notEmpty()) {
            $email = $this->_receipt->getEmail();
            $phone = $this->_receipt->getPhone();
            if (empty($email) && empty($phone)) {
                $this->setValidationError('Both email and phone values are empty in receipt');
                return false;
            }
            if ($this->_receipt->getTaxSystemCode() === null) {
                foreach ($this->_receipt->getItems() as $item) {
                    if ($item->getVatCode() === null) {
                        $this->setValidationError('Item vat_id and receipt tax_system_id not specified');
                        return false;
                    }
                }
            }
        }
        if ($this->hasPaymentToken()) {
            if ($this->hasPaymentMethodId()) {
                $this->setValidationError('Both paymentToken and paymentMethodID values are specified');
                return false;
            }
            if ($this->hasPaymentMethodData()) {
                $this->setValidationError('Both paymentToken and paymentData values are specified');
                return false;
            }
        } elseif ($this->hasPaymentMethodId()) {
            if ($this->hasPaymentMethodData()) {
                $this->setValidationError('Both paymentMethodID and paymentData values are specified');
                return false;
            }
        } /* elseif (!$this->hasPaymentMethodData()) {
            $this->setValidationError('Payment method not specified, set paymentToken, paymentMethodID or paymentData');
            return false;
        }*/
        return true;
    }

    /**
     * Возвращает билдер объектов запросов создания платежа
     * @return CreatePaymentRequestBuilder Инстанс билдера объектов запрсов
     */
    public static function builder()
    {
        return new CreatePaymentRequestBuilder();
    }
}
