<?php


namespace Meilunzhi\Admin\Repositories\System;

use Meilunzhi\Admin\Repository;

class AttachmentRepository extends Repository
{

    protected $allowFields = [
        'id',
        'album_id',
        'name',
        'path',
        'mime_type',
        'size',
        'created_at',
        'updated_at',
    ];




}
