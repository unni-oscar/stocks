<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bhavcopy;
use App\Models\Scrip;
use Carbon\Carbon;
class BhavcopyController extends Controller
{
    function addBhavcopy() {

        include(app_path() . '/functions/simple_html_dom.php');
        $html = file_get_html('http://www.google.com/');
        $title = $html->find('title', 0);
        $image = $html->find('img', 0);

        // echo $title->plaintext."<br>\n";
        // echo $image->src;

        return $image->src;
        //--------------------------
        $bhavDate = '20042021';
        $file = 'https://archives.nseindia.com/products/content/sec_bhavdata_full_'.$bhavDate.'.csv';
        // TODO: Check the data exists for this selected data
        // $checkBhavData = Bhavcopy::get
        // $exitingBhavcopyData = Bhavcopy::with('industry')->orderBy('name')->where($condition)->get();

        $collection = Scrip::all();
        $data = [];
        $dataNotInserted = [];

        if($handle = @fopen($file, 'r')) {
            $header = true;

            while ($csvLine = fgetcsv($handle, 5000, ",")) {
                if ($header) {
                    $header = false;
                } else {

                    $symbol =  $csvLine[0];
                    // Checking if the scrip already in the database
                    $desired_object = $collection->filter(function($item) use ($symbol) {
                        return $item->symbol === $symbol ;
                    })->first();
                    // Prepare the array for adding into database
                    if(gettype($desired_object) == 'object' && trim($csvLine[1]) === 'EQ') {
                        $delQty = (trim($csvLine[13]) == '-' ) ? 0 :$csvLine[13] ;
                        $delPer = (trim($csvLine[14]) == '-' ) ? 0 :$csvLine[14] ;
                        $data[] = [
                            'bhavcopy_date' => date('Y-m-d',strtotime($csvLine[2])),
                            'prev_close' => $csvLine[3],
                            'open_price' => $csvLine[4],
                            'high_price' => $csvLine[5],
                            'low_price' => $csvLine[6],
                            'last_price' => $csvLine[7],
                            'close_price' => $csvLine[8],
                            'avg_price' => $csvLine[9],
                            'trd_qty' => $csvLine[10],
                            'no_of_trades' => $csvLine[11],
                            'turnover' => $csvLine[12],
                            'del_qty' => $delQty,
                            'del_per' => $delPer,
                            'exchange' => 'NSE',
                            'scrip_id' => $desired_object->id,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()                                         
                        ];
                    }else {
                        // Preparing data(for which scrip doesn't exists in the db) for writing into file
                        $dataNotInserted[] = [
                            'SYMBOL' => $csvLine[0],
                            'SERIES' => $csvLine[1],
                            'DATE1' => $csvLine[2],
                            'PREV_CLOSE' => $csvLine[3],
                            'OPEN_PRICE' => $csvLine[4],
                            'HIGH_PRICE' => $csvLine[5],
                            'LOW_PRICE' => $csvLine[6],
                            'LAST_PRICE' => $csvLine[7],
                            'CLOSE_PRICE' => $csvLine[8],
                            'AVG_PRICE' => $csvLine[9],
                            'TTL_TRD_QNTY' => $csvLine[10],
                            'TURNOVER_LACS' => $csvLine[11],
                            'NO_OF_TRADES' => $csvLine[12],
                            'DELIV_QTY' => $csvLine[13],
                            'DELIV_PER' => $csvLine[14],
                        ];
                    }              
                }
            }
            Bhavcopy::insert($data);
            // Writing balance data (for which symbol was not found) to csv file
            $folderName = "NotInserted/";
            $fileName = public_path($folderName.$bhavDate.'-balance.csv');           
            $columns = array(
                'SYMBOL', 
                'SERIES', 
                'DATE1', 
                'PREV_CLOSE',
                'OPEN_PRICE', 
                'HIGH_PRICE',
                'LOW_PRICE', 
                'LAST_PRICE', 
                'CLOSE_PRICE',
                'AVG_PRICE',
                'TTL_TRD_QNTY',
                'TURNOVER_LACS',
                'NO_OF_TRADES',
                'DELIV_QTY',
                'DELIV_PER'
            );
            $file = fopen($fileName, 'w');
	        fputcsv($file, $columns);

            foreach ($dataNotInserted as $row) {              
                fputcsv($file, array(
                     $row['SYMBOL'],
                     $row['SERIES'],
                     $row['DATE1'],
                     $row['PREV_CLOSE'],
                     $row['OPEN_PRICE'],
                     $row['HIGH_PRICE'],
                     $row['LOW_PRICE'],
                     $row['LAST_PRICE'],
                     $row['CLOSE_PRICE'],
                     $row['AVG_PRICE'],
                     $row['TTL_TRD_QNTY'],
                     $row['TURNOVER_LACS'],
                     $row['NO_OF_TRADES'],
                     $row['DELIV_QTY'],
                     $row['DELIV_PER'],
                    )
                );
            }
            fclose($file);            
            return "Added bhavcopy data for this date";
        } else {
            return "Could not find bhavcopy file";
        }        
    }

    
}
