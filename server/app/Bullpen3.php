<?php

namespace App;
use App\Bullpen3;
use Illuminate\Database\Eloquent\Model;


class Bullpen3 extends Model
{

    public function getAll()
    {
        $bullpen1 =  Bullpen3::orderBy('created_at', 'asc')->get();

        var_dump('done', $bullpen1);
        //return ['bullpen1' => $bullpen1];
    }

}