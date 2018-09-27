<?php
namespace App\Utils;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
class Excel{

    public static function download($obj,$filename)
    {
        if(is_object($obj))
        {
            $obj = (array)$obj->toArray();
        }
        $header = array_keys((array)$obj[0]);
        array_unshift($obj,$header);
        $spreadsheet = new Spreadsheet(); 
        $Excel_writer = new Xls($spreadsheet); 
        $activeSheet = $spreadsheet->setActiveSheetIndex(0);
        $activeSheet = $spreadsheet->getActiveSheet()->fromArray($obj);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xls"'); 
        header('Cache-Control: max-age=0');
        
        return $Excel_writer->save('php://output');
    }


    public static function getExtensao($arquivo)
    {
        $ext =  explode(".", $arquivo);
        return ucfirst(strtolower($ext[count($ext)-1]));
    }

    public static function read($dir,$raw = false)
    {
        $ext = Excel::getExtensao($dir);
        ini_set("memory_limit", "128M");
        $extensao = Excel::getExtensao($dir);
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($extensao);
        $reader->setReadDataOnly(true);
        $excel = $reader->load($dir);
        $excel = $excel->getActiveSheet()->toArray();
        if($raw)
            return $excel;
        $val = Excel::getValores($excel);
        return $val;
    }

    public static function tirarAcentos($string)
    {
        $str =  preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);
        $str = str_replace("ç","c",$str);
        $str = str_replace("Ç","C",$str);
        return strtoupper($str);
    }

    public static function getValores($arquivo)
    {
        $valores = [];
        $posicao = 0;
        $cabecalho = $arquivo[0];
        for($i=1; $i<count($arquivo);$i++)
        {   
            foreach($arquivo[$i] as $key => $value)
            {
                $aux[Excel::tirarAcentos(strtoupper(str_replace(" ","_",trim($cabecalho[$key]))))] = $value;
            }
            array_push($valores, $aux); 
            $posicao++;
        }
        return $valores;
    }


}