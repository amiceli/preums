<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class LangAuthor extends Model {
    protected $guarded = array('id');

    public function languages(): BelongsToMany {
        return $this->belongsToMany(ProLang::class);
    }
}
