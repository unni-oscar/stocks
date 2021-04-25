<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sector;
use Carbon\Carbon;


class SectorController extends Controller
{
    public function index()
    {        
        return Sector::orderBy('name')->get();  
    }
    
    public function setSectors() {        
        $collection = Sector::all();
        $file = public_path('stock-master-completed.csv');
        $sectorArr = $this->csvToArray($file);
        $data = [] ;
        
        for ($i = 0; $i < count($sectorArr); $i ++)
        {

            $sectorName = trim($sectorArr[$i]['sector']);
            $sectorName = ($sectorName == 'NA') ? 'Others' : $sectorName ;
            $desired_object = $collection->filter(function($item) use ($sectorName) {
                return trim($item->name) == trim($sectorName) ;
            });

            if(count($desired_object) == 0)  {
                $isExists = $this->nameExists( trim($sectorName), $data);
                if(!$isExists ) {
                    $data[] = [
                        'name' => $sectorName,                   
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()                                    
                    ];    
                }           
            }            
        }
        Sector::insert($data);        
        return "inserted";  
    }
    function nameExists($name, $array) {
        foreach ($array as $key => $val) {            
            if(strcmp(trim($val['name']),$name ) == 0){
                return true;
            }
        }
        return null;
     }
    function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }
        return $data;
    }
}