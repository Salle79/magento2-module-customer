<?php

namespace Ateles\Installer\Setup;

use Ateles\Installer\Setup\Configurations\KlarnaSetup;
use Ateles\Installer\Setup\Configurations\ThemeSetup;
use Ateles\Installer\Setup\Configurations\WidgetSetup;
use Magento\Framework\Exception\ConfigurationMismatchException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\InstallDataInterface;
use Psr\Log\LoggerInterface;

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
     * @var KlarnaSetup
     */
    private $klarnaSetup;

    /**
     * InstallData constructor.
     * @param LoggerInterface $logger
     * @param ThemeSetup $themeSetup
     * @param KlarnaSetup $klarnaSetup
     */
    public function __construct(
        LoggerInterface $logger,
        ThemeSetup $themeSetup,
        WidgetSetup $widgetSetup,
        KlarnaSetup $klarnaSetup)
    {
        $this->logger = $logger;
        $this->themeSetup = $themeSetup;
        $this->widgetSetup = $widgetSetup;
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
        $this->logger->info('Setting Ateles theme');
        $this->themeSetup->switchToThemeAteles();

        $this->logger->info('Creating widgets');
        $this->widgetSetup->setup();

        $this->logger->info('Enabling Klarna');
        $this->klarnaSetup->setup();
    }
}