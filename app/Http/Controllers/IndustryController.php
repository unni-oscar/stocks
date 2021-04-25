<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Industry;
use App\Models\Sector;
use App\Models\SectorIndustry;


use Carbon\Carbon;

class IndustryController extends Controller
{
    public function index() {
        $industries = Industry::orderBy('name')->get();        
        return $industries; 
    }
    public function setScripMaster() {
        $file = public_path('stock_master.txt');        
        $file = file_get_contents($file , true);
        $array = json_decode($file, true);
        $data = [];

        foreach($array as $values) {
        
           $data[] = [
                'name' => $values['info']['companyName'],

                'symbol' => $values['metadata']['symbol'],
                'isin' => $values['metadata']['isin'],
                'industry' => $values['metadata']['industry'],
                'sector' => $values['metadata']['pdSectorInd'],
                'listingDate' => $values['metadata']['listingDate'],
                'series' => $values['metadata']['series'],
                'status' => $values['metadata']['status'],
                'sector_pe' => $values['metadata']['pdSectorPe'],
                'symbol_pe' => $values['metadata']['pdSymbolPe'],

                'trading_status' => $values['securityInfo']['tradingStatus'],
                'issuedCap' => $values['securityInfo']['issuedCap'],
                'faceValue' => $values['securityInfo']['faceValue'],
            ];
         }
        
        $fileName = public_path('stock-master-completed.csv');           
        $columns = array(
            'name', 
            'symbol', 
            'isin', 
            'industry',
            'sector', 
            'listingDate',
            'series', 
            'status', 
            'sector_pe',
            'symbol_pe',
            'trading_status',
            'issuedCap',
            'faceValue'            
        );
        $file = fopen($fileName, 'w');
        fputcsv($file, $columns);
        foreach ($data as $row) {              
            fputcsv($file, array(
                 $row['name'],
                 $row['symbol'],
                 $row['isin'],
                 $row['industry'],
                 $row['sector'],
                 $row['listingDate'],
                 $row['series'],
                 $row['status'],
                 $row['sector_pe'],
                 $row['symbol_pe'],
                 $row['trading_status'],
                 $row['issuedCap'],
                 $row['faceValue'],               
                )
            );
        }
        fclose($file);            
        return "Added bhavcopy data for this date";
    }

    public function getIndustriesForSector($id) {
        $sector =   Sector::find($id);
        $pivots =  $sector->industries;
        $ids = [];

        foreach($pivots as $pi) {
            $ids[] = $pi->id;
        }
        $industries = Industry::whereIn('id', $ids)->orderBy('name')
            ->get();
        return $industries;
    }

    public function setIndustries()
    {            
        $collection = Industry::all();
        $sectors = Sector::all();
        $file = public_path('stock-master-completed.csv');
        $industryArr = $this->csvToArray($file);
        $data = $dataTemp = [];

        for ($i = 0; $i < count($industryArr); $i ++)
        {           
            $indName =  trim($industryArr[$i]['industry']);           
            $sectorName =  trim($industryArr[$i]['sector']);
            $sectorName = ($sectorName == 'NA') ? 'Others' : $sectorName ;
            $sector = Sector::where('name', '=', $sectorName)->first();
            $isExists = $this->nameExists( trim($indName),$sectorName, $dataTemp);

            if(!$isExists ) {
                   
                $existingIndustry =  Industry::select('id')->where('name','=' , $indName)->first();

                if(empty($existingIndustry) ) {

                    $industry = Industry::create([
                        'name' => $indName,                   
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                                    
                    ]);    
                    $sector->industries()->attach([$industry->id]);
                    $data[] = [
                        'sector_id' => $sector->id,  
                        'industry_id' =>  $industry->id ,                
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()                                    
                    ];   
                } else {        
                    
                    $sector->industries()->attach([$existingIndustry['id']]);  
                    $data[] = [
                        'sector_id' => $sector->id,  
                        'industry_id' =>   $existingIndustry['id'],                
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()            
                    ];   
                }                                    
                $dataTemp[] = [
                    'iname' => $indName,
                    'sname' => $sectorName
                ];
  
                    
            }      
           
        }               
        return "Inserted";
    }

    function setup(){
        $file = public_path('symbols.csv');
        $scripArr = $this->csvToArray($file);
        $data = [];

        for ($i = 0; $i < count($scripArr); $i ++)
        {
            $data[] =  $scripArr[$i]['SYMBOL'];

        }
        return $data;

    }
    function nameExists($name, $sName, $array) {
        foreach ($array as $key => $val) {            
            if(strcmp(trim($val['iname']),$name ) == 0 && ($val['sname'] == $sName) ){
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
