<?php

namespace YaMoney\Request;

use YaMoney\Common\AbstractRequest;
use YaMoney\Common\Exceptions\InvalidPropertyValueException;
use YaMoney\Common\Exceptions\InvalidPropertyValueTypeException;
use YaMoney\Helpers\TypeCast;
use YaMoney\Model\ConfirmationType;
use YaMoney\Model\CurrencyCode;

/**
 * Класс запроса списка возможных способов оплаты
 *
 * @package YaMoney\Request
 *
 * @property string $accountId Идентификатор магазина
 * @property string $gatewayId Идентификатор товара
 * @property string $amount Сумма заказа
 * @property string $currency Код валюты
 * @property string $confirmationType Сценарий подтверждения платежа
 */
class PaymentOptionsRequest extends AbstractRequest implements PaymentOptionsRequestInterface
{
    /**
     * @var string Идентификатор магазина
     */
    private $_accountId;

    /**
     * @var string Идентификатор товара
     */
    private $_gatewayId;

    /**
     * @var string Сумма
     */
    private $_amount;

    /**
     * @var string Код валюты
     */
    private $_currency;

    /**
     * @var string Сценарий подтверждения платежа
     */
    private $_confirmationTypes;

    /**
     * Возвращает идентификатор магазина для которого требуется провести платёж
     * @return string Идентификатор магазина
     */
    public function getAccountId()
    {
        return $this->_accountId;
    }

    /**
     * Проверяет, был ли установлен идентификатор магазина
     * @return bool True если идентификатор магазина был установлен, false если нет
     */
    public function hasAccountId()
    {
        return $this->_accountId !== null;
    }

    /**
     * Устанавливает идентификатор магазина
     * @param string|null $value Значение идентификатора магазина, null если требуется удалить значение
     */
    public function setAccountId($value)
    {
        if ($value === null || $value === '') {
            $this->_accountId = null;
        } elseif (TypeCast::canCastToString($value)) {
            $this->_accountId = (string)$value;
        } else {
            throw new \InvalidArgumentException('Invalid account_id value type "' . gettype($value) . '"');
        }
    }

    /**
     * Возвращает идентификатор товара
     * @return string Идентификатор товара
     */
    public function getGatewayId()
    {
        return $this->_gatewayId;
    }

    /**
     * Проверяет, был ли установлен идентификатор товара
     * @return bool True если идентификатор товара был установлен, false если нет
     */
    public function hasGatewayId()
    {
        return !empty($this->_gatewayId);
    }

    /**
     * Устанавливает идентификатор товара
     * @param string|null $value Значение идентификатора товара, null если требуется удалить значение
     */
    public function setGatewayId($value)
    {
        if ($value === null || $value === '') {
            $this->_gatewayId = null;
        } elseif (TypeCast::canCastToString($value)) {
            $this->_gatewayId = (string)$value;
        } else {
            throw new \InvalidArgumentException('Invalid gateway_id value type "' . gettype($value) . '"');
        }
    }

    /**
     * Возвращает сумму заказа
     * @return string Сумма заказа
     */
    public function getAmount()
    {
        return $this->_amount;
    }

    /**
     * Проверяет, была ли установлена сумма заказа
     * @return bool True если сумма заказа была установлена, false если нет
     */
    public function hasAmount()
    {
        return !empty($this->_amount);
    }

    /**
     * Устанавливает сумму платежа
     * @param string|null $value Сумма платежа, null если требуется удалить значение
     */
    public function setAmount($value)
    {
        if ($value === null || $value === '') {
            $this->_amount = null;
        } else {
            if (!is_scalar($value)) {
                if (!is_object($value) || !method_exists($value, '__toString')) {
                    throw new InvalidPropertyValueTypeException(
                        'Invalid amount value type', 0, 'amount.value', $value
                    );
                }
                $value = (string)$value;
            }
            if (!is_numeric($value) || $value < 0.0) {
                throw new InvalidPropertyValueException(
                    'Invalid amount value "' . $value . '"', 0, 'amount.value', $value
                );
            } elseif ($value < 0.01) {
                $this->_amount = null;
            } else {
                $this->_amount = (string)round($value, 2);
            }
        }
    }

    /**
     * Возвращает код валюты, в которой осуществляется покупка
     * @return string Код валюты
     */
    public function getCurrency()
    {
        return $this->_currency;
    }

    /**
     * Проверяет был ли установлен код валюты
     * @return bool True если код валюты был установлен, false если нет
     */
    public function hasCurrency()
    {
        return !empty($this->_currency);
    }

    /**
     * Устанавливает код валюты в которой требуется провести платёж
     * @param string $value Код валюты, null если требуется удалить значение
     */
    public function setCurrency($value)
    {
        if ($value === null || $value === '') {
            $this->_currency = null;
        } elseif (TypeCast::canCastToEnumString($value)) {
            $value = strtoupper($value);
            if (!CurrencyCode::valueExists($value)) {
                throw new \InvalidArgumentException('Invalid currency value: "' . $value . '"');
            }
            $this->_currency = $value;
        } else {
            throw new \InvalidArgumentException('Invalid currency value type: "' . gettype($value) . '"');
        }
    }

    /**
     * Возвращает сценарий подтверждения платежа, для которого запрашивается список способов оплаты
     * @return string Сценарий подтверждения платежа
     */
    public function getConfirmationType()
    {
        return $this->_confirmationTypes;
    }

    /**
     * Проверяет был ли установлен сценарий подтверждения платежа
     * @return bool True если сценарий подтверждения платежа был установлен, false если нет
     */
    public function hasConfirmationType()
    {
        return !empty($this->_confirmationTypes);
    }

    /**
     * Устанавливает сценарий подтверждения платежа, для которого запрашивается список способов оплаты
     * @param string $value Сценарий подтверждения платежа
     */
    public function setConfirmationType($value)
    {
        if ($value === null || $value === '') {
            $this->_confirmationTypes = null;
        } elseif (TypeCast::canCastToEnumString($value)) {
            if (!ConfirmationType::valueExists((string)$value)) {
                throw new \InvalidArgumentException('Invalid confirmation_type value: "' . $value . '"');
            }
            $this->_confirmationTypes = $value;
        } else {
            throw new \InvalidArgumentException('Invalid confirmation_type value type: "' . gettype($value) . '"');
        }
    }

    /**
     * Валидирует текущий запрос, проверяет все ли нужные свойства установлены
     * @return bool True если запрос валиден, false если нет
     */
    public function validate()
    {
        if (empty($this->_accountId)) {
            $this->setValidationError('Account id not specified');
            return false;
        }
        return true;
    }

    /**
     * Возвращает инстанс билдера объектов запросов списока способов оплаты
     * @return PaymentOptionsRequestBuilder Билдер запросов списока способов оплаты
     */
    public static function builder()
    {
        return new PaymentOptionsRequestBuilder();
    }
}
