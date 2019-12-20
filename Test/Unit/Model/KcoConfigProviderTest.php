<?php
namespace Salle\Customer\Test\Unit\Model;
use Magento\Checkout\Model\Session;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Quote\Model\Quote\Address;
use PHPUnit\Framework\TestCase;
use Klarna\Kco\Model\KcoConfigProvider;
use Magento\Quote\Model\Quote;
use Salle\Customer\Api\providerInterface;
use Salle\Customer\Model\Customer;

class KcoConfigProviderTest extends TestCase
{

    protected function setUp(): void
    {

    }

    public function testgetUrl(): void
    {
        $objectManager = new ObjectManager($this);
        $addressMock = $this->createMock(Address::class);
        $addressMock->expects($this->exactly(2))->method('getShippingMethod')->willReturn("address");

        $quoteMock = $this->createMock(Quote::class);
        $quoteMock->expects($this->any())->method('getStore')->willReturn(1);
        $quoteMock->expects($this->any())->method('getShippingAddress')->with()->willReturn($addressMock);

        $sesssionMock= $this->createMock(Session::class);
        $sesssionMock->expects($this->any())->method('getQuote')->willReturn($quoteMock);

        $objectManager = new ObjectManager($this);
        $KcoConfigProviderArgs = $objectManager->getConstructArguments(KcoConfigProvider::class);

        $KcoConfigProviderArgs['checkoutSession'] = $sesssionMock;
        $KcoConfigProviderClass =  $objectManager->getObject(KcoConfigProvider::class, $KcoConfigProviderArgs);
        $KcoConfigProviderClass =  $objectManager->getObject(Customer::class);
        $KcoConfigProviderClass->getConfig();
    }
}
