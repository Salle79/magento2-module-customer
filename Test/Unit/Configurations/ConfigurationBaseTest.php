<?php
namespace Salle\Customer\Test\Unit\Setup\Configurations;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\Exception\ConfigurationMismatchException;
use Salle\Customer\Setup\Configurations\ConfigurationBase;
use PHPUnit\Framework\TestCase;

class ConfigurationBaseTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
    }

    public function testSetup()
    {
        $filename = '/home/salle/workspace/magento/app/code/Salle/Customer/Setup/Configurations/klarna_settingss.csv';
        $test = $this->objectManager->getObject(ConfigurationBase::class);
        $this->expectException(ConfigurationMismatchException::class);
        $this->expectExceptionMessage(ConfigurationBase::BAD_CSV_FILE_CONTENT);
        $test->saveConfigCvs($filename);
    }
}