<?php


namespace Jmhc\Admin\Services\System;

use Jmhc\Admin\Service;

class ConfigService extends Service
{

    const DISABLE_DELETE = ['name', 'logo']; //禁止删除

    protected function rules(array $data, $id): array
    {
        return [
            'group' => 'bail|required|max:15',
            'title' => 'bail|required|max:50',
            'name' => 'bail|required|max:50',
            'type' => 'bail|required|in:string,text,editor,switch,image,images',
        ];
    }

    protected function message(): array
    {
        return [
            'group.required' => '配置组不能为空',
            'group.max' => '配置组不能超过15个字符',
            'title.required' => '配置名称不能为空',
            'title.max' => '配置名称不能超过50个字符',
            'name.required' => '标识符不能为空',
            'name.max' => '标识符不能超过50个字符',
            'type.required' => '配置类型不能为空',
            'type.in' => '配置类型只能为字符串,文本,编辑器,开关,图片',
        ];
    }

    public function index()
    {
        $configs = $this->repository->all()->toArray();
        $regroupConfigs = [];

        foreach ($configs as $config) {
            if (!array_key_exists($config['group'], $regroupConfigs)) {
                $regroupConfigs[$config['group']] = [$config];
            } else {
                $regroupConfigs[$config['group']][] = $config;
            }
        }
        return $this->response->success($regroupConfigs);
    }

    /**
     * 保存
     * @return mixed
     */
    public function store()
    {
        $formData = $this->request->all();
        if (!$this->validate($formData)) {
            return $this->response->error($this->errorMsg);
        }
        if ($this->repository->where('name', $formData['name'])->exists()) {
            return $this->response->error('该标识符已存在！');
        }
        $model = $this->repository->store($formData);
        if ($model) {
            return $this->response->success(['id' => $model->id]);
        } else {
            return $this->response->error();
        }
    }

    /**
     * 更新一个组
     */
    public function updateGroup()
    {
        $configs = $this->request->all();
        $names = array_keys($configs);
        $all = $this->repository->whereIn('name', $names)->get();
        foreach ($all as $item) {
            $item->value = $configs[$item->name];
            $item->save();
        }
        return $this->response->success();
    }

    /**
     * 获取站点配置
     * @return mixed
     */
    public function getWebsiteConfig()
    {
        return $this->response->collection($this->repository->where('group', 'website')->pluck('value', 'name'));
    }

    /**
     * 删除前置 TODO 配置删除功能待完成
     * @param int $id
     * @return bool
     */
    protected function beforeDestroy(int $id): bool
    {
        $config = $this->repository->find($id);
        if (in_array($config->name, self::DISABLE_DELETE)) {
            $this->setError('该配置不允许删除');
            return false;
        }
        return true;
    }


}