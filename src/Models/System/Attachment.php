<?php

namespace meilunzhi\Admin\Models\System;

use Illuminate\Database\Eloquent\Model;
use meilunzhi\Admin\Traits\SerializeDate;

class Attachment extends Model
{

    use SerializeDate;

    protected $fillable = [
        'album_id',
        'name',
        'admin_id',
        'path',
        'mime_type',
        'size'
    ];

    public function getTable()
    {
        return config('admin.table_names.attachments', parent::getTable());
    }

}
