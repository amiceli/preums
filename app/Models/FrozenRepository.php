<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrozenRepository extends Model {
    protected $guarded = array('id');

    protected $casts = array('topics' => 'array');

    protected $appends = array('owner');

    protected $hidden = array('ownerLogin', 'ownerGithubId', 'ownerAvatarUrl');

    public function getOwnerAttribute() {
        return array(
            'login' => $this->ownerLogin,
            'id' => $this->ownerGithubId,
            'avatarUrl' => $this->ownerAvatarUrl,
        );
    }
}
