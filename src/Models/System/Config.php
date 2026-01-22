<?php

namespace meilunzhi\Admin\Models\System;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $guarded = [];

    public function getTable()
    {
        return config('admin.table_names.configs', parent::getTable());
    }
}
