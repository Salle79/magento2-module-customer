<?php
namespace Salle\Customer\Model;
use Salle\Customer\Api\providerInterface;

class Provider implements providerInterface
{

    public function __construct() {}

    public function getConfig()
    {

      return "hello";
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