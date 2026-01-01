<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name
 * @property string|null $pictureUrl
 * @property string|null $country
 * @property string|null $link
 */
class LangAuthor extends Model {
    protected $guarded = array('id');

    public function languages(): BelongsToMany {
        return $this->belongsToMany(ProLang::class);
    }
}
