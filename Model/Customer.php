<?php
namespace Salle\Customer\Model;
use Klarna\Kco\Model\Checkout\Kco\Shipping;
use Klarna\Kco\Model\Checkout\Url;
use Klarna\Kco\Model\Provider\Base\Config;
use Klarna\Base\Helper\KlarnaConfig;
use Magento\Checkout\Model\Session;
use Magento\Framework\UrlInterface;
use Salle\Customer\Api\providerInterface;

class Customer extends \Klarna\Kco\Model\KcoConfigProvider
{

    private $provider;

    public function __construct(
        Url $url,
        Config $config,
        KlarnaConfig $klarnaConfig,
        UrlInterface $urlBuilder,
        Session $checkoutSession,
        Shipping $kcoShipping,
        providerInterface $provider)
    {
        parent::__construct($url,$config, $klarnaConfig, $urlBuilder, $checkoutSession, $kcoShipping);
        $this->provider = $provider;
    }

    public function getConfig()
    {
        $value = parent::getConfig();
        $this->provider->getConfig();
        $value['klarna'] = $this->checkUrl($value['klarna'], ['saveUrl','reloadUrl','addressUrl','methodUrl','regionUrl','countryUrl', 'refreshAddressUrl']);
        return $value;
    }

    public function checkUrl($value, $array): array
    {
        foreach ($array as $current){
            //Do something
            $value[$current] = $value[$current] . "hello";
        }
        return $value;
    }
}