<?php


namespace Jmhc\Admin\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ResetAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:password {account=admin} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '设置后台用户账号的密码';


    public function handle()
    {
        $account = $this->argument('account');
        $password = $this->argument('password');
        $adminExists = DB::table('admin_users')->where('username', $account)->exists();
        if ($adminExists) {
            $password = Hash::make($password);
            DB::table('admin_users')->where('username', 'admin')
                ->update(['password' => $password]);
            $this->info('密码更新成功');
        } else {
            $this->info('该账号不存在');
        }

    }
}