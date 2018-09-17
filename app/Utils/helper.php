<?php
namespace App\Utils;
class Helper
{
    public static function implodeEloquent($field,$result)
    {
        try
        {
            $array = []; 
            foreach($result as $r)
            {
                array_push($array, $r->{$field});
            }
            return implode(",", $array);
        }
        catch (\Exception $e) 
        {
            return "";
        }
    }

}