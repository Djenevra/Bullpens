<?php namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Bullpen1;
use App\Bullpen2;
use App\Bullpen3;
use App\Bullpen4;


class Bullpen1Controller extends Controller {

    public function getAllBullpens()
    {
        $bullpen1 =  Bullpen1::orderBy('created_at', 'asc')->get();
        $bullpen2 =  Bullpen2::orderBy('created_at', 'asc')->get();
        $bullpen3 =  Bullpen3::orderBy('created_at', 'asc')->get();
        $bullpen4 =  Bullpen4::orderBy('created_at', 'asc')->get();

        return view('bullpens', [
            'bullpen1' => $bullpen1, 
            'bullpen2' => $bullpen2,
            'bullpen3' => $bullpen3,
            'bullpen4' => $bullpen4

        ]);
    }

    
    public function addSheep(Request $request){
          
        $bullpensNameArray = $request->bullpensNameArray;
        $counter = $request->counter;
        $data = array('sheep_name' => 'sheep'.$counter);
        $randomKey = array_rand($bullpensNameArray, 1);
        $bullpenName = $bullpensNameArray[$randomKey];
        //DB::table($bullpensNameArray[$randomKey])->insert($data);
        $sheepsList =  DB::table($bullpensNameArray[$randomKey])->get();
        $dataForHistory = array('day'=>$counter, 'bullpen'=>$bullpenName, 'sheep_name'=>'sheep'.$counter, 'status'=>'added');
       // DB::table('histories')->insert($dataForHistory);
        $response = array(
           'status' => 'success',
           'sheepList' => $sheepsList,
           'bullpenName' => $bullpenName
        );
        return response()->json($response);
     }

     public function killSheep(Request $request) {
        $bullpen = $request->bullpen;
        $sheep = DB::table($bullpen)->inRandomOrder()->first();
        //DB::table($bullpen)->where('sheep_name', '=', $sheep->sheep_name)->delete();
         $currentBullpen =  DB::table($bullpen)->get();
         $counter = $request->counter;
         $dataForHistory = array('day'=>$counter, 'bullpen'=>$bullpen, 'sheep_name'=>$sheep->sheep_name, 'status'=>'killed');
         //dd($currentBullpen);
        // $currentBullpen =  DB::table('histories')->insert($dataForHistory);
         $response = array(
            'status' => 'success',
            'bullpen' => $currentBullpen
         );
         return response()->json($response);
     }

     public function relocateSheep(Request $request) {
        $bullpenWithMinSheeps = $request->bullpenArrayListWithMinSheeps;
        if ($bullpenWithMinSheeps != null) {
            $counter = $request->counter;
            $bullpenWithMaxSheeps = $request->bullpenArrayListWithMaxSheeps;
            $randKeyForMaxSheepsBullpen = array_rand($bullpenWithMaxSheeps, 1);
            $randKeyForMinSheepsBullpen = array_rand($bullpenWithMinSheeps, 1);
            $sheep = DB::table($bullpenWithMaxSheeps[$randKeyForMaxSheepsBullpen]['bullpen'])->inRandomOrder()->first();
            $currentBullpenWithMaxSheepsName = $bullpenWithMaxSheeps[$randKeyForMaxSheepsBullpen]['bullpen'];
            $currentBullpenWithMinSheepsName = $bullpenWithMinSheeps[$randKeyForMinSheepsBullpen]['bullpen'];
            DB::table($bullpenWithMaxSheeps[$randKeyForMaxSheepsBullpen]['bullpen'])->where('sheep_name', '=', $sheep->sheep_name)->delete();
            $currentBullpenWithMaxSheeps = DB::table($bullpenWithMaxSheeps[$randKeyForMaxSheepsBullpen]['bullpen'])->get();
            $data = array('sheep_name' => $sheep->sheep_name);
            DB::table($bullpenWithMinSheeps[$randKeyForMinSheepsBullpen]['bullpen'])->insert($data);
            $currentBullpenWithMinSheeps = DB::table($bullpenWithMinSheeps[$randKeyForMinSheepsBullpen]['bullpen'])->get();
            $dataForHistory = array('day'=>$counter, 'bullpen'=>$bullpenWithMinSheeps[$randKeyForMinSheepsBullpen]['bullpen'], 'sheep_name'=>$sheep->sheep_name, 'status'=>'relocated from '.$bullpenWithMaxSheeps[$randKeyForMaxSheepsBullpen]['bullpen']);
            DB::table('histories')->insert($dataForHistory);
            $response = array(
                'status' => 'success',
                'relocate'=> true,
                'currentBullpenWithMaxSheeps' => $currentBullpenWithMaxSheeps,
                'currentBullpenWithMinSheeps' => $currentBullpenWithMinSheeps,
                'currentBullpenWithMaxSheepsName' => $currentBullpenWithMaxSheepsName,
                'currentBullpenWithMinSheepsName' => $currentBullpenWithMinSheepsName
            );
        } else {
            $response = array(
                'status' => 'success',
                'relocate' => false
            );
        }
        //dd($response);
         return response()->json($response);
     }
     
}

