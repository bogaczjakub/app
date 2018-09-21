<?php
/*
* 2017- app
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade app to newer
* versions in the future. If you wish to customize app for your
* needs please refer to http://www.app.com for more information.
*
*  @author Jakub bogacz <bogaczjakub@gmail.com>
*  @copyright  2017- app
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/

try
{
    if(!include ('../../classes/appException.php'))
    {
        throw new Exception('Can not include exception class.');
    }else {
        try
        {
            if(!include ('../../config/defines.inc.php'))
            {
                throw new appException('Can not include defines file.');
            };
        }catch (appException $e) {
            echo $e->getMessage();
        }
    }
}catch (Exception $e) {
    echo $e->getMessage();
    ;}





