<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

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

    protected $appends = array('paths');

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

    // paths

    public function getPathsAttribute() {
        $targetId = DB::table('pro_langs')->where('id', $this->id)->value('id');

        $paths = DB::select("
            WITH RECURSIVE paths AS (
                SELECT
                    l.id,
                    l.name,
                    l.name::text AS path
                FROM pro_langs l
                WHERE NOT EXISTS (
                    SELECT 1 FROM predecessors p WHERE p.child_id = l.id
                )

                UNION ALL

                SELECT
                    c.id,
                    c.name,
                    paths.path || ' -> ' || c.name
                FROM paths
                JOIN predecessors p ON p.parent_id = paths.id
                JOIN pro_langs c ON c.id = p.child_id
            )
            SELECT path
            FROM paths
            WHERE id = ?
        ", array($targetId));

        return array_map(fn ($r) => $r->path, $paths);
    }
}
