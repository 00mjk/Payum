<?php

namespace Payum\Paypal\ProHosted\Nvp\Tests\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayInterface;
use Payum\Core\Request\Capture;
use Payum\Core\Request\GetHttpRequest;
use Payum\Core\Tests\GenericActionTest;
use Payum\Paypal\ProHosted\Nvp\Action\Api\CreateButtonPaymentAction;
use Payum\Paypal\ProHosted\Nvp\Action\CaptureAction;
use Payum\Paypal\ProHosted\Nvp\Api;
use Payum\Paypal\ProHosted\Nvp\Request\Api\CreateButtonPayment;

class CaptureActionTest extends GenericActionTest
{
    protected $requestClass = Capture::class;

    protected $actionClass = CaptureAction::class;

    public function testShouldImplementActionInterface()
    {
        $rc = new \ReflectionClass(CaptureAction::class);

        $this->assertTrue($rc->implementsInterface(ActionInterface::class));
    }

    public function testShouldImplementGatewayAwareInterface()
    {
        $rc = new \ReflectionClass(CaptureAction::class);

        $this->assertTrue($rc->implementsInterface(GatewayAwareInterface::class));
    }

    public function testShouldRequestApiCreateButtonPaymentMethodWithExpectedRequiredArguments()
    {
        $gatewayMock = $this->createGatewayMock();

        $gatewayMock->expects($this->atLeast(2))
            ->method('execute')
            ->withConsecutive(
                [$this->isInstanceOf(GetHttpRequest::class)],
                [$this->isInstanceOf(CreateButtonPayment::class)]
            )
        ;
        $action = new CaptureAction();
        $action->setGateway($gatewayMock);

        $action->execute(new Capture([
            'currency_code' => 'EUR',
            'subtotal' => 5,
        ]));
    }

    public function testThrowIfModelNotHavePaymentAmountOrCurrencySet()
    {
        $this->expectException(\Payum\Core\Exception\LogicException::class);
        $action = new CreateButtonPaymentAction();

        $request = new CreateButtonPayment(new \ArrayObject());

        $action->execute($request);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|Api
     */
    protected function createApiMock()
    {
        return $this->createMock(Api::class, [], [], '', false);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Payum\Core\GatewayInterface
     */
    protected function createGatewayMock()
    {
        return $this->createMock(GatewayInterface::class);
    }
}
