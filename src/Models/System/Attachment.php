<?php

namespace Meilunzhi\Admin\Models\System;

use Illuminate\Database\Eloquent\Model;
use Meilunzhi\Admin\Traits\SerializeDate;

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
