<?php

namespace Salle\Customer\Setup;

use Magento\Framework\Exception\ConfigurationMismatchException;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Psr\Log\LoggerInterface;
use Salle\Customer\Setup\Configurations\KlarnaSetup;

/**
 * Ateles Max Base Common Settings and Data Installer (CSDI) for settings and data that either does not require its own module(s) and/or
 * that does not fit in anywhere else.sdfdf
 */
class InstallData implements InstallDataInterface
{
    /**
     *
     * @var LoggerInterface
     */
    private $logger;
    /**
     *
     * @var ThemeSetup
     */
    private $themeSetup;
    /**
     * @var WidgetSetup
     */
    private $widgetSetup;
    /**
     * @var ConfigurationService
     */
    private $configuratorService;
    /**
     * @var KlarnaSetup
     */
    private $klarnaSetup;

    /**
     * InstallData constructor.
     * @param LoggerInterface $logger
     * @param ConfigurationService $configuratorService
     */
    public function __construct(
        LoggerInterface $logger,
        KlarnaSetup $klarnaSetup
    ) {
        $this->logger = $logger;
        $this->klarnaSetup = $klarnaSetup;

    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context): void
    {
        $this->logger->info("InstallData: Start");
        $setup->startSetup();
        $this->runSetup();
        $setup->endSetup();
        $this->logger->info("InstallData: End");
    }

    /**
     * Main function to run related setup functions when doing a complete reinstall
     * @throws ConfigurationMismatchException
     */
    private function runSetup(): void
    {
        $this->logger->info('Enabling Klarna');
        $this->klarnaSetup->setup();
    }
}
