<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Scrip;
use App\Models\Sector;
use App\Models\Industry;
use App\Models\IndustrySector;

use Carbon\Carbon;
class ScripController extends Controller
{
    public function index()
    {
        
    }
    public function scrip($id)
    {
        // return $id;
        $scrip = Scrip::with('industry_sector.sector', 'industry_sector.industry', 'bhavcopies')->find($id);
        return $scrip;
            // ->get();
        // return $request->query('id');
        // return '$request';
    }
    public function getGroups() {
        return Scrip::select('group')->distinct()->orderBy('group')->get();
    }
    public function getAllScrips(Request $request) {
        $industry = $request->industry;
        $sector = $request->sector;
        $condition = [];
        $ids = [];

        if($sector != -1) {
            $secObjs = Sector::find($sector);
            $pivots =  $secObjs->Industries; 
            foreach($pivots as $pi) {
                if($industry != -1) {
                    $ids[] = ($pi->id == $industry) ? $pi->pivot->id : '';
                } else {
                    $ids[] = $pi->pivot->id;
                }
            }
        } else {

            if($industry != -1) {
                $indObjs = Industry::find($industry);
                $pivots = $indObjs->sectors;
                foreach($pivots as $pi) {                   
                    $ids[] = $pi->pivot->id;                   
                }                
            }            
        }

        if($industry != -1) 
            $condition = ['industry_id' => $industry ];

        if($sector != -1  && $industry != -1)
            $condition = ['sector_id' => $sector, 'industry_id' => $industry  ];

            
        if($sector != -1 || $industry != -1) {
            $scrips = Scrip::whereIn('industry_sector_id',$ids)
                ->orderBy('name')
                ->with('industry_sector.sector', 'industry_sector.industry')
                ->get();
        } else {
             $scrips = Scrip::orderBy('name')
                ->with('industry_sector.sector', 'industry_sector.industry')
                ->get();
        }            
        return $scrips;      
    }

    public function setup()
    {       
        $collection = Scrip::all();
        $industries = Industry::all();
        $sectors = Sector::all();
        $file = public_path('stock-master-completed.csv');
        $scripArr = $this->csvToArray($file);
        $data = [];

        for ($i = 0; $i < count($scripArr); $i ++)
        {
            $symbol =  $scripArr[$i]['symbol'];
            $desired_object = $collection->filter(function($item) use ($symbol) {
                return $item->symbol == $symbol ;
            });

            if(count($desired_object) == 0) {

                $sectorName =  trim($scripArr[$i]['sector']);
                $sectorName = ($sectorName == 'NA') ? 'Others' : $sectorName ;
                $scripSector = $sectors->filter(function($sec) use ($sectorName) {
                    return $sec->name == $sectorName ;
                })->first();

                if(!empty($scripSector) ) 
                    $sectorId = $scripSector->id;
                else 
                    $sectorId = null;


                $indName =  trim($scripArr[$i]['industry']);
                $scripIndustry = $industries->filter(function($ind) use ($indName, $sectorId) {
                    return (($ind->name == $indName) && ($ind->sector_id == $sectorId));
                })->first();

                if(!empty($scripIndustry) ) 
                    $industryId = $scripIndustry->id;
                else 
                    $industryId = null;

                /*** Adding Pivot table id (industry_sector)*/
                $sectorName =  trim($scripArr[$i]['sector']);
                $sectorName = ($sectorName == 'NA') ? 'Others' : $sectorName ;
                $sector = Sector::where('name', '=', $sectorName)->first();
                $indName =  trim($scripArr[$i]['industry']);
                $industry = Industry::where('name', '=', $indName)->first();
                $sectorIndustries = $sector->industries->find($industry->id);
                                
                $data[] = [
                    'symbol' => $scripArr[$i]['symbol'],
                    'name' => $scripArr[$i]['name'],
                    'series' => $scripArr[$i]['series'],
                    'status' => $scripArr[$i]['status'],                    
                    'trading_status' => $scripArr[$i]['trading_status'],
                    'issuedCap' => ($scripArr[$i]['issuedCap'] == '-') ? 0 : $scripArr[$i]['issuedCap'],
                    'faceValue' => ($scripArr[$i]['faceValue'] == '-') ? 0 :$scripArr[$i]['faceValue'],                    
                    'isin_no' => $scripArr[$i]['isin'],
                    'industry_sector_id' => $sectorIndustries->pivot->id,                    
                    'listing_date' => date('Y-m-d',strtotime($scripArr[$i]['listingDate'])),                    
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
