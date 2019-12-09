<?php

namespace App;
use App\Bullpen2;
use Illuminate\Database\Eloquent\Model;


class Bullpen2 extends Model
{

    public function getAll()
    {
        $bullpen1 =  Bullpen2::orderBy('created_at', 'asc')->get();

    }

    public function insertData($data){
          DB::table('bullpen2s')->insert($data);
          return 1;
     
      }

}