<?php

namespace Tests\YaMoney\Request\Payments;

use PHPUnit\Framework\TestCase;
use YaMoney\Helpers\Random;
use YaMoney\Model\ConfirmationAttributes\ConfirmationAttributesExternal;
use YaMoney\Model\Metadata;
use YaMoney\Model\MonetaryAmount;
use YaMoney\Model\PaymentData\PaymentDataQiwi;
use YaMoney\Model\Receipt;
use YaMoney\Model\ReceiptItem;
use YaMoney\Model\Recipient;
use YaMoney\Request\Payments\CreatePaymentRequest;
use YaMoney\Request\Payments\CreatePaymentRequestBuilder;

class CreatePaymentRequestTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     * @param $options
     */
    public function testRecipient($options)
    {
        $instance = new CreatePaymentRequest();

        self::assertFalse($instance->hasRecipient());
        self::assertNull($instance->getRecipient());
        self::assertNull($instance->recipient);

        $instance->setRecipient($options['recipient']);
        if (empty($options['recipient'])) {
            self::assertFalse($instance->hasRecipient());
            self::assertNull($instance->getRecipient());
            self::assertNull($instance->recipient);
        } else {
            self::assertTrue($instance->hasRecipient());
            self::assertSame($options['recipient'], $instance->getRecipient());
            self::assertSame($options['recipient'], $instance->recipient);
        }

        $instance->setRecipient(null);
        self::assertFalse($instance->hasRecipient());
        self::assertNull($instance->getRecipient());
        self::assertNull($instance->recipient);

        $instance->recipient = $options['recipient'];
        if (empty($options['recipient'])) {
            self::assertFalse($instance->hasRecipient());
            self::assertNull($instance->getRecipient());
            self::assertNull($instance->recipient);
        } else {
            self::assertTrue($instance->hasRecipient());
            self::assertSame($options['recipient'], $instance->getRecipient());
            self::assertSame($options['recipient'], $instance->recipient);
        }
    }

    /**
     * @dataProvider invalidRecipientDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetInvalidRecipient($value)
    {
        $instance = new CreatePaymentRequest();
        $instance->setRecipient($value);
    }

    /**
     * @dataProvider validDataProvider
     * @param $options
     */
    public function testAmount($options)
    {
        $instance = new CreatePaymentRequest();

        self::assertNull($instance->getAmount());
        self::assertNull($instance->amount);

        $instance->setAmount($options['amount']);

        self::assertSame($options['amount'], $instance->getAmount());
        self::assertSame($options['amount'], $instance->amount);
    }

    /**
     * @dataProvider validDataProvider
     * @param $options
     */
    public function testPaymentToken($options)
    {
        $instance = new CreatePaymentRequest();

        self::assertFalse($instance->hasPaymentToken());
        self::assertNull($instance->getPaymentToken());
        self::assertNull($instance->paymentToken);

        $instance->setPaymentToken($options['paymentToken']);
        if (empty($options['paymentToken'])) {
            self::assertFalse($instance->hasPaymentToken());
            self::assertNull($instance->getPaymentToken());
            self::assertNull($instance->paymentToken);
        } else {
            self::assertTrue($instance->hasPaymentToken());
            self::assertSame($options['paymentToken'], $instance->getPaymentToken());
            self::assertSame($options['paymentToken'], $instance->paymentToken);
        }

        $instance->setPaymentToken(null);
        self::assertFalse($instance->hasPaymentToken());
        self::assertNull($instance->getPaymentToken());
        self::assertNull($instance->paymentToken);

        $instance->paymentToken = $options['paymentToken'];
        if (empty($options['paymentToken'])) {
            self::assertFalse($instance->hasPaymentToken());
            self::assertNull($instance->getPaymentToken());
            self::assertNull($instance->paymentToken);
        } else {
            self::assertTrue($instance->hasPaymentToken());
            self::assertSame($options['paymentToken'], $instance->getPaymentToken());
            self::assertSame($options['paymentToken'], $instance->paymentToken);
        }
    }

    /**
     * @dataProvider invalidPaymentTokenDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetInvalidPaymentToken($value)
    {
        $instance = new CreatePaymentRequest();
        $instance->setPaymentToken($value);
    }

    /**
     * @dataProvider validDataProvider
     * @param $options
     */
    public function testPaymentMethodId($options)
    {
        $instance = new CreatePaymentRequest();

        self::assertFalse($instance->hasPaymentMethodId());
        self::assertNull($instance->getPaymentMethodId());
        self::assertNull($instance->paymentMethodId);

        $instance->setPaymentMethodId($options['paymentMethodId']);
        if (empty($options['paymentMethodId'])) {
            self::assertFalse($instance->hasPaymentMethodId());
            self::assertNull($instance->getPaymentMethodId());
            self::assertNull($instance->paymentMethodId);
        } else {
            self::assertTrue($instance->hasPaymentMethodId());
            self::assertSame($options['paymentMethodId'], $instance->getPaymentMethodId());
            self::assertSame($options['paymentMethodId'], $instance->paymentMethodId);
        }

        $instance->setPaymentMethodId(null);
        self::assertFalse($instance->hasPaymentMethodId());
        self::assertNull($instance->getPaymentMethodId());
        self::assertNull($instance->paymentMethodId);

        $instance->paymentMethodId = $options['paymentMethodId'];
        if (empty($options['paymentMethodId'])) {
            self::assertFalse($instance->hasPaymentMethodId());
            self::assertNull($instance->getPaymentMethodId());
            self::assertNull($instance->paymentMethodId);
        } else {
            self::assertTrue($instance->hasPaymentMethodId());
            self::assertSame($options['paymentMethodId'], $instance->getPaymentMethodId());
            self::assertSame($options['paymentMethodId'], $instance->paymentMethodId);
        }
    }

    /**
     * @dataProvider invalidPaymentMethodIdDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetInvalidPaymentMethodId($value)
    {
        $instance = new CreatePaymentRequest();
        $instance->setPaymentMethodId($value);
    }

    /**
     * @dataProvider validDataProvider
     * @param $options
     */
    public function testPaymentData($options)
    {
        $instance = new CreatePaymentRequest();

        self::assertFalse($instance->hasPaymentMethodData());
        self::assertNull($instance->getPaymentMethodData());
        self::assertNull($instance->paymentMethodData);

        $instance->setPaymentMethodData($options['paymentMethodData']);
        if (empty($options['paymentMethodData'])) {
            self::assertFalse($instance->hasPaymentMethodData());
            self::assertNull($instance->getPaymentMethodData());
            self::assertNull($instance->paymentMethodData);
        } else {
            self::assertTrue($instance->hasPaymentMethodData());
            self::assertSame($options['paymentMethodData'], $instance->getPaymentMethodData());
            self::assertSame($options['paymentMethodData'], $instance->paymentMethodData);
        }

        $instance->setPaymentMethodData(null);
        self::assertFalse($instance->hasPaymentMethodData());
        self::assertNull($instance->getPaymentMethodData());
        self::assertNull($instance->paymentMethodData);

        $instance->paymentMethodData = $options['paymentMethodData'];
        if (empty($options['paymentMethodData'])) {
            self::assertFalse($instance->hasPaymentMethodData());
            self::assertNull($instance->getPaymentMethodData());
            self::assertNull($instance->paymentMethodData);
        } else {
            self::assertTrue($instance->hasPaymentMethodData());
            self::assertSame($options['paymentMethodData'], $instance->getPaymentMethodData());
            self::assertSame($options['paymentMethodData'], $instance->paymentMethodData);
        }
    }

    /**
     * @dataProvider invalidPaymentDataDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetInvalidPaymentData($value)
    {
        $instance = new CreatePaymentRequest();
        $instance->setPaymentMethodData($value);
    }

    /**
     * @dataProvider validDataProvider
     * @param $options
     */
    public function testConfirmationAttributes($options)
    {
        $instance = new CreatePaymentRequest();

        self::assertFalse($instance->hasConfirmation());
        self::assertNull($instance->getConfirmation());
        self::assertNull($instance->confirmation);

        $instance->setConfirmation($options['confirmation']);
        if (empty($options['confirmation'])) {
            self::assertFalse($instance->hasConfirmation());
            self::assertNull($instance->getConfirmation());
            self::assertNull($instance->confirmation);
        } else {
            self::assertTrue($instance->hasConfirmation());
            self::assertSame($options['confirmation'], $instance->getConfirmation());
            self::assertSame($options['confirmation'], $instance->confirmation);
        }

        $instance->setConfirmation(null);
        self::assertFalse($instance->hasConfirmation());
        self::assertNull($instance->getConfirmation());
        self::assertNull($instance->confirmation);

        $instance->confirmation = $options['confirmation'];
        if (empty($options['confirmation'])) {
            self::assertFalse($instance->hasConfirmation());
            self::assertNull($instance->getConfirmation());
            self::assertNull($instance->confirmation);
        } else {
            self::assertTrue($instance->hasConfirmation());
            self::assertSame($options['confirmation'], $instance->getConfirmation());
            self::assertSame($options['confirmation'], $instance->confirmation);
        }
    }

    /**
     * @dataProvider invalidConfirmationAttributesDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetInvalidConfirmationAttributes($value)
    {
        $instance = new CreatePaymentRequest();
        $instance->setConfirmation($value);
    }

    /**
     * @dataProvider validDataProvider
     * @param $options
     */
    public function testCreateRecurring($options)
    {
        $instance = new CreatePaymentRequest();

        self::assertFalse($instance->hasSavePaymentMethod());
        self::assertNull($instance->getSavePaymentMethod());
        self::assertNull($instance->savePaymentMethod);

        $instance->setSavePaymentMethod($options['savePaymentMethod']);
        if ($options['savePaymentMethod'] === null || $options['savePaymentMethod'] === '') {
            self::assertFalse($instance->hasSavePaymentMethod());
            self::assertNull($instance->getSavePaymentMethod());
            self::assertNull($instance->savePaymentMethod);
        } else {
            self::assertTrue($instance->hasSavePaymentMethod());
            self::assertSame($options['savePaymentMethod'], $instance->getSavePaymentMethod());
            self::assertSame($options['savePaymentMethod'], $instance->savePaymentMethod);
        }

        $instance->setSavePaymentMethod(null);
        self::assertFalse($instance->hasSavePaymentMethod());
        self::assertNull($instance->getSavePaymentMethod());
        self::assertNull($instance->savePaymentMethod);

        $instance->savePaymentMethod = $options['savePaymentMethod'];
        if ($options['savePaymentMethod'] === null || $options['savePaymentMethod'] === '') {
            self::assertFalse($instance->hasSavePaymentMethod());
            self::assertNull($instance->getSavePaymentMethod());
            self::assertNull($instance->savePaymentMethod);
        } else {
            self::assertTrue($instance->hasSavePaymentMethod());
            self::assertSame($options['savePaymentMethod'], $instance->getSavePaymentMethod());
            self::assertSame($options['savePaymentMethod'], $instance->savePaymentMethod);
        }
    }

    /**
     * @dataProvider invalidBooleanDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetInvalidCreateRecurring($value)
    {
        $instance = new CreatePaymentRequest();
        $instance->setSavePaymentMethod($value);
    }

    /**
     * @dataProvider validDataProvider
     * @param $options
     */
    public function testCapture($options)
    {
        $instance = new CreatePaymentRequest();

        self::assertFalse($instance->hasCapture());
        self::assertNull($instance->getCapture());
        self::assertNull($instance->capture);

        $instance->setCapture($options['capture']);
        if ($options['capture'] === null || $options['capture'] === '') {
            self::assertFalse($instance->hasCapture());
            self::assertNull($instance->getCapture());
            self::assertNull($instance->capture);
        } else {
            self::assertTrue($instance->hasCapture());
            self::assertSame($options['capture'], $instance->getCapture());
            self::assertSame($options['capture'], $instance->capture);
        }

        $instance->setCapture(null);
        self::assertFalse($instance->hasCapture());
        self::assertNull($instance->getCapture());
        self::assertNull($instance->capture);

        $instance->capture = $options['capture'];
        if ($options['capture'] === null || $options['capture'] === '') {
            self::assertFalse($instance->hasCapture());
            self::assertNull($instance->getCapture());
            self::assertNull($instance->capture);
        } else {
            self::assertTrue($instance->hasCapture());
            self::assertSame($options['capture'], $instance->getCapture());
            self::assertSame($options['capture'], $instance->capture);
        }
    }

    /**
     * @dataProvider invalidBooleanDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetInvalidCapture($value)
    {
        $instance = new CreatePaymentRequest();
        $instance->setCapture($value);
    }

    /**
     * @dataProvider validDataProvider
     * @param $options
     */
    public function testClientIp($options)
    {
        $instance = new CreatePaymentRequest();

        self::assertFalse($instance->hasClientIp());
        self::assertNull($instance->getClientIp());
        self::assertNull($instance->clientIp);

        $instance->setClientIp($options['clientIp']);
        if (empty($options['clientIp'])) {
            self::assertFalse($instance->hasClientIp());
            self::assertNull($instance->getClientIp());
            self::assertNull($instance->clientIp);
        } else {
            self::assertTrue($instance->hasClientIp());
            self::assertSame($options['clientIp'], $instance->getClientIp());
            self::assertSame($options['clientIp'], $instance->clientIp);
        }

        $instance->setClientIp(null);
        self::assertFalse($instance->hasClientIp());
        self::assertNull($instance->getClientIp());
        self::assertNull($instance->clientIp);

        $instance->clientIp = $options['clientIp'];
        if (empty($options['clientIp'])) {
            self::assertFalse($instance->hasClientIp());
            self::assertNull($instance->getClientIp());
            self::assertNull($instance->clientIp);
        } else {
            self::assertTrue($instance->hasClientIp());
            self::assertSame($options['clientIp'], $instance->getClientIp());
            self::assertSame($options['clientIp'], $instance->clientIp);
        }
    }

    /**
     * @dataProvider invalidClientIpDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetInvalidClientIp($value)
    {
        $instance = new CreatePaymentRequest();
        $instance->setClientIp($value);
    }

    public function invalidClientIpDataProvider()
    {
        return array(
            array(array()),
            array(new \stdClass()),
            array(true),
            array(false),
        );
    }

    /**
     * @dataProvider validDataProvider
     * @param $options
     */
    public function testMetadata($options)
    {
        $instance = new CreatePaymentRequest();

        self::assertFalse($instance->hasMetadata());
        self::assertNull($instance->getMetadata());
        self::assertNull($instance->metadata);

        $expected = $options['metadata'];
        if ($expected instanceof Metadata) {
            $expected = $expected->toArray();
        }

        $instance->setMetadata($options['metadata']);
        if (empty($options['metadata'])) {
            self::assertFalse($instance->hasMetadata());
            self::assertNull($instance->getMetadata());
            self::assertNull($instance->metadata);
        } else {
            self::assertTrue($instance->hasMetadata());
            self::assertSame($expected, $instance->getMetadata()->toArray());
            self::assertSame($expected, $instance->metadata->toArray());
        }

        $instance->setMetadata(null);
        self::assertFalse($instance->hasMetadata());
        self::assertNull($instance->getMetadata());
        self::assertNull($instance->metadata);

        $instance->metadata = $options['metadata'];
        if (empty($options['metadata'])) {
            self::assertFalse($instance->hasMetadata());
            self::assertNull($instance->getMetadata());
            self::assertNull($instance->metadata);
        } else {
            self::assertTrue($instance->hasMetadata());
            self::assertSame($expected, $instance->getMetadata()->toArray());
            self::assertSame($expected, $instance->metadata->toArray());
        }
    }

    public function testValidate()
    {
        $instance = new CreatePaymentRequest();

        self::assertFalse($instance->validate());

        $amount = new MonetaryAmount();
        $instance->setAmount($amount);
        self::assertFalse($instance->validate());

        $instance->setAmount(new MonetaryAmount(10));
        self::assertTrue($instance->validate());

        $instance->setPaymentToken(Random::str(10));
        self::assertTrue($instance->validate());
        $instance->setPaymentMethodId(Random::str(10));
        self::assertFalse($instance->validate());
        $instance->setPaymentMethodId(null);
        self::assertTrue($instance->validate());
        $instance->setPaymentMethodData(new PaymentDataQiwi());
        self::assertFalse($instance->validate());
        $instance->setPaymentToken(null);
        self::assertTrue($instance->validate());
        $instance->setPaymentMethodId(Random::str(10));
        self::assertFalse($instance->validate());
        $instance->setPaymentMethodId(null);
        self::assertTrue($instance->validate());

        $receipt = new Receipt();
        $instance->setReceipt($receipt);
        $item = new ReceiptItem();
        $item->setPrice(new MonetaryAmount(10));
        $item->setDescription('test');
        $receipt->addItem($item);
        self::assertFalse($instance->validate());
        $receipt->setPhone('123123');
        self::assertFalse($instance->validate());
        $item->setVatCode(3);
        self::assertTrue($instance->validate());
        $item->setVatCode(null);
        self::assertFalse($instance->validate());
        $receipt->setTaxSystemCode(4);
        self::assertTrue($instance->validate());

        self::assertNotNull($instance->getReceipt());
        $instance->removeReceipt();
        self::assertTrue($instance->validate());
        self::assertNull($instance->getReceipt());

        $instance->setAmount(new MonetaryAmount());
        self::assertFalse($instance->validate());
    }

    public function testBuilder()
    {
        $builder = CreatePaymentRequest::builder();
        self::assertTrue($builder instanceof CreatePaymentRequestBuilder);
    }

    /**
     * @dataProvider invalidMetadataDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetInvalidMetadata($value)
    {
        $instance = new CreatePaymentRequest();
        $instance->setMetadata($value);
    }

    public function validDataProvider()
    {
        $metadata = new Metadata();
        $metadata->test = 'test';
        $result = array(
            array(
                array(
                    'recipient' => null,
                    'amount' => new MonetaryAmount(Random::int(1, 1000000)),
                    'referenceId' => null,
                    'paymentToken' => null,
                    'paymentMethodId' => null,
                    'paymentMethodData' => null,
                    'confirmation' => null,
                    'savePaymentMethod' => null,
                    'capture' => null,
                    'clientIp' => null,
                    'metadata' => null,
                ),
            ),
            array(
                array(
                    'recipient' => null,
                    'amount' => new MonetaryAmount(Random::int(1, 1000000)),
                    'referenceId' => '',
                    'paymentToken' => '',
                    'paymentMethodId' => '',
                    'paymentMethodData' => '',
                    'confirmation' => '',
                    'savePaymentMethod' => '',
                    'capture' => '',
                    'clientIp' => '',
                    'metadata' => array(),
                ),
            ),
        );
        for ($i = 0; $i < 10; $i++) {
            $request = array(
                'recipient' => new Recipient(),
                'amount' => new MonetaryAmount(Random::int(1, 1000000)),
                'referenceId' => uniqid(),
                'paymentToken' => uniqid(),
                'paymentMethodId' => uniqid(),
                'paymentMethodData' => new PaymentDataQiwi(),
                'confirmation' => new ConfirmationAttributesExternal(),
                'savePaymentMethod' => mt_rand(0, 1) ? true : false,
                'capture' => mt_rand(0, 1) ? true : false,
                'clientIp' => long2ip(mt_rand(0, pow(2, 32))),
                'metadata' => $i == 0 ? $metadata : array('test' => 'test'),
            );
            $result[] = array($request);
        }
        return $result;
    }

    public function invalidRecipientDataProvider()
    {
        return array(
            array(false),
            array(true),
            array(1),
            array(Random::str(10)),
            array(new \stdClass()),
        );
    }

    public function invalidReferenceIdDataProvider()
    {
        return array(
            array(false),
            array(true),
            array(new \stdClass()),
            array(array()),
            array(Random::str(32)),
        );
    }

    public function invalidPaymentTokenDataProvider()
    {
        return array(
            array(false),
            array(true),
            array(new \stdClass()),
            array(array()),
            array(Random::str(201)),
        );
    }

    public function invalidPaymentMethodIdDataProvider()
    {
        return array(
            array(false),
            array(true),
            array(new \stdClass()),
            array(array()),
        );
    }

    public function invalidPaymentDataDataProvider()
    {
        return array(
            array(array()),
            array(false),
            array(true),
            array(1),
            array(Random::str(10)),
            array(new \stdClass()),
        );
    }

    public function invalidConfirmationAttributesDataProvider()
    {
        return array(
            array(array()),
            array(false),
            array(true),
            array(1),
            array(Random::str(10)),
            array(new \stdClass()),
        );
    }

    public function invalidMetadataDataProvider()
    {
        return array(
            array(false),
            array(true),
            array(1),
            array(Random::str(10)),
        );
    }

    public function invalidBooleanDataProvider()
    {
        return array(
            array(array()),
            array(new \stdClass()),
            array('test'),
        );
    }
}