<?php
/*
* 2017 app
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable tohttp://35.176.254.4:8000
* obtain it through the world-wide-web, please send an email
* to license@app.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade app to newer
* versions in the future. If you wish to customize app for your
* needs please refer to http://www.app.com for more information.
*
*  @author Jakub Bogacz SA <bogaczjakub@gmail.com>
*  @copyright  2017 app
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/
include '../classes/CustomException.php';
include dirname(__FILE__) . '/phpSettings.inc.php';
try {
    if (file_exists(dirname(__FILE__) . '/autoload.inc.php') &&
        !empty(dirname(__FILE__) . '/autoload.inc.php')) {
        include dirname(__FILE__) . '/autoload.inc.php';
    } else {
        throw new CustomException('Could not load autoload file.');
    }
} catch (customException $e) {
    echo $e->getCustomMessage($e);
    exit();
}

try {
    if (file_exists(dirname(__FILE__) . '/defines.inc.php') &&
        !empty(dirname(__FILE__) . '/defines.inc.php')) {
        include dirname(__FILE__) . '/defines.inc.php';
    } else {
        throw new CustomException('Could not load defines file.');
    }
    if (file_exists(dirname(__FILE__) . '/config.inc.php') &&
        !empty(dirname(__FILE__) . '/config.inc.php')) {
        include dirname(__FILE__) . '/config.inc.php';
    } else {
        throw new CustomException('Could not load config file.');
    }
    if (file_exists(dirname(__FILE__) . '/settings.inc.php') &&
        !empty(dirname(__FILE__) . '/settings.inc.php')) {
        include dirname(__FILE__) . '/settings.inc.php';
    } else {
        throw new CustomException('Could not load settings file.');
    }
} catch (CustomException $e) {
    echo $e->getCustomMessage($e);
    exit();
}
