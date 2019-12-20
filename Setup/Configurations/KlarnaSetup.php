<?php
namespace Salle\Customer\Setup\Configurations;
use Magento\Config\Model\ResourceModel\Config;
use Magento\Framework\Encryption\EncryptorInterface;

class KlarnaSetup
{

    public function __construct(Config $config, EncryptorInterface $encryptor) {
        $this->config = $config;
        $this->encryptor = $encryptor;
    }

    public function setup() : void
    {
        $this->config->saveConfig(
            'payment/klarna_kco/active',
            1);
        $this->config->saveConfig(
            'klarna/api/api_version',
            'uk');
        $this->config->saveConfig(
            'klarna/api/merchant_id',
            'PK07797_c2688244bd22');
        $this->config->saveConfig(
            'klarna/api/shared_secret',
            $this->encryptor->encrypt('0MMdd7PQHs105acm'));
        $this->config->saveConfig(
            'klarna/api/test_mode',
            1);
        $this->config->saveConfig(
            'klarna/api/debug',
            1);
        $this->config->saveConfig(
            'general/store_information/country_id',
            'SE');
        $this->config->saveConfig(
            'checkout/klarna_kco_design/color_button',
            '#000033');
        $this->config->saveConfig(
            'checkout/klarna_kco_design/color_button_text',
            '#ffffff');
        $this->config->saveConfig(
            'checkout/klarna_kco_design/color_checkbox',
            '#fc6621');
        $this->config->saveConfig(
            'checkout/klarna_kco_design/color_checkbox_checkmark',
            '#fc6621');
        $this->config->saveConfig(
            'checkout/klarna_kco_design/color_header',
            '#212121');
        $this->config->saveConfig(
            'checkout/klarna_kco_design/color_link',
            '#212121');
    }

}