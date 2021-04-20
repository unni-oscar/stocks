<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Scrip;
use App\Models\Industry;
use Carbon\Carbon;
class ScripController extends Controller
{
    public function index()
    {
        
    }
    public function getGroups() {
        return Scrip::select('group')->distinct()->orderBy('group')->get();
    }
    public function getAllScrips(Request $request) {
       

        $industry = $request->industry;
        $group = $request->group;
        $condition = [];
        if($industry != -1) 
            $condition = ['industry_id' => $industry ];

        if($group != -1) 
             $condition = ['group' => $group ];

        if($group != -1  && $industry != -1)
            $condition = ['group' => $group, 'industry_id' => $industry  ];

            // return $condition;
        $scrips = Scrip::with('industry')->orderBy('name')->where($condition)->get();
        // $scrips->where('group', 'A');
        // $scrips->listing_date->format('d/m/Y');
        return $scrips;      
    }
    public function setup()
    {       
        $collection = Scrip::all();
        $Industries = Industry::all();
        // return $Industries;
        // $file = public_path('LDE_EQUITIES_MORE_THAN_5_YEARS.csv');
        $file = public_path('equity.csv');
        
        $scripArr = $this->csvToArray($file);
        $data = [];

        for ($i = 0; $i < count($scripArr); $i ++)
        {
            $symbol =  $scripArr[$i]['isin_no'];
            $desired_object = $collection->filter(function($item) use ($symbol) {
                return $item->isin_no == $symbol ;
            });

            if(count($desired_object) == 0) {
                $industryName =  trim($scripArr[$i]['Industry']);
                if($industryName != " " && $industryName != "") {
                    $scripIndustry = $Industries->filter(function($indus) use ($industryName) {
                        return $indus->name == $industryName ;
                    })->first();
                }
                
                // return $scripIndustry->id;
                // $f = ($scripIndustry->id > 0) ? $scripIndustry->id : '';
                // try{

                // } catch(error $e) {
                //     return $e;
                // }
                $data[] = [
                    'symbol' => $scripArr[$i]['symbol'],
                    'name' => $scripArr[$i]['name'],
                    // 'series' => $scripArr[$i]['series'],
                    // 'bse_code' => $scripArr[$i]['bse_code'],
                    'isin_no' => $scripArr[$i]['isin_no'],
                    'industry_id' => $scripIndustry->id,
                    // 'listing_date' => date('Y-m-d',strtotime($scripArr[$i]['listing_date'])),
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
