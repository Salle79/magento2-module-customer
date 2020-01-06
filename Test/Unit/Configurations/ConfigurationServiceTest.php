<?php
namespace Salle\Customer\Test\Unit\Setup\Configurations;

use Magento\Config\Model\ResourceModel\Config;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Exception\ConfigurationMismatchException;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Salle\Customer\Setup\Configurations\ConfigurationService;

class ConfigurationServiceTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;
    /**
     * @var String
     */
    private $filepath;
    /**
     * @var ConfigurationService
     */
    private $configurationService;
    /**
     * @var array
     */
    private $configurationServiceArgs;
    /**
     * @var Config
     */
    private $configMock;
    /**
     * @var EncryptorInterface
     */
    private $encryptorMock;

    public function setUp()
    {
        $this->objectManager = new ObjectManager($this);
        $this->filepath = (string) __DIR__ . '/_files/';
        $this->configMock = (object) $this->createMock(Config::class);
        $this->encryptorMock = (object) $this->createMock(EncryptorInterface::class);
        $this->configurationServiceArgs = (array) ['config' => $this->configMock,'encryptor'=> $this->encryptorMock ];
        $this->configurationService = (object) $this->objectManager->getObject(ConfigurationService::class, $this->configurationServiceArgs);
    }

    public function testSaveConfigCsv()
    {
        $this->setExpectedConsecutiveCalls(3, 3);
        $this->configurationService->saveConfigCvs($this->filepath . 'workingSettingsFile.csv');
    }

    public function testSaveConfigCsvWithoutEncryption()
    {
        $this->setExpectedConsecutiveCalls(0, 1);
        $this->configurationService->saveConfigCvs($this->filepath . 'NoCryptoSettingsFile.csv');
    }

    public function testSaveConfigEmptyCvs()
    {
        $this->setExpectedConsecutiveCalls(0, 0);
        $this->configurationService->saveConfigCvs($this->filepath . 'emptySettingsFile.csv');
    }

    public function testSaveConfigCvsExceptionConfigurationMismatch()
    {
        $this->setExceptionConditions(ConfigurationMismatchException::class, ConfigurationService::BAD_CSV_FILE_CONTENT);
        $this->configurationService->saveConfigCvs($this->filepath . 'badFormatedSettingsFile.csv');
    }

    public function testSaveConfigCvsExceptionFileSystemException()
    {
        $this->setExceptionConditions(FileSystemException::class, ConfigurationService::BAD_CSV_FILE_PATH);
        $this->configurationService->saveConfigCvs($this->filepath . 'fileDoesNotExist.csv');
    }

    /**
     * @param String $exceptionClass
     * @param String $exceptionMessage
     */
    private function setExceptionConditions(String $exceptionClass, String $exceptionMessage): void
    {
        $this->expectException($exceptionClass);
        $this->expectExceptionMessage($exceptionMessage);
    }

    /**
     * @param Int $encryptCalls
     * @param Int $configCalls
     */
    private function setExpectedConsecutiveCalls(Int $encryptCalls, Int $configCalls): void
    {
        $this->encryptorMock->expects($this->exactly($encryptCalls))
            ->method('encrypt')
            ->with()
            ->willReturnArgument(0);

        $this->configMock->expects($this->exactly($configCalls))
            ->method('saveConfig')
            ->withConsecutive(
                [
                    $this->stringContains('core_config_path_1'),
                    $this->stringContains('core_config_value_1')
                ],
                [
                    $this->stringContains('core_config_path_2'),
                    $this->stringContains('core_config_value_2')
                ],
                [
                    $this->stringContains('core_config_path_3'),
                    $this->stringContains('core_config_value_3')
                ]
            )
            ->willReturn($this->isEmpty());
    }
}
