<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_admin_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('admin_id')->comment('管理员ID');
            $table->string('name', 20)->comment('姓名');
            $table->string('title', 255)->default('')->comment('标题');
            $table->unsignedInteger('ip')->default(0)->comment('IP');
            $table->text('content')->nullable()->comment('操作内容');
            $table->timestamps();
        });

        Schema::create('app_admin_users', function (Blueprint $table) {
            $table->unsignedInteger('id');
            $table->string('username', 50)->comment('用户名');
            $table->string('name', 15)->comment('姓名');
            $table->char('avatar', 128)->default('')->comment('头像');
            $table->string('password')->comment('密码');
            $table->timestamps();
            $table->primary('id');
            $table->unique('username');
        });

        Schema::create('app_albums', function (Blueprint $table) {
            $table->unsignedInteger('id');
            $table->string('name', 15)->comment('相册名称');
            $table->string('cover_image', 255)->comment('封面图');
            $table->unsignedInteger('weigh')->default(1)->comment('权重');
            $table->timestamps();
            $table->softDeletes();
            $table->primary('id');
        });

        Schema::create('app_attachments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('album_id')->default(0)->comment('相册ID');
            $table->string('name', 255)->default('')->comment('附件名称');
            $table->integer('admin_id')->default(0)->comment('上传人ID');
            $table->string('path', 255)->default('')->comment('图片存储路径');
            $table->string('mime_type', 80)->default('')->comment('mime-type');
            $table->integer('size')->default(0)->comment('图片字节大小');
            $table->timestamps();
            $table->index('album_id');
        });

        Schema::create('app_configs', function (Blueprint $table) {
            $table->unsignedInteger('id');
            $table->string('group', 25)->comment('组名称');
            $table->string('title', 50)->comment('标题');
            $table->string('name', 50)->comment('配置名称');
            $table->string('type', 10)->comment('配置字段类型:string,text,editor,switch,');
            $table->text('value')->nullable()->comment('配置值内容');
            $table->string('rule', 255)->default('')->comment('验证规则');
            $table->string('extend', 255)->default('')->comment('扩展数据');
            $table->string('tips', 255)->default('')->comment('提示说明');
            $table->timestamps();
            $table->primary('id');
            $table->index('group');
        });

        Schema::create('app_dictionary', function (Blueprint $table) {
            $table->unsignedInteger('id');
            $table->string('title', 15)->comment('字典名称');
            $table->string('name', 15)->comment('字典标识名');
            $table->string('describe', 255)->default('')->comment('字典描述');
            $table->longText('value')->comment('字段内容，键值对');
            $table->timestamps();
            $table->primary('id');
        });

        Schema::create('app_model_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->primary(['permission_id', 'model_id', 'model_type']);
            $table->index(['model_id', 'model_type']);
        });

        Schema::create('app_model_has_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->primary(['role_id', 'model_id', 'model_type']);
            $table->index(['model_id', 'model_type']);
        });

        Schema::create('app_permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 50)->comment('权限标题');
            $table->string('icon', 255)->default('')->comment('icon');
            $table->unsignedInteger('pid')->default(0)->comment('父级id');
            $table->string('name', 255)->comment('规则');
            $table->string('component_path', 255)->default('')->comment('组件路径');
            $table->string('view_route_name', 50)->default('')->comment('视图路由名称');
            $table->string('view_route_path', 255)->default('')->comment('视图路由路径');
            $table->string('redirect_path', 80)->default('')->comment('默认跳转路径');
            $table->string('guard_name')->comment('guard名称');
            $table->tinyInteger('is_menu')->default(0)->comment('是否是菜单');
            $table->tinyInteger('is_hidden')->default(0)->comment('是否隐藏:0=否,1=是');
            $table->unsignedInteger('weigh')->default(100);
            $table->timestamps();
            $table->unique(['guard_name', 'name']);
        });

        Schema::create('app_roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('parent_id')->default(0)->comment('父级id');
            $table->string('name')->comment('角色名称');
            $table->string('guard_name')->comment('guard名称');
            $table->timestamps();
            $table->primary('id');
        });

        Schema::create('app_role_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');
            $table->primary(['permission_id', 'role_id']);
            $table->index('role_id');
        });

        // 添加外键约束
        Schema::table('app_model_has_permissions', function (Blueprint $table) {
            $table->foreign('permission_id')->references('id')->on('app_permissions')->onDelete('cascade');
        });

        Schema::table('app_model_has_roles', function (Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('app_roles')->onDelete('cascade');
        });

        Schema::table('app_role_has_permissions', function (Blueprint $table) {
            $table->foreign('permission_id')->references('id')->on('app_permissions')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('app_roles')->onDelete('cascade');
        });

        // 插入初始数据
        $this->seedData();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_role_has_permissions');
        Schema::dropIfExists('app_model_has_roles');
        Schema::dropIfExists('app_model_has_permissions');
        Schema::dropIfExists('app_permissions');
        Schema::dropIfExists('app_roles');
        Schema::dropIfExists('app_dictionary');
        Schema::dropIfExists('app_configs');
        Schema::dropIfExists('app_attachments');
        Schema::dropIfExists('app_albums');
        Schema::dropIfExists('app_admin_users');
        Schema::dropIfExists('app_admin_log');
    }

    /**
     * 插入初始数据
     */
    protected function seedData()
    {
        // 插入管理员用户
        DB::table('app_admin_users')->insert([
            'id' => 1,
            'username' => 'admin',
            'name' => '超级管理员',
            'avatar' => '',
            'password' => '$2y$10$4KWpL6/..AMiyoPwVfrMGudPH1Ud4BENlG/koHNypMEqqUQZyKkse',
            'created_at' => '2019-09-09 10:09:51',
            'updated_at' => '2020-03-23 11:05:44',
        ]);

        // 插入系统配置
        DB::table('app_configs')->insert([
            ['id' => 1, 'group' => 'website', 'title' => '站点名称', 'name' => 'name', 'type' => 'string', 'value' => 'LaravelVueAdmin', 'rule' => 'required', 'extend' => '', 'tips' => '', 'created_at' => '2020-03-16 05:36:41', 'updated_at' => '2020-06-16 00:45:17'],
            ['id' => 2, 'group' => 'website', 'title' => 'logo', 'name' => 'logo', 'type' => 'image', 'value' => '', 'rule' => 'required', 'extend' => '', 'tips' => '', 'created_at' => '2020-03-16 06:56:22', 'updated_at' => '2020-06-16 00:44:10'],
        ]);

        // 插入字典
        DB::table('app_dictionary')->insert([
            'id' => 1,
            'title' => '系统配置组',
            'name' => 'config_group',
            'describe' => '',
            'value' => '{"website":"站点设置"}',
            'created_at' => '2020-03-16 03:29:07',
            'updated_at' => '2020-03-16 03:29:07',
        ]);

        // 插入角色
        DB::table('app_roles')->insert([
            'id' => 1,
            'parent_id' => 0,
            'name' => 'admin',
            'guard_name' => 'admin',
            'created_at' => '2021-01-07 02:43:09',
            'updated_at' => '2021-01-07 02:43:09',
        ]);

        // 插入权限
        DB::table('app_permissions')->insert([
            ['id' => 1, 'title' => '控制面板', 'icon' => 'dashboard', 'pid' => 0, 'name' => 'dashboard', 'component_path' => '', 'view_route_name' => '', 'view_route_path' => '/', 'redirect_path' => '/dashboard', 'guard_name' => 'admin', 'is_menu' => 1, 'is_hidden' => 0, 'weigh' => 1000, 'created_at' => '2019-09-29 00:57:13', 'updated_at' => '2020-03-18 09:44:02'],
            ['id' => 16, 'title' => '系统管理', 'icon' => 'system', 'pid' => 0, 'name' => 'system', 'component_path' => '', 'view_route_name' => '', 'view_route_path' => '/system', 'redirect_path' => '', 'guard_name' => 'admin', 'is_menu' => 1, 'is_hidden' => 0, 'weigh' => 100, 'created_at' => '2020-03-20 09:20:57', 'updated_at' => '2020-03-20 09:20:57'],
            ['id' => 18, 'title' => '字典设置', 'icon' => 'dictionary', 'pid' => 16, 'name' => 'dictionary', 'component_path' => '/system/dictionary', 'view_route_name' => 'Dictionary', 'view_route_path' => 'dictionary', 'redirect_path' => '', 'guard_name' => 'admin', 'is_menu' => 1, 'is_hidden' => 0, 'weigh' => 18, 'created_at' => '2020-03-19 09:43:56', 'updated_at' => '2020-03-19 09:43:56'],
            ['id' => 20, 'title' => '权限管理', 'icon' => 'peoples', 'pid' => 0, 'name' => 'auth', 'component_path' => '', 'view_route_name' => '', 'view_route_path' => '/auth', 'redirect_path' => '', 'guard_name' => 'admin', 'is_menu' => 1, 'is_hidden' => 0, 'weigh' => 80, 'created_at' => '2020-03-19 10:58:25', 'updated_at' => '2020-03-19 10:58:25'],
            ['id' => 23, 'title' => '角色管理', 'icon' => 'user', 'pid' => 20, 'name' => 'roles', 'component_path' => '/auth/roles', 'view_route_name' => 'Roles', 'view_route_path' => 'roles', 'redirect_path' => '', 'guard_name' => 'admin', 'is_menu' => 1, 'is_hidden' => 0, 'weigh' => 23, 'created_at' => '2020-03-19 10:49:18', 'updated_at' => '2020-03-19 10:49:18'],
            ['id' => 24, 'title' => '菜单管理', 'icon' => 'tree', 'pid' => 20, 'name' => 'permissions', 'component_path' => '/auth/permissions', 'view_route_name' => 'Permissions', 'view_route_path' => 'permissions', 'redirect_path' => '', 'guard_name' => 'admin', 'is_menu' => 1, 'is_hidden' => 0, 'weigh' => 24, 'created_at' => '2020-03-19 10:53:43', 'updated_at' => '2020-03-19 10:53:43'],
            ['id' => 29, 'title' => '系统配置', 'icon' => 'system', 'pid' => 16, 'name' => 'configs', 'component_path' => '/system/configs', 'view_route_name' => 'Configs', 'view_route_path' => 'configs', 'redirect_path' => '', 'guard_name' => 'admin', 'is_menu' => 1, 'is_hidden' => 0, 'weigh' => 29, 'created_at' => '2020-03-19 11:02:29', 'updated_at' => '2020-03-19 11:02:29'],
            ['id' => 30, 'title' => '相册管理', 'icon' => 'album', 'pid' => 16, 'name' => 'albums', 'component_path' => '/system/albums', 'view_route_name' => 'Albums', 'view_route_path' => 'albums', 'redirect_path' => '', 'guard_name' => 'admin', 'is_menu' => 1, 'is_hidden' => 0, 'weigh' => 30, 'created_at' => '2020-03-19 11:02:46', 'updated_at' => '2020-03-19 11:02:46'],
            ['id' => 31, 'title' => '查看', 'icon' => 'list', 'pid' => 30, 'name' => 'albums.show', 'component_path' => '', 'view_route_name' => '', 'view_route_path' => '', 'redirect_path' => '', 'guard_name' => 'admin', 'is_menu' => 0, 'is_hidden' => 0, 'weigh' => 31, 'created_at' => '2020-03-19 11:02:46', 'updated_at' => '2020-03-24 02:49:42'],
            ['id' => 32, 'title' => '附件管理', 'icon' => 'file-white', 'pid' => 16, 'name' => 'attachments', 'component_path' => '/system/attachments', 'view_route_name' => 'Attachments', 'view_route_path' => 'attachments', 'redirect_path' => '', 'guard_name' => 'admin', 'is_menu' => 1, 'is_hidden' => 0, 'weigh' => 32, 'created_at' => '2020-03-19 11:08:37', 'updated_at' => '2020-03-19 11:08:37'],
            ['id' => 34, 'title' => '控制面板', 'icon' => 'dashboard', 'pid' => 1, 'name' => 'dashboard.index', 'component_path' => '/dashboard/index', 'view_route_name' => 'Dashboard', 'view_route_path' => 'dashboard', 'redirect_path' => '', 'guard_name' => 'admin', 'is_menu' => 1, 'is_hidden' => 0, 'weigh' => 34, 'created_at' => '2019-09-29 00:57:13', 'updated_at' => '2020-03-18 09:48:20'],
            ['id' => 36, 'title' => '操作日志', 'icon' => 'list', 'pid' => 16, 'name' => 'admin-log', 'component_path' => '/system/admin-log', 'view_route_name' => 'AdminLog', 'view_route_path' => 'admin-log', 'redirect_path' => '', 'guard_name' => 'admin', 'is_menu' => 1, 'is_hidden' => 1, 'weigh' => 100, 'created_at' => '2020-03-20 09:20:57', 'updated_at' => '2020-06-16 01:06:13'],
            ['id' => 52, 'title' => '用户管理', 'icon' => 'user1', 'pid' => 20, 'name' => 'admin-users', 'component_path' => '/auth/admin-users', 'view_route_name' => 'AdminUsers', 'view_route_path' => 'admin-users', 'redirect_path' => '', 'guard_name' => 'admin', 'is_menu' => 1, 'is_hidden' => 0, 'weigh' => 52, 'created_at' => '2020-03-19 09:51:34', 'updated_at' => '2020-03-19 09:51:34'],
            ['id' => 100, 'title' => '相册图片列表', 'icon' => '', 'pid' => 16, 'name' => 'album-detail', 'component_path' => '/system/album-detail', 'view_route_name' => 'AlbumDetail', 'view_route_path' => 'album-detail', 'redirect_path' => '', 'guard_name' => 'admin', 'is_menu' => 1, 'is_hidden' => 1, 'weigh' => 100, 'created_at' => '2020-03-24 02:51:00', 'updated_at' => '2020-03-24 02:58:59'],
        ]);

        // 插入 model_has_roles (管理员关联角色)
        DB::table('app_model_has_roles')->insert([
            'role_id' => 1,
            'model_type' => 'Meilunzhi\\Admin\\Models\\Auth\\AdminUser',
            'model_id' => 1,
        ]);
    }
}
