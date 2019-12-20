<?php
namespace Salle\Customer\Test\Unit\Setup\Configurations;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Salle\Customer\Setup\Configurations\KlarnaSetup;
use Magento\Config\Model\ResourceModel\Config;
use Magento\Framework\Encryption\EncryptorInterface;
use PHPUnit\Framework\TestCase;

class KlarnaSetupTest extends TestCase
{
    private $mockConfig;
    private $mockEncrypt;

    private $klarnaSetup;

    public function setUp() : void
    {
        $objectManager = new ObjectManager($this);
        $args = $objectManager->getConstructArguments(KlarnaSetup::class);

        $this->mockConfig = $this->createMock(Config::class);
        $this->mockEncrypt = $this->createMock(EncryptorInterface::class);

        $this->klarnaSetup = new KlarnaSetup(
            $this->mockConfig,
            $this->mockEncrypt
        );
    }

    public function testSetup()
    {
        $objectManager = new ObjectManager($this);
        $args = $objectManager->getConstructArguments(KlarnaSetup::class);


        $this->mockEncrypt
            ->expects($this->once())
            ->method('encrypt')
            ->willReturn('encrypted');

        $this->mockConfig
            ->expects($this->exactly(13))
            ->method('saveConfig');

        $this->mockConfig
            ->expects($this->at(0))
            ->method('saveConfig')
            ->with('payment/klarna_kco/active', 1);

        $this->mockConfig
            ->expects($this->at(1))
            ->method('saveConfig')
            ->with('klarna/api/api_version', 'uk');

        $this->mockConfig
            ->expects($this->at(2))
            ->method('saveConfig')
            ->with('klarna/api/merchant_id', 'PK07797_c2688244bd22');

        $this->mockConfig
            ->expects($this->at(3))
            ->method('saveConfig')
            ->with('klarna/api/shared_secret', 'encrypted');

        $this->mockConfig
            ->expects($this->at(4))
            ->method('saveConfig')
            ->with('klarna/api/test_mode', 1);

        $this->mockConfig
            ->expects($this->at(5))
            ->method('saveConfig')
            ->with('klarna/api/debug', 1);

        $this->mockConfig
            ->expects($this->at(6))
            ->method('saveConfig')
            ->with('general/store_information/country_id', 'SE');

        $this->mockConfig
            ->expects($this->at(7))
            ->method('saveConfig')
            ->with('checkout/klarna_kco_design/color_button', '#000033');

        $this->mockConfig
            ->expects($this->at(8))
            ->method('saveConfig')
            ->with('checkout/klarna_kco_design/color_button_text', '#ffffff');

        $this->mockConfig
            ->expects($this->at(9))
            ->method('saveConfig')
            ->with('checkout/klarna_kco_design/color_checkbox', '#fc6621');

        $this->mockConfig
            ->expects($this->at(10))
            ->method('saveConfig')
            ->with('checkout/klarna_kco_design/color_checkbox_checkmark', '#fc6621');

        $this->mockConfig
            ->expects($this->at(11))
            ->method('saveConfig')
            ->with('checkout/klarna_kco_design/color_header', '#212121');

        $this->mockConfig
            ->expects($this->at(12))
            ->method('saveConfig')
            ->with('checkout/klarna_kco_design/color_link', '#212121');

        $this->klarnaSetup->setup();
    }
}