<?php
namespace Salle\Customer\Setup\Configurations;

class KlarnaSetup
{
    /**
     * @var ConfigurationService
     */
    private $configurationService;

    public function __construct(ConfigurationService $configurationService)
    {
        $this->configurationService = $configurationService;
    }

    public function setup() : void
    {
        $filepath = $this->configurationService::PATH_TO__FILES_FOLDER . '/klarna_settings.csv';
        $this->configurationService->saveConfigCvs($filepath);
    }
}
