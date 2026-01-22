<?php


namespace meilunzhi\Admin\Controllers\Auth;

use Illuminate\Routing\Controller;
use meilunzhi\Admin\Contracts\Service;
use meilunzhi\Admin\Traits\HasResourceRoutes;

class AdminUserController extends Controller
{
    use HasResourceRoutes;

    /**
     * 分配角色
     * @return mixed
     */
    public function assignRole(Service $service)
    {
        return $service->assignRole();
    }
}
