<?php

namespace App;
use App\Bullpen4;
use Illuminate\Database\Eloquent\Model;


class Bullpen4 extends Model
{

    public function getAll()
    {
        $bullpen1 =  Bullpen4::orderBy('created_at', 'asc')->get();

        var_dump('done', $bullpen1);
        //return ['bullpen1' => $bullpen1];
    }

}