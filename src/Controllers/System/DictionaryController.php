<?php


namespace Meilunzhi\Admin\Controllers\System;


use Illuminate\Routing\Controller;
use Meilunzhi\Admin\Contracts\Service;
use Meilunzhi\Admin\Traits\HasResourceRoutes;

class DictionaryController extends Controller
{
    use HasResourceRoutes;

    /**
     * 获取字典值
     * @param Service $service
     * @return mixed
     */
    public function getDict(Service $service)
    {
        $name = request()->input('name');
        return $service->getDict($name);
    }
}
