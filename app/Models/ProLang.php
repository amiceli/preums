<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $apiId
 * @property string $link
 * @property string $name
 * @property string|null $company
 * @property string $years
 * @property int $yearGroupId
 * @property-read \App\Models\YearGroup $yearGroup
 */
class ProLang extends Model {
    protected $guarded = array('id');

    public function yearGroup(): BelongsTo {
        return $this->belongsTo(YearGroup::class, 'yearGroupId');
    }

    public function authors(): BelongsToMany {
        return $this->belongsToMany(LangAuthor::class);
    }
}
