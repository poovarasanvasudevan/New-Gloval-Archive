<?php
use Thetispro\Setting\Facades\Setting;

/**
 * Created by PhpStorm.
 * User: poovarasanv
 * Date: 22/8/16
 * Time: 2:42 PM
 */
function getSetting($name)
{
    return Setting::get($name);
}