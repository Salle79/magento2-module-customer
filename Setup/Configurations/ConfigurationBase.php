<?php
namespace Salle\Customer\Setup\Configurations;
use Magento\Framework\Phrase;
use Magento\Config\Model\ResourceModel\Config;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Exception\ConfigurationMismatchException;
class ConfigurationBase {

    /**
     * Values for updating/adding core data in Magento.
     */
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

    public function __construct(Config $config = null, EncryptorInterface $encryptor = null) {
        $this->config = $config;
        $this->encryptor = $encryptor;
    }

    /**
     * @param string $filename
     * @param string $delimiter
     * @throws ConfigurationMismatchException
     * @throws FileSystemException
     */
    public function saveConfigCvs( string $filename='', string $delimiter=','): void
    {
        $this->saveConfigArray($this->file_csv_to_array($filename, $delimiter));
    }

    /**
     * Convert a comma separated file into an associated array.
     * The first row should contain the array keys.
     *
     * @param string $filename Path to the CSV file
     * @param string $delimiter The separator used in the file
     * @return array
     * @throws FileSystemException
     * @author Jay Williams <http://myd3.com/>
     * @copyright Copyright (c) 2010-20xx, Jay Williams
     * @license http://www.opensource.org/licenses/mit-license.php MIT License
     * @link http://gist.github.com/385876
     */
    protected function file_csv_to_array($filename, $delimiter): array
    {
        if (!file_exists($filename) || !is_readable($filename)){
            throw new FileSystemException(new Phrase(self::BAD_CSV_FILE_PATH));
        }
        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                if (!$header) {
                    $header = $row;
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
     * @param $dataArray
     * @throws ConfigurationMismatchException
     */
    protected function saveConfigArray($dataArray): void
    {
        foreach ($dataArray as $dataRow) {
            $this->saveConfigRow($dataRow);
        }
    }

    /**
     * @param $dataRow
     * @throws ConfigurationMismatchException
     */
    protected function saveConfigRow($dataRow): void
    {
        try {
            $pairConfigPath = $dataRow[self::CORE_CONFIG_PATH];
            $pairValue = $dataRow[self::CORE_CONFIG_VALUE];
            $requiresEncryptor = (bool)$dataRow[self::REQUIRES_ENCRYPTOR];
        }
        catch (\Exception $e) {
            throw new ConfigurationMismatchException(new Phrase(self::BAD_CSV_FILE_CONTENT));
        }
        if ($requiresEncryptor) {
            $pairValue = $this->encryptor($dataRow[self::CORE_CONFIG_VALUE]);
        }
        $this->config->saveConfig($pairConfigPath, $pairValue);
    }
}

