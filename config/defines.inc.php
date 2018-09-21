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
namespace defines;

use classes\Tools;
$Tools = new Tools();
$Tools->getDefinesData();

define('_ROOT_DIR_', $_SERVER['DOCUMENT_ROOT']);
define('CLASSES_DIR', _ROOT_DIR_ . '/classes/');
define('CONTROLLERS_DIR', _ROOT_DIR_ . '/controllers/');
define('ADMIN_CONTROLLERS', _ROOT_DIR_ . CONTROLLERS_DIR . '/admin/');
define('FRONT_CONTROLLERS', _ROOT_DIR_ . CONTROLLERS_DIR .'/front/');
define('CONNECTION_DATA', _ROOT_DIR_ . '/config/xml/connection.data.xml');