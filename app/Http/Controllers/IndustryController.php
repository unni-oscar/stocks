<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Industry;

use Carbon\Carbon;

class IndustryController extends Controller
{
    public function index() {
        $industries = Industry::orderBy('name')->get();
        // $scrips->where('group', 'A');
        // $scrips->listing_date->format('d/m/Y');
        return $industries; 
    }
    public function setup()
    {       
        $collection = Industry::all();
        $file = public_path('equity.csv');
        
        $industryArr = $this->csvToArray($file);
        $data = [];

        for ($i = 0; $i < count($industryArr); $i ++)
        {
            $name =  $industryArr[$i]['Industry'];
            $desired_object = $collection->filter(function($item) use ($name) {
                return trim($item->name) == trim($name) ;
            });

            if(count($desired_object) == 0) {
                $indName = $industryArr[$i]['Industry'];
             
                if($indName != " " && $indName != "") {
                    $isExists = $this->nameExists( trim($indName), $data);
                    if(!$isExists ) {
                        $data[] = [
                            'name' => trim($industryArr[$i]['Industry']),                   
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                                        
                        ];
                    }
                }
        
            }
        }        
        Industry::insert($data);
        return "Inserted";
        
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
