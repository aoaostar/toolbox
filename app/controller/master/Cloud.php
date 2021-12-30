<?php


namespace app\controller\master;


use app\controller\Base;
use think\facade\Cache;
use think\facade\Request;

class Cloud extends Base
{
    private $PLUGIN_API = '';
    private $PLUGINS_API = '';
    private $CATEGORIES_API = '';
    private $RELEASES_API = '';
    private $HEADERS = [];

    public function initialize()
    {
        $api = base64_decode('aHR0cHM6Ly90b29sLWNsb3VkLmFvYW9zdGFyLmNvbQ==');
        $this->PLUGIN_API = "$api/open/plugin";
        $this->PLUGINS_API = "$api/open/plugins";
        $this->CATEGORIES_API = "$api/open/categories";
        $this->RELEASES_API = "$api/open/releases";
        $this->HEADERS = [
            'referer:' . Request::url(true)
        ];
    }

    public function categories()
    {
        $data = Cache::get(__METHOD__);
        if (!empty($data)) {
            return msg("ok", "success", $data);
        }

        $res = aoaostar_get($this->CATEGORIES_API, $this->HEADERS);
        $json = json_decode($res);
        if (empty($json) || empty($json->data)) {
            if (!empty($json->message)) {
                return msg("error", $json->message);
            }
            return msg("error", '连接云中心失败，请检查网络连通性是否正常');
        }
        Cache::set(__METHOD__, $json->data);
        return msg("ok", "success", $json->data);
    }

    public function plugins()
    {
        $data = Cache::get(__METHOD__);
        if (!empty($data)) {
            return msg("ok", "success", $data);
        }
        $res = aoaostar_get($this->PLUGINS_API, $this->HEADERS);
        $json = json_decode($res);
        if (empty($json) || empty($json->data)) {
            if (!empty($json->message)) {
                return msg("error", $json->message);
            }
            return msg("error", '连接云中心失败，请检查网络连通性是否正常');
        }
        $classes = [];
        foreach ($json->data->items as $v) {
            $classes[] = $v->class;
        }
        $plugins = \app\model\Plugin::field('class,version')->select();

        foreach ($json->data->items as $v) {
            $v->current_version = null;
            $first = $plugins->where('class', $v->class)->first();
            if (!empty($first)) {
                $v->current_version = $first->version;
            }
        }
        Cache::set(__METHOD__, $json->data);
        return msg("ok", "success", $json->data);
    }

    public function releases()
    {
        $data = Cache::get(__METHOD__);
        if (!empty($data)) {
            return msg("ok", "success", $data);
        }
        $query = http_build_query([
            'page' => 1,
            'limit' => 12,
        ]);
        $res = aoaostar_get("$this->RELEASES_API?$query", $this->HEADERS);
        $json = json_decode($res);
        if (empty($json) || empty($json->data)) {
            if (!empty($json->message)) {
                return msg("error", $json->message);
            }
            return msg("error", '连接云中心失败，请检查网络连通性是否正常');
        }
        Cache::set(__METHOD__, $json->data);
        return msg("ok", "success", $json->data);
    }

    private function get_plugin_info($id)
    {
        $query = http_build_query([
            'id' => $id,
        ]);
        $res = aoaostar_get("$this->PLUGIN_API?$query", $this->HEADERS);
        $json = json_decode($res);

        if (empty($json) || empty($json->data)) {
            if (!empty($json->message)) {
                throw new \Exception($json->message);
            }
            throw new \Exception('"连接云中心失败，请检查网络连通性是否正常"');
        }
        return $json;
    }

    public function plugin_get()
    {
        $id = Request::param('id');
        if (empty($id)) {
            return msg("error", 'id不得为空');
        }
        $data = Cache::get(__METHOD__ . "_$id");
        if (!empty($data)) {
            return msg("ok", "success", $data);
        }
        try {
            $json = $this->get_plugin_info($id);
        } catch (\Exception $e) {

            return msg("error", $e->getMessage());
        }

        $plugin = \app\model\Plugin::field('class,version')->where('class', $json->data->class)->findOrEmpty();
        $json->data->current_version = null;
        if (!$plugin->isEmpty()) {
            $json->data->current_version = $plugin->version;
        }

        Cache::set(__METHOD__ . "_$id", $json->data);
        return msg("ok", "success", $json->data);
    }

    public function plugin_install()
    {
        $id = Request::param('id');
        if (empty($id)) {
            return msg("error", 'id不得为空');
        }
        try {
            $json = $this->get_plugin_info($id);
        } catch (\Exception $e) {

            return msg("error", $e->getMessage());
        }

        $plugin = new \app\lib\Plugin();

        $zipFilepath = $plugin->getZipFilepath();
        $file = $json->data->file;
        $downloadUrl = config_get('cloud.mirror');
        //https://github.com/{owner}/{repo}/raw/{branch}/{path}
        if (empty($downloadUrl)) {
            $downloadUrl = 'https://github.com/{owner}/{repo}/raw/{branch}/{path}';
        }
        $arr = array_keys(get_object_vars($file));

        foreach ($arr as $v) {
            $downloadUrl = str_ireplace("{{$v}}", $file->$v, $downloadUrl);
        }
        $data = aoaostar_get($downloadUrl);
        if (empty($data) || str_starts_with($data, 'CURL Error:')) {
            return msg("error", "下载插件包失败", $data);
        }
        file_put_contents($zipFilepath, $data);

        return $plugin->install();
    }

}