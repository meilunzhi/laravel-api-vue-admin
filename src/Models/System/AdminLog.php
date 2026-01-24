<?php

namespace Meilunzhi\Admin\Models\System;

use Illuminate\Database\Eloquent\Model;
use Meilunzhi\Admin\Services\System\AdminLogService;
use Meilunzhi\Admin\Traits\SerializeDate;

class AdminLog extends Model
{

    use SerializeDate;

    protected $table = 'app_admin_log';

    protected $fillable = [
        'admin_id',
        'name',
        'title',
        'ip',
        'content',
    ];

    protected $appends = [];

    protected $casts = [
        'content' => 'json',
    ];

    public function getTable()
    {
        return config('admin.table_names.admin_log', parent::getTable());
    }

    public function getIpAttribute($value)
    {
        return long2ip($value);
    }
}
