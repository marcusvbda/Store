<?php

class json{

    public static function toString($obj, $vcrudModel=false)
    {
        $json = "[";
        for($i=0;$i<count($obj);$i++)
        {
            if($vcrudModel)
            {
                $json .= '{"text":"'.$obj[$i]->text.'", "value":"'.$obj[$i]->value.'" }';
                if($i<=count($obj)-2)
                {
                    $json.=",";
                }
            }
        }
        $json .= "]";
        return $json;
    }

}