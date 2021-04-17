<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Scrip;
use Carbon\Carbon;
class ScripController extends Controller
{
    public function index()
    {
        $scrips = Scrip::all()->toArray();
        return $scrips;      
    }

    public function setup()
    {       
        $collection = Scrip::all();
        $file = public_path('equity.csv');
        $scripArr = $this->csvToArray($file);
        $data = [];
        
        for ($i = 0; $i < count($scripArr); $i ++)
        {
            $symbol =  $scripArr[$i]['symbol'];
            $desired_object = $collection->filter(function($item) use ($symbol) {
                return $item->symbol == $symbol ;
            });

            if(count($desired_object) == 0) {
                $data[] = [
                    'symbol' => $scripArr[$i]['symbol'],
                    'series' => $scripArr[$i]['series'],
                    'bse_code' => $scripArr[$i]['bse_code'],
                    'isin_no' => $scripArr[$i]['isin_no'],
                    'group' => $scripArr[$i]['group'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                                 
                ];
            }
        }
    
        Scrip::insert($data);
        return "inserted";       
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
