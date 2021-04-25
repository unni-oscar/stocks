<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bhavcopy;
use App\Models\Scrip;
use Carbon\Carbon;
use Goutte\Client;


class BhavcopyController extends Controller
{
    function addBhavcopy($date) {

        // return $date;
        // $client = new Client();
        // $crawler = $client->request('GET', 'https://www.symfony.com/blog/');
        // $crawler->filter('h2 > a')->each(function ($node) {
        //     return  $node->text()."\n";
        // });
        // return '$crawler';
        //--------------------------
        // $bhavDate = '20042021';
        $bhavDate =  str_replace("-","",$date);
        // return $bhavDate;
        // return response()->json([ 'status' => 'OK', 'timestamp' => Carbon::now() ], 200);


        // return $bhavDate;
        $newDateFormat = date( "Y-m-d", strtotime( $date ) ); 
        // return $newDateFormat;

        // $newformat = date('Y-m-d',$date);
        //     return  $newformat;
        // $time = strtodate($date);
        //     return $time;
        // $newDateFormat = date('Y/m/d', $date);
        // return $newDateFormat;
        // $bhavDate = $date;
        // return convert($date);
        $file = 'https://archives.nseindia.com/products/content/sec_bhavdata_full_'.$bhavDate.'.csv';
        // TODO: Check the data exists for this selected data
        // $checkBhavData = Bhavcopy::get
        // $exitingBhavcopyData = Bhavcopy::where(['']])->get();
        // $formattedDate = Carbon::parse($date)->format('d-m-Y');
        // $time = strtotime('10/16/2003');

        // $newformat = date('Y-m-d',$bhavDate);
        $firstDateCheck = false;
        // return $newformat;
        $bhavcopyExists = Bhavcopy::where('bhavcopy_date', '=',  $newDateFormat)->first();
        // return $bhavcopyExists;
        if(isset($bhavcopyExists)) {
            return response()->json([ 'status' => 'error', 'message' => 'Bhavcopy exists for this date' ], 400);
        }else {
            $collection = Scrip::all();
            $data = [];
            $dataNotInserted = [];

            if($handle = @fopen($file, 'r')) {
                $header = true;

                while ($csvLine = fgetcsv($handle, 5000, ",")) {
                    if ($header) {
                        $header = false;
                    } else {
                        if(!$firstDateCheck) {
                            $newDateFormatTemp = date( "Y-m-d", strtotime( $csvLine[2] ) ); 
                            $bhavcopyExistsTemp = Bhavcopy::where('bhavcopy_date', '=',  $newDateFormatTemp)->first();
                            if(isset($bhavcopyExistsTemp)) {
                                return response()->json([ 'status' => 'error', 'message' => 'Bhavcopy exists for this date' ], 400);
                            } 
                            $firstDateCheck = true;  
                        }
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
                return response()->json([ 'status' => 'success', 'message' => 'Bhavcopy added successfully' ], 200);
            } else {
                return response()->json([ 'status' => 'error', 'message' => 'Couldn\'t find bhavcopy file for this date' ], 400);
            }  
        }

              
    }

    
}
