<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Sales\Test\Unit\Ui\Component\Listing\Column;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Payment\Helper\Data;
use Magento\Sales\Ui\Component\Listing\Column\PaymentMethod;

/**
 * Class PaymentMethodTest
 */
class PaymentMethodTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PaymentMethod
     */
    protected $model;

    /**
     * @var Data|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $paymentHelper;

    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->paymentHelper = $this->getMock('Magento\Payment\Helper\Data', [], [], '', false);
        $this->model = $objectManager->getObject(
            'Magento\Sales\Ui\Component\Listing\Column\PaymentMethod',
            ['paymentHelper' => $this->paymentHelper]
        );
    }

    public function testPrepareDataSource()
    {
        $itemName = 'itemName';
        $oldItemValue = 'oldItemValue';
        $newItemValue = 'newItemValue';
        $dataSource = [
            'data' => [
                'items' => [
                    [$itemName => $oldItemValue]
                ]
            ]
        ];

        $payment = $this->getMockForAbstractClass('Magento\Payment\Model\MethodInterface');
        $payment->expects($this->once())
            ->method('getTitle')
            ->willReturn($newItemValue);
        $this->paymentHelper->expects($this->once())
            ->method('getMethodInstance')
            ->with($oldItemValue)
            ->willReturn($payment);

        $this->model->setData('name', $itemName);
        $this->model->prepareDataSource($dataSource);
        $this->assertEquals($newItemValue, $dataSource['data']['items'][0][$itemName]);
    }
}
