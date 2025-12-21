<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LangStats extends Model {
    protected $appends = array('isoCode');

    public function getIsoCodeAttribute() {
        return $this->attributes['iso_code'];
    }
}
