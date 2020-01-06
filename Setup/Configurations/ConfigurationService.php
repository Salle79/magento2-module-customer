<?php
namespace Salle\Customer\Setup\Configurations;

use Magento\Config\Model\ResourceModel\Config;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Exception\ConfigurationMismatchException;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Phrase;

class ConfigurationService
{
    /**
     * Repeated values for updating/adding core data in Magento being used in
     * ConfigurationService or other classes utilizing it.
     */
    const PATH_TO__FILES_FOLDER = __DIR__ . '/_files';
    const CORE_CONFIG_PATH = 'core_config_path';
    const CORE_CONFIG_VALUE = 'core_config_value';
    const REQUIRES_ENCRYPTOR = 'requires_encryptor';
    const BAD_CSV_FILE_PATH = 'CSV file does not exist:';
    const BAD_CSV_FILE_CONTENT = 'Identifiers in CSV file is not set, please check your data file';

    /**
     * @var Config
     */
    private $config;

    /**
     * @var EncryptorInterface
     */
    private $encryptor;

    public function __construct(Config $config, EncryptorInterface $encryptor)
    {
        $this->config = $config;
        $this->encryptor = $encryptor;
    }

    /**
     * @param string $filename
     * @param string $delimiter
     * @throws ConfigurationMismatchException
     * @throws FileSystemException
     */
    public function saveConfigCvs(string $filename, string $delimiter=','): void
    {
        $this->saveConfigArray($this->file_csv_to_array($filename, $delimiter));
    }

    /**
     * @param $dataArray
     */
    protected function saveConfigArray(array $dataArray): void
    {
        foreach ($dataArray as $dataRow) {
            $pairConfigPath = (string) $dataRow[self::CORE_CONFIG_PATH];
            $pairValue = (string) $dataRow[self::CORE_CONFIG_VALUE];
            $requiresEncryptor = (string) $dataRow[self::REQUIRES_ENCRYPTOR];

            if ($requiresEncryptor) {
                $pairValue = (string) $this->encryptor->encrypt($dataRow[self::CORE_CONFIG_VALUE]);
            }
            $this->config->saveConfig($pairConfigPath, $pairValue);
        }
    }

    /**
     * Convert a comma separated file into an associated array.
     * The first row should contain the array keys.
     *
     * @param string $filename Path to the CSV file
     * @param string $delimiter The separator used in the file
     * @return array
     * @throws FileSystemException
     * @throws ConfigurationMismatchException
     * @author Jay Williams <http://myd3.com/>
     * @copyright Copyright (c) 2010-20xx, Jay Williams
     * @license http://www.opensource.org/licenses/mit-license.php MIT License
     * @link http://gist.github.com/385876
     */
    protected function file_csv_to_array(string $filename, string $delimiter): array
    {
        $this->verifyFile($filename);
        $header =  (array) null;
        $data = (array) [];
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                if (!$header) {
                    $header = $row;
                    $this->verifyHeader($header);
                } else {
                    if (count($header) != count($row)) {
                        continue;
                    }
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }
        return $data;
    }

    /**
     * @param array $header
     * @throws ConfigurationMismatchException
     */
    protected function verifyHeader(array $header): void
    {
        try {
            if (!($header[0] == self::CORE_CONFIG_PATH && $header[1] == self::CORE_CONFIG_VALUE && $header[2] == self::REQUIRES_ENCRYPTOR)) {
                throw new ConfigurationMismatchException(new Phrase(self::BAD_CSV_FILE_CONTENT));
            }
        } catch (Exception $e) {
            //do stuff here like logging
        }
    }

    /**
     * @param String $filename
     * @throws FileSystemException
     */
    protected function verifyFile(String $filename): void
    {
        try {
            if (!file_exists($filename) || !is_readable($filename)) {
                throw new FileSystemException(new Phrase(self::BAD_CSV_FILE_PATH));
            }
        } catch (Exception $e) {
            //do stuff here like logging
        }
    }
}
