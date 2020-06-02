<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'adresses'; 
    public function state(){

        return $this->belongsTo(State::class);

    }

    public function city(){

        return $this->belongsTo(City::class);

    }

    public function realState(){
        return $this->hasOne(RealState::class);
    }
}
