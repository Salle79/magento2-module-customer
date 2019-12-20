<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Salle\Customer\Api;

/**
 * @api
 * @since 100.0.2
 */
interface providerInterface
{
     function getConfig();

     function checkUrl($value, $array): array;
}
