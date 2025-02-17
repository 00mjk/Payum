<?php

namespace Payum\Klarna\Invoice\Action\Api;

use ArrayAccess;
use KlarnaException;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayInterface;
use Payum\Klarna\Invoice\Request\Api\CreditPart;
use Payum\Klarna\Invoice\Request\Api\PopulateKlarnaFromDetails;

class CreditPartAction extends BaseApiAwareAction implements GatewayAwareInterface
{
    /**
     * @var GatewayInterface
     */
    protected $gateway;

    public function setGateway(GatewayInterface $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * @param CreditPart $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $details = ArrayObject::ensureArrayObject($request->getModel());

        $details->validateNotEmpty(['invoice_number']);

        $klarna = $this->getKlarna();

        try {
            $this->gateway->execute(new PopulateKlarnaFromDetails($details, $klarna));

            $details['refund_invoice_number'] = $klarna->creditPart($details['invoice_number']);
        } catch (KlarnaException $e) {
            $this->populateDetailsWithError($details, $e, $request);
        }
    }

    public function supports($request)
    {
        return $request instanceof CreditPart &&
            $request->getModel() instanceof ArrayAccess
        ;
    }
}
