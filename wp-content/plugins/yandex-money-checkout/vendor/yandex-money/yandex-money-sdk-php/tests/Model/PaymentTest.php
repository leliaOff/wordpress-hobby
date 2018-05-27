<?php

namespace Tests\YaMoney\Model;

use PHPUnit\Framework\TestCase;
use YaMoney\Helpers\Random;
use YaMoney\Model\Confirmation\ConfirmationRedirect;
use YaMoney\Model\Metadata;
use YaMoney\Model\MonetaryAmount;
use YaMoney\Model\Payment;
use YaMoney\Model\PaymentError;
use YaMoney\Model\PaymentMethod\PaymentMethodQiwi;
use YaMoney\Model\ReceiptRegistrationStatus;
use YaMoney\Model\Recipient;
use YaMoney\Model\Status;

class PaymentTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetSetId($options)
    {
        $instance = new Payment();

        self::assertNull($instance->getId());
        self::assertNull($instance->id);

        $instance->setId($options['id']);
        self::assertEquals($options['id'], $instance->getId());
        self::assertEquals($options['id'], $instance->id);

        $instance = new Payment();
        $instance->id = $options['id'];
        self::assertEquals($options['id'], $instance->getId());
        self::assertEquals($options['id'], $instance->id);
    }

    /**
     * @dataProvider invalidDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetInvalidId($value)
    {
        $instance = new Payment();
        $instance->setId($value['id']);
    }

    /**
     * @dataProvider invalidDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetterInvalidId($value)
    {
        $instance = new Payment();
        $instance->id = $value['id'];
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetSetStatus($options)
    {
        $instance = new Payment();

        self::assertNull($instance->getStatus());
        self::assertNull($instance->status);

        $instance->setStatus($options['status']);
        self::assertEquals($options['status'], $instance->getStatus());
        self::assertEquals($options['status'], $instance->status);

        $instance = new Payment();
        $instance->status = $options['status'];
        self::assertEquals($options['status'], $instance->getStatus());
        self::assertEquals($options['status'], $instance->status);
    }

    /**
     * @dataProvider invalidDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetInvalidStatus($value)
    {
        $instance = new Payment();
        $instance->setStatus($value['status']);
    }

    /**
     * @dataProvider invalidDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetterInvalidStatus($value)
    {
        $instance = new Payment();
        $instance->status = $value['status'];
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetSetError($options)
    {
        $instance = new Payment();

        self::assertNull($instance->getError());
        self::assertNull($instance->error);

        $instance->setError($options['error']);
        self::assertSame($options['error'], $instance->getError());
        self::assertSame($options['error'], $instance->error);

        $instance = new Payment();
        $instance->error = $options['error'];
        self::assertSame($options['error'], $instance->getError());
        self::assertSame($options['error'], $instance->error);
    }

    /**
     * @dataProvider invalidDataProvider
     * @param $value
     */
    public function testSetInvalidError($value)
    {
        if (class_exists('TypeError')) {
            self::setExpectedException('TypeError');
            $instance = new Payment();
            $instance->setError($value['error']);
        }
    }

    /**
     * @dataProvider invalidDataProvider
     * @param $value
     */
    public function testSetterInvalidError($value)
    {
        if (class_exists('TypeError')) {
            self::setExpectedException('TypeError');
            $instance = new Payment();
            $instance->error = $value['error'];
        }
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetSetRecipient($options)
    {
        $instance = new Payment();

        self::assertNull($instance->getRecipient());
        self::assertNull($instance->recipient);

        $instance->setRecipient($options['recipient']);
        self::assertSame($options['recipient'], $instance->getRecipient());
        self::assertSame($options['recipient'], $instance->recipient);

        $instance = new Payment();
        $instance->recipient = $options['recipient'];
        self::assertSame($options['recipient'], $instance->getRecipient());
        self::assertSame($options['recipient'], $instance->recipient);
    }

    /**
     * @dataProvider invalidDataProvider
     * @param $value
     */
    public function testSetInvalidRecipient($value)
    {
        if (class_exists('TypeError')) {
            self::setExpectedException('TypeError');
            $instance = new Payment();
            $instance->setRecipient($value['recipient']);
        }
    }

    /**
     * @dataProvider invalidDataProvider
     * @param $value
     */
    public function testSetterInvalidRecipient($value)
    {
        if (class_exists('TypeError')) {
            self::setExpectedException('TypeError');
            $instance = new Payment();
            $instance->recipient = $value['recipient'];
        }
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetSetAmount($options)
    {
        $instance = new Payment();

        self::assertNull($instance->getAmount());
        self::assertNull($instance->amount);

        $instance->setAmount($options['amount']);
        self::assertSame($options['amount'], $instance->getAmount());
        self::assertSame($options['amount'], $instance->amount);

        $instance = new Payment();
        $instance->amount = $options['amount'];
        self::assertSame($options['amount'], $instance->getAmount());
        self::assertSame($options['amount'], $instance->amount);
    }

    /**
     * @dataProvider invalidDataProvider
     * @param $value
     */
    public function testSetInvalidAmount($value)
    {
        if (class_exists('TypeError')) {
            self::setExpectedException('TypeError');
            $instance = new Payment();
            $instance->setAmount($value['amount']);
        }
    }

    /**
     * @dataProvider invalidDataProvider
     * @param $value
     */
    public function testSetterInvalidAmount($value)
    {
        if (class_exists('TypeError')) {
            self::setExpectedException('TypeError');
            $instance = new Payment();
            $instance->amount = $value['amount'];
        }
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetSetPaymentMethod($options)
    {
        $instance = new Payment();

        self::assertNull($instance->getPaymentMethod());
        self::assertNull($instance->paymentMethod);

        $instance->setPaymentMethod($options['payment_method']);
        self::assertSame($options['payment_method'], $instance->getPaymentMethod());
        self::assertSame($options['payment_method'], $instance->paymentMethod);

        $instance = new Payment();
        $instance->paymentMethod = $options['payment_method'];
        self::assertSame($options['payment_method'], $instance->getPaymentMethod());
        self::assertSame($options['payment_method'], $instance->paymentMethod);
    }

    /**
     * @dataProvider invalidDataProvider
     * @param $value
     */
    public function testSetInvalidPaymentMethod($value)
    {
        if (class_exists('TypeError')) {
            self::setExpectedException('TypeError');
            $instance = new Payment();
            $instance->setPaymentMethod($value['payment_method']);
        }
    }

    /**
     * @dataProvider invalidDataProvider
     * @param $value
     */
    public function testSetterInvalidPaymentMethod($value)
    {
        if (class_exists('TypeError')) {
            self::setExpectedException('TypeError');
            $instance = new Payment();
            $instance->paymentMethod = $value['payment_method'];
        }
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetSetCreatedAt($options)
    {
        $instance = new Payment();

        self::assertNull($instance->getCreatedAt());
        self::assertNull($instance->createdAt);

        $instance->setCreatedAt($options['created_at']);
        self::assertSame($options['created_at'], $instance->getCreatedAt()->format(DATE_ATOM));
        self::assertSame($options['created_at'], $instance->createdAt->format(DATE_ATOM));

        $instance = new Payment();
        $instance->createdAt = $options['created_at'];
        self::assertSame($options['created_at'], $instance->getCreatedAt()->format(DATE_ATOM));
        self::assertSame($options['created_at'], $instance->createdAt->format(DATE_ATOM));
    }

    /**
     * @dataProvider invalidDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetInvalidCreatedAt($value)
    {
        $instance = new Payment();
        $instance->setCreatedAt($value['created_at']);
    }

    /**
     * @dataProvider invalidDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetterInvalidCreatedAt($value)
    {
        $instance = new Payment();
        $instance->createdAt = $value['created_at'];
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetSetCapturedAt($options)
    {
        $instance = new Payment();

        self::assertNull($instance->getCapturedAt());
        self::assertNull($instance->capturedAt);

        $instance->setCapturedAt($options['captured_at']);
        if ($options['captured_at'] === null || $options['captured_at'] === '') {
            self::assertNull($instance->getCapturedAt());
            self::assertNull($instance->capturedAt);
        } else {
            self::assertSame($options['captured_at'], $instance->getCapturedAt()->format(DATE_ATOM));
            self::assertSame($options['captured_at'], $instance->capturedAt->format(DATE_ATOM));
        }

        $instance = new Payment();
        $instance->capturedAt = $options['captured_at'];
        if ($options['captured_at'] === null || $options['captured_at'] === '') {
            self::assertNull($instance->getCapturedAt());
            self::assertNull($instance->capturedAt);
        } else {
            self::assertSame($options['captured_at'], $instance->getCapturedAt()->format(DATE_ATOM));
            self::assertSame($options['captured_at'], $instance->capturedAt->format(DATE_ATOM));
        }
    }

    /**
     * @dataProvider invalidDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetInvalidCapturedAt($value)
    {
        $instance = new Payment();
        $instance->setCapturedAt($value['captured_at']);
    }

    /**
     * @dataProvider invalidDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetterInvalidCapturedAt($value)
    {
        $instance = new Payment();
        $instance->capturedAt = $value['captured_at'];
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetSetConfirmation($options)
    {
        $instance = new Payment();

        self::assertNull($instance->getConfirmation());
        self::assertNull($instance->confirmation);

        $instance->setConfirmation($options['confirmation']);
        self::assertSame($options['confirmation'], $instance->getConfirmation());
        self::assertSame($options['confirmation'], $instance->confirmation);

        $instance = new Payment();
        $instance->confirmation = $options['confirmation'];
        self::assertSame($options['confirmation'], $instance->getConfirmation());
        self::assertSame($options['confirmation'], $instance->confirmation);
    }

    /**
     * @dataProvider invalidDataProvider
     * @param $value
     */
    public function testSetInvalidConfirmation($value)
    {
        if (class_exists('TypeError')) {
            self::setExpectedException('TypeError');
            $instance = new Payment();
            $instance->setConfirmation($value['confirmation']);
        }
    }

    /**
     * @dataProvider invalidDataProvider
     * @param $value
     */
    public function testSetterInvalidConfirmation($value)
    {
        if (class_exists('TypeError')) {
            self::setExpectedException('TypeError');
            $instance = new Payment();
            $instance->confirmation = $value['confirmation'];
        }
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetSetRefundedAmount($options)
    {
        $instance = new Payment();

        self::assertNull($instance->getRefundedAmount());
        self::assertNull($instance->refundedAmount);

        $instance->setRefundedAmount($options['refunded_amount']);
        self::assertSame($options['refunded_amount'], $instance->getRefundedAmount());
        self::assertSame($options['refunded_amount'], $instance->refundedAmount);

        $instance = new Payment();
        $instance->refundedAmount = $options['refunded_amount'];
        self::assertSame($options['refunded_amount'], $instance->getRefundedAmount());
        self::assertSame($options['refunded_amount'], $instance->refundedAmount);
    }

    /**
     * @dataProvider invalidDataProvider
     * @param $value
     */
    public function testSetInvalidRefundedAmount($value)
    {
        if (class_exists('TypeError')) {
            self::setExpectedException('TypeError');
            $instance = new Payment();
            $instance->setRefundedAmount($value['refunded_amount']);
        }
    }

    /**
     * @dataProvider invalidDataProvider
     * @param $value
     */
    public function testSetterInvalidRefundedAmount($value)
    {
        if (class_exists('TypeError')) {
            self::setExpectedException('TypeError');
            $instance = new Payment();
            $instance->refundedAmount = $value['refunded_amount'];
        }
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetSetPaid($options)
    {
        $instance = new Payment();

        self::assertNull($instance->getPaid());
        self::assertNull($instance->paid);

        $instance->setPaid($options['paid']);
        self::assertSame($options['paid'], $instance->getPaid());
        self::assertSame($options['paid'], $instance->paid);

        $instance = new Payment();
        $instance->paid = $options['paid'];
        self::assertSame($options['paid'], $instance->getPaid());
        self::assertSame($options['paid'], $instance->paid);
    }

    /**
     * @dataProvider invalidDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetInvalidPaid($value)
    {
        $instance = new Payment();
        $instance->setPaid($value['paid']);
    }

    /**
     * @dataProvider invalidDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetterInvalidPaid($value)
    {
        $instance = new Payment();
        $instance->paid = $value['paid'];
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetSetReceiptRegistration($options)
    {
        $instance = new Payment();

        self::assertNull($instance->getReceiptRegistration());
        self::assertNull($instance->receiptRegistration);

        $instance->setReceiptRegistration($options['receipt_registration']);
        if ($options['receipt_registration'] === null || $options['receipt_registration'] === '') {
            self::assertNull($instance->getReceiptRegistration());
            self::assertNull($instance->receiptRegistration);
        } else {
            self::assertSame($options['receipt_registration'], $instance->getReceiptRegistration());
            self::assertSame($options['receipt_registration'], $instance->receiptRegistration);
        }


        $instance = new Payment();
        $instance->receiptRegistration = $options['receipt_registration'];
        if ($options['receipt_registration'] === null || $options['receipt_registration'] === '') {
            self::assertNull($instance->getReceiptRegistration());
            self::assertNull($instance->receiptRegistration);
        } else {
            self::assertSame($options['receipt_registration'], $instance->getReceiptRegistration());
            self::assertSame($options['receipt_registration'], $instance->receiptRegistration);
        }
    }

    /**
     * @dataProvider invalidDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetInvalidReceiptRegistration($value)
    {
        $instance = new Payment();
        $instance->setReceiptRegistration($value['receipt_registration']);
    }

    /**
     * @dataProvider invalidDataProvider
     * @expectedException \InvalidArgumentException
     * @param $value
     */
    public function testSetterInvalidReceiptRegistration($value)
    {
        $instance = new Payment();
        $instance->receiptRegistration = $value['receipt_registration'];
    }

    /**
     * @dataProvider validDataProvider
     * @param array $options
     */
    public function testGetSetMetadata($options)
    {
        $instance = new Payment();

        self::assertNull($instance->getMetadata());
        self::assertNull($instance->metadata);

        $instance->setMetadata($options['metadata']);
        self::assertSame($options['metadata'], $instance->getMetadata());
        self::assertSame($options['metadata'], $instance->metadata);

        $instance = new Payment();
        $instance->metadata = $options['metadata'];
        self::assertSame($options['metadata'], $instance->getMetadata());
        self::assertSame($options['metadata'], $instance->metadata);
    }

    public function validDataProvider()
    {
        $result = array();
        for ($i = 0; $i < 10; $i++) {
            $payment = array(
                'id' => Random::str(36),
                'status' => Random::value(Status::getValidValues()),
                'error' => new PaymentError(),
                'recipient' => new Recipient(),
                'amount' => new MonetaryAmount(Random::int(1, 10000), 'RUB'),
                'payment_method' => new PaymentMethodQiwi(),
                'reference_id' => ($i == 0 ? null :  ($i == 1 ? '' : Random::str(10, 20, 'abcdef0123456789'))),
                'created_at' => date(DATE_ATOM, mt_rand(1, time())),
                'captured_at' => ($i == 0 ? null : ($i == 1 ? '' : date(DATE_ATOM, mt_rand(1, time())))),
                'confirmation' => new ConfirmationRedirect(),
                'charge' => new MonetaryAmount(),
                'income' => new MonetaryAmount(),
                'refunded_amount' => new MonetaryAmount(),
                'paid' => $i % 2 ? true : false,
                'receipt_registration' => $i == 0 ? null : ($i == 1 ? '' : Random::value(ReceiptRegistrationStatus::getValidValues())),
                'metadata' => new Metadata(),
            );
            $result[] = array($payment);
        }
        return $result;
    }

    public function invalidDataProvider()
    {
        $result = array(
            array(
                array(
                    'id' => null,
                    'status' => null,
                    'error' => null,
                    'recipient' => null,
                    'amount' => null,
                    'payment_method' => null,
                    'reference_id' => Random::str(65),
                    'confirmation' => null,
                    'charge' => null,
                    'income' => null,
                    'refunded_amount' => null,
                    'paid' => null,
                    'created_at' => null,
                    'captured_at' => array(),
                    'receipt_registration' => array(),
                )
            ),
            array(
                array(
                    'id' => '',
                    'status' => '',
                    'error' => '',
                    'recipient' => '',
                    'amount' => '',
                    'payment_method' => '',
                    'reference_id' => array(),
                    'confirmation' => '',
                    'charge' => '',
                    'income' => '',
                    'refunded_amount' => '',
                    'paid' => '',
                    'created_at' => array(),
                    'captured_at' => '23423-234-234',
                    'receipt_registration' => new \stdClass(),
                ),
            ),
        );
        for ($i = 0; $i < 10; $i++) {
            $payment = array(
                'id' => Random::str($i < 5 ? mt_rand(1, 35) : mt_rand(37, 64)),
                'status' => Random::str(1, 35),
                'error' => 'test',
                'recipient' => 'test',
                'amount' => 'test',
                'payment_method' => 'test',
                'reference_id' => Random::str(66, 128),
                'confirmation' => 'test',
                'charge' => 'test',
                'income' => 'test',
                'refunded_amount' => 'test',
                'paid' => $i == 0 ? array() : new \stdClass(),
                'created_at' => $i == 0 ? '23423-234-32' : -Random::int(),
                'captured_at' => -Random::int(),
                'receipt_registration' => $i == 0 ? true : Random::str(5),
            );
            $result[] = array($payment);
        }
        return $result;
    }
}