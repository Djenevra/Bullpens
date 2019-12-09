<?php

namespace App;
use App\Bullpen1;
use Illuminate\Database\Eloquent\Model;

class Bullpen1 extends Model
{

    public function getAll()
    {
        $bullpen1 =  Bullpen1::orderBy('created_at', 'asc')->get();

       // var_dump('done', $bullpen1);
        //return ['bullpen1' => $bullpen1];
    }

}




