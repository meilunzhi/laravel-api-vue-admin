<?php


namespace Meilunzhi\Admin\Controllers\Auth;

use Illuminate\Routing\Controller;
use Meilunzhi\Admin\Contracts\Service;
use Meilunzhi\Admin\Traits\HasResourceRoutes;

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
