<?php

/**
 * @deprecated  This script exists for legacy purposes only and will be removed in a future release.
 */

declare(strict_types=1);

namespace SimpleSAML;

require_once('../../_include.php');

use SimpleSAML\Configuration;
use SimpleSAML\Module\saml\Controller;

$config = Configuration::getInstance();
$controller = new Controller\WebBrowserSingleSignOn($config);
$controller->singleSignOnService()->send();
