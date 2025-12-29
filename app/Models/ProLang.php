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

    protected $casts = array(
        'years' => 'array',
    );

    public function yearGroup(): BelongsTo {
        return $this->belongsTo(YearGroup::class, 'yearGroupId');
    }

    // predecessors

    public function parents(): BelongsToMany {
        return $this->belongsToMany(ProLang::class, 'predecessors', 'child_id', 'parent_id');
    }

    public function children(): BelongsToMany {
        return $this->belongsToMany(ProLang::class, 'predecessors', 'parent_id', 'child_id');
    }

    public function isOrphan(): bool {
        return $this->parents()->count() === 0;
    }

    // authors

    public function authors(): BelongsToMany {
        return $this->belongsToMany(LangAuthor::class);
    }

    public function authorNames(): array {
        return $this->authors()->pluck('name')->toArray();
    }
}
