<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $apiId
 * @property string $name
 * @property int $position
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProLang> $proLangs
 */
class YearGroup extends Model {
    protected $fillable = array(
        'apiId', 'name', 'position',
    );

    public function languages(): HasMany {
        return $this->hasMany(ProLang::class, 'yearGroupId');
    }
}
