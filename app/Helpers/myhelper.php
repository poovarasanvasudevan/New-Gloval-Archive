<?php
namespace App\Helpers;
use App\Artefact;

/**
 * Created by PhpStorm.
 * User: poovarasanv
 * Date: 11/8/16
 * Time: 4:54 PM
 */

class MyHelper {
    public static function getAttrValues($id,$code) {
        $fullJson = Artefact::find($id)->artefact_values;
        return $fullJson[$code]['attr_value'];
    }
}