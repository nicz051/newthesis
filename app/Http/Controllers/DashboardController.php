<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\itexmoo;
use App\functions;
use SMSGatewayMe\Client\ApiClient;
use SMSGatewayMe\Client\Configuration;
use SMSGatewayMe\Client\Api\MessageApi;
use SMSGatewayMe\Client\Model\SendMessageRequest;
use Carbon\Carbon;
use App\DateTime;
use App\Charts\energyConsumptionChart;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        // $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJhZG1pbiIsImlhdCI6MTU4MDU1OTc5OCwiZXhwIjo0MTAyNDQ0ODAwLCJ1aWQiOjc3MTA4LCJyb2xlcyI6WyJST0xFX1VTRVIiXX0.0itAev15AZH70jnynEZbqL5K0Z_YQe-Kvp1m5MZ_Ij0";

        //start send message
        // $array_fields['phone_number'] = '+639776498142';
        // $array_fields['message'] = 'Testing data';
        // $array_fields['device_id'] = 115241;



        // $curl = curl_init();

        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => "https://smsgateway.me/api/v4/message/send",
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => "",
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 50,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => "POST",
        //     CURLOPT_POSTFIELDS => "[  " . json_encode($array_fields) . "]",
        //     CURLOPT_HTTPHEADER => array(
        //         "authorization: $token",
        //         "cache-control: no-cache"
        //     ),
        // ));
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        // $response = curl_exec($curl);
        // $err = curl_error($curl);

        // curl_close($curl);



        // if ($err) {
        //     dd($err);
        // } else {
        //         // dd($response);
        // }
        //     //end send message

        //Start Read Messages
        //    - $searchArray = [];

        //     $curl = curl_init();

        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => "https://smsgateway.me/api/v4/message/search",
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => "",
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 50,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => "POST",
        //     CURLOPT_POSTFIELDS => "[  " . json_encode($searchArray) . "]",
        //     CURLOPT_HTTPHEADER => array(
        //         "authorization: $token",
        //         "cache-control: no-cache"
        //     ),
        // ));
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        // $response = curl_exec($curl);
        // $err = curl_error($curl);

        // curl_close($curl);



        // if ($err) {
        //     dd($err);
        // } else {
        //     $messagesRaw = json_decode($response);
        //     $filteredMessages = [];
        //     // dd(date('Y-m-d H:i:s'));
        //     foreach ($messagesRaw->results as $key => $message) {
        //         if($message->status == 'received' && $message->device_id == '115242'){
        //             $filteredMessages[] = $message;

        //             $date = $message->created_at;
        //             $convertedDate = date("Y-m-d H:i:s", strtotime($date)); //change format
        //             $adjustedTime = date('Y-m-d H:i:s', strtotime('-5 minutes',strtotime("now"))); //subtract 5mins

        //             // interval or date_diff()
        //             $diff = abs(strtotime($convertedDate) - strtotime($adjustedTime));
        //             $years   = floor($diff / (365*60*60*24));
        //             $months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
        //             $days    = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
        //             $hours   = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60));
        //             $minuts  = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60);

        //             // $interval =  date(date_diff($convertedDate, $adjustedTime));

        //             echo $convertedDate . " " . $adjustedTime;
        //             echo "<br>";

        //             // adjustedTime = date_add(currentTime,' -5 mins'); // Y-m-d H:i:s
        //             // $date = sadasd
        //             // $time = wqqwdqw
        //             // convertedDateTime = $date . " " . $time;
        //             // $messageTime =date_add($convertedDateTime, '8 hours');
        //             // if($message->status == 'received' && $message->device_id == '115242' && date_diff($convertedDate, $messageTime) >=0){
        //             if($diff >= 0){
        //                 $filteredMessages[] = $message;
        //         }
        //     }
        // }
        //     dd($filteredMessages);
        // }

        //-End Read SMS











            $accounts = DB::table('accounts')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                        ->from('bills')
                        ->whereRaw('bills.bills_account_number = accounts.account_number')
                        ->whereRaw('bills.due_date <= CURDATE()')
                        ->whereRaw('bills.status = 0');
            })
            ->get();

           $accounts1 = DB::table('accounts')
           ->whereExists(function ($query) {
               $query->select(DB::raw(1))
                     ->from('bills')
                     ->whereRaw('bills.bills_account_number = accounts.account_number')
                     ->whereRaw('bills.disconnection_date <= CURDATE()')
                     ->whereRaw('bills.status = 0');
           })
           ->get();

           $notifications = DB::table('notifications')
            ->whereDate('created', today())
            ->latest('created')
            ->limit(3)
            ->get();

           $ECCchart = new energyConsumptionChart;

           $ECCchart->labels( [ 'Sept' , 'Oct' , 'Nov' , 'Dec' ]) ;
           $ECCchart->dataset( 'Kilowatt Hour' , 'line' , [100,200,150,250] )
                    ->backgroundcolor("rgb(255, 99, 132)")
                    ->fill(false)
                    ->linetension(0)
                    ->options([]);

            $subscriberStatus = new energyConsumptionChart;
            $subscriberStatus->labels( [ 'With Power' , 'Without Power' ]) ;
            $subscriberStatus->dataset( 'Status' , 'pie' , [62,38] )
                             ->backgroundColor([
                                 "rgb(255, 150, 65)",
                                 "rgb(255, 99, 55)"
                            ]);
            $consumption = new energyConsumptionChart;
            $consumption->labels( [ 'Residential' , 'Commercial' ]) ;
            $consumption->dataset( 'Percentage' , 'bar' , [62,38] )
                                ->backgroundColor([
                                    "rgb(150, 150, 65)",
                                    "rgb(130, 99, 55)"
                            ]);
            $powerCuts = new energyConsumptionChart;
            $powerCuts->labels( [ 'Sept' , 'Oct' , 'Nov' , 'Dec' ]) ;
            $powerCuts->dataset( 'Cuts' , 'line' , [10,14,6,8] )
                        ->backgroundcolor("rgb(10, 60, 110)")
                        ->fill(true)
                        ->linetension(0);

           return 'here';
           return view('pages.dashboard', ['accounts' => $accounts, 'accounts1' => $accounts1, 'notifications' => $notifications,
                        'LineChart' => $ECCchart ,  'PieChart' => $subscriberStatus , 'BarChart' => $consumption,
                        'powerCuts' => $powerCuts]);
    }






    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
