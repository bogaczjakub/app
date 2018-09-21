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
* If you did not receive a copy of the license and are unable to
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
try {
    if (file_exists(dirname(__FILE__) . '/config/preload.inc.php')) {
        include dirname(__FILE__) . '/config/preload.inc.php';
        $Core = new classes\Core();
        $Core->appInit('admin');
    } else {
        throw new Exception('Could not find preload.inc.php file');
    }
} catch (Exception $e) {
    die($e->getMessage());
}


