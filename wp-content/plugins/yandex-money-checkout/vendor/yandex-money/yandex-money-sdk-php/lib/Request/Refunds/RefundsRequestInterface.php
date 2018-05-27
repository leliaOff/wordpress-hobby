<?php

namespace YaMoney\Request\Refunds;

/**
 * Интерфейс объекта запроса списка возвратов
 *
 * @package YaMoney\Request\Refunds
 *
 * @property-read string $refundId
 * @property-read string $paymentId Идентификатор платежа
 * @property-read string $accountId Идентификатор магазина
 * @property-read string $gatewayId Идентификатор товара
 * @property-read \DateTime $createdGte Время создания, от (включительно)
 * @property-read \DateTime $createdGt Время создания, от (не включая)
 * @property-read \DateTime $createdLte Время создания, до (включительно)
 * @property-read \DateTime $createdLt Время создания, до (не включая)
 * @property-read \DateTime $authorizedGte Время проведения операции, от (включительно)
 * @property-read \DateTime $authorizedGt Время проведения операции, от (не включая)
 * @property-read \DateTime $authorizedLte Время проведения, до (включительно)
 * @property-read \DateTime $authorizedLt Время проведения, до (не включая)
 * @property-read string $status Статус возврата
 * @property-read string $nextPage Токен для получения следующей страницы выборки
 */
interface RefundsRequestInterface
{
    /**
     * Возвращает идентификатор возврата
     * @return string Идентификатор возврата
     */
    function getRefundId();

    /**
     * Проверяет был ли установлен идентификатор возврата
     * @return bool True если идентификатор возврата был установлен, false если не был
     */
    function hasRefundId();

    /**
     * Возвращает идентификатор платежа если он задан или null
     * @return string|null Идентификатор платежа
     */
    function getPaymentId();

    /**
     * Проверяет, был ли задан идентификатор платежа
     * @return bool True если идентификатор был задан, false если нет
     */
    function hasPaymentId();

    /**
     * Возвращает идентификатор магазина, если он был задан
     * @return string|null Идентификатор магазина
     */
    function getAccountId();

    /**
     * Проверяет, был ли установлен идентификатор магазина
     * @return bool True если идентификатор магазина был установлен, false если нет
     */
    function hasAccountId();

    /**
     * Возвращает идентификатор товара
     * @return string|null Идентификатор товара
     */
    function getGatewayId();

    /**
     * Проверяет был ли установлен идентификатор товара
     * @return bool True если идентификатор товара был установлен, false если нет
     */
    function hasGatewayId();

    /**
     * Возвращает дату создания от которой будут возвращены возвраты или null если дата не была установлена
     * @return \DateTime|null Время создания, от (включительно)
     */
    function getCreatedGte();

    /**
     * Проверяет была ли установлена дата создания от которой выбираются возвраты
     * @return bool True если дата была установлена, false если нет
     */
    function hasCreatedGte();

    /**
     * Возвращает дату создания от которой будут возвращены возвраты или null если дата не была установлена
     * @return \DateTime|null Время создания, от (не включая)
     */
    function getCreatedGt();

    /**
     * Проверяет была ли установлена дата создания от которой выбираются возвраты
     * @return bool True если дата была установлена, false если нет
     */
    function hasCreatedGt();

    /**
     * Возвращает дату создания до которой будут возвращены возвраты или null если дата не была установлена
     * @return \DateTime|null Время создания, до (включительно)
     */
    function getCreatedLte();

    /**
     * Проверяет была ли установлена дата создания до которой выбираются возвраты
     * @return bool True если дата была установлена, false если нет
     */
    function hasCreatedLte();

    /**
     * Возвращает дату создания до которой будут возвращены возвраты или null если дата не была установлена
     * @return \DateTime|null Время создания, до (не включая)
     */
    function getCreatedLt();

    /**
     * Проверяет была ли установлена дата создания до которой выбираются возвраты
     * @return bool True если дата была установлена, false если нет
     */
    function hasCreatedLt();

    /**
     * Возвращает дату проведения от которой будут возвращены возвраты или null если дата не была установлена
     * @return \DateTime|null Время проведения операции, от (включительно)
     */
    function getAuthorizedGte();

    /**
     * Проверяет была ли установлена дата проведения от которой выбираются возвраты
     * @return bool True если дата была установлена, false если нет
     */
    function hasAuthorizedGte();

    /**
     * Возвращает дату проведения от которой будут возвращены возвраты или null если дата не была установлена
     * @return \DateTime|null Время проведения операции, от (не включая)
     */
    function getAuthorizedGt();

    /**
     * Проверяет была ли установлена дата проведения от которой выбираются возвраты
     * @return bool True если дата была установлена, false если нет
     */
    function hasAuthorizedGt();

    /**
     * Возвращает дату проведения до которой будут возвращены возвраты или null если дата не была установлена
     * @return \DateTime|null Время проведения, до (включительно)
     */
    function getAuthorizedLte();

    /**
     * Проверяет была ли установлена дата проведения до которой выбираются возвраты
     * @return bool True если дата была установлена, false если нет
     */
    function hasAuthorizedLte();

    /**
     * Возвращает дату проведения до которой будут возвращены платежи возвраты или null если она не была установлена
     * @return \DateTime|null Время проведения, до (не включая)
     */
    function getAuthorizedLt();

    /**
     * Проверяет была ли установлена дата проведения до которой выбираются вовзраты
     * @return bool True если дата была установлена, false если нет
     */
    function hasAuthorizedLt();

    /**
     * Возвращает статус выбираемых возвратов или null если он до этого не был установлен
     * @return string|null Статус выбираемых возвратов
     */
    function getStatus();

    /**
     * Проверяет был ли установлен статус выбираемых возвратов
     * @return bool True если статус был установлен, false если нет
     */
    function hasStatus();

    /**
     * Возвращает токен для получения следующей страницы выборки
     * @return string|null Токен для получения следующей страницы выборки
     */
    function getNextPage();

    /**
     * Проверяет был ли установлен токен следующей страницы
     * @return bool True если токен был установлен, false если нет
     */
    function hasNextPage();
}
