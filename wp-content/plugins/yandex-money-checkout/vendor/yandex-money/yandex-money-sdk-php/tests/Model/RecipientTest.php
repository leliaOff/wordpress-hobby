<?php

namespace Tests\YaMoney\Model;

use PHPUnit\Framework\TestCase;
use YaMoney\Helpers\Random;
use YaMoney\Helpers\StringObject;
use YaMoney\Model\Recipient;

class RecipientTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     *
     * @param $value
     */
    public function testGetSetAccountId($value)
    {
        $instance = new Recipient();

        self::assertEquals(null, $instance->getAccountId());
        self::assertEquals(null, $instance->accountId);
        $instance->setAccountId($value);
        self::assertEquals((string)$value, $instance->getAccountId());
        self::assertEquals((string)$value, $instance->accountId);

        $instance = new Recipient();
        $instance->accountId = $value;
        self::assertEquals((string)$value, $instance->getAccountId());
        self::assertEquals((string)$value, $instance->accountId);
    }

    /**
     * @dataProvider invalidDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetInvalidAccountId($value)
    {
        $instance = new Recipient();
        $instance->setAccountId($value);
    }

    /**
     * @dataProvider invalidDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetterInvalidAccountId($value)
    {
        $instance = new Recipient();
        $instance->accountId = $value;
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param $value
     */
    public function testGetSetGatewayId($value)
    {
        $instance = new Recipient();

        self::assertEquals(null, $instance->getGatewayId());
        self::assertEquals(null, $instance->gatewayId);
        $instance->setGatewayId($value);
        self::assertEquals((string)$value, $instance->getGatewayId());
        self::assertEquals((string)$value, $instance->gatewayId);

        $instance = new Recipient();
        $instance->gatewayId = $value;
        self::assertEquals((string)$value, $instance->getGatewayId());
        self::assertEquals((string)$value, $instance->gatewayId);
    }

    /**
     * @dataProvider invalidDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetInvalidGatewayId($value)
    {
        $instance = new Recipient();
        $instance->setGatewayId($value);
    }

    /**
     * @dataProvider invalidDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetterInvalidGatewayId($value)
    {
        $instance = new Recipient();
        $instance->gatewayId = $value;
    }

    public function validDataProvider()
    {
        $result = array(
            array(Random::str(1)),
            array(Random::str(2, 64)),
            array(new StringObject(Random::str(1, 32))),
            array(0),
            array(123),
        );
        return $result;
    }

    public function invalidDataProvider()
    {
        return array(
            array(null),
            array(''),
            array(true),
            array(false),
            array(array()),
            array(new \stdClass()),
        );
    }
}