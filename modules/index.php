<?php

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





