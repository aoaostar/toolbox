<?php


namespace app\controller\master;


use app\controller\Base;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use think\Collection;
use think\facade\Cache;
use think\facade\Request;
use think\response\Json;
use Throwable;

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

    public function categories(): Json
    {
        try {

            $json = $this->get_remote_data(__METHOD__, $this->CATEGORIES_API);

            return success($json->data);

        } catch (Exception $e) {

            return error($e->getMessage());
        }
    }

    public function plugins(): Json
    {

        try {
            $json = $this->get_remote_data(__METHOD__, $this->PLUGINS_API);

        } catch (Exception $e) {

            return error($e->getMessage());
        }

        $plugins = \app\model\Plugin::field('id,class,version')->select();

        foreach ($json->data->items as &$v) {
            $v->current_version = null;
            $first = $plugins->where('class', $v->class)->first();
            if (!empty($first)) {
                $v->current_version = $first->version;
                $v->current_plugin_id = $first->id;
            }
            $v = (array)$v;
        }
        unset($v);
        $collection = new Collection($json->data->items);

        $params = request()->param();

        if (!empty($params['need_update']) && $params['need_update']) {
            $collection = $collection->filter(function ($v) {
                return !empty($v['current_version']) && $v['current_version'] !== $v['version'];
            });
        }

        foreach (['category_id' => '=',] as $k => $v) {
            if (!empty($params[$k])) {
                $collection = $collection->where($k, $v, $params[$k]);
            }
        }

        $keywords = array_filter(['title', 'class', 'tag'], function ($v) use ($params) {
            return !empty($params[$v]);
        });

        if (!empty($keywords)) {
            $collection = $collection->filter(function ($item) use ($keywords, $params) {
                foreach ($keywords as $k) {
                    if (str_contains($item[$k], $params[$k])) {
                        return true;
                    }
                }
                return false;
            });
        }

        $json->data->total = $collection->count();
        $collection = $collection->order('update_time', 'desc');
        if (!empty($params['page']) && !empty($params['limit'])) {
            $collection = $collection->slice(($params['page'] - 1) * $params['limit'], $params['limit']);
        }
        $json->data->items = array_values($collection->toArray());

        return success($json->data);
    }

    public function releases(): Json
    {

        try {

            $query = http_build_query([
                'page' => 1,
                'limit' => 12,
            ]);
            $json = $this->get_remote_data(__METHOD__, "$this->RELEASES_API?$query");

            return success($json->data);

        } catch (Exception $e) {

            return error($e->getMessage());
        }
    }

    public function plugin_get(): Json
    {
        $id = Request::param('id');

        try {
            $this->validate([
                'id' => $id,
            ], [
                'id' => 'require|number',
            ]);
            $json = $this->get_plugin_info($id);
        } catch (Exception $e) {

            return error($e->getMessage());
        }

        $plugin = \app\model\Plugin::field('class,version')->where('class', $json->data->class)->findOrEmpty();
        $json->data->current_version = null;
        if (!$plugin->isEmpty()) {
            $json->data->current_version = $plugin->version;
        }
        return success($json->data);
    }

    public function plugin_install(): Json
    {
        $id = Request::param('id');

        try {
            $this->validate([
                'id' => $id,
            ], [
                'id' => 'require|number',
            ]);
            $json = $this->get_plugin_info($id);
        } catch (Exception $e) {

            return error($e->getMessage());
        }

        $plugin = new \app\lib\Plugin();

        $zipFilepath = $plugin->getZipFilepath();
        $file = $json->data->file;
        $downloadUrl = config_get('cloud.mirror');

        if (empty($downloadUrl)) {
            $downloadUrl = 'https://github.com/{owner}/{repo}/raw/{branch}/{path}';
        }

        $arr = array_keys(get_object_vars($file));

        foreach ($arr as $v) {
            $downloadUrl = str_ireplace("{$v}", $file->$v, $downloadUrl);
        }

        try {
            $data = aoaostar_get($downloadUrl, [], false);
        } catch (GuzzleException $e) {
            return error($e->getMessage());
        }

        if (empty($data) || str_starts_with($data, 'CURL Error:')) {
            return error("下载插件包失败", $data);
        }

        file_put_contents($zipFilepath, $data);

        return $plugin->install();
    }

    private function get_plugin_info($id)
    {
        $query = http_build_query([
            'id' => $id,
        ]);
        return $this->get_remote_data(__METHOD__ . '__' . $id, "$this->PLUGIN_API?$query");
    }

    /**
     * @throws Throwable
     */
    private function get_remote_data($key, $url)
    {
        return Cache::remember($key, function () use ($url) {
            $json = aoaostar_get($url, $this->HEADERS);
            if (empty($json) || empty($json->data)) {
                if (!empty($json->message)) {
                    throw  new Exception($json->message);
                }
                throw  new Exception('连接云中心失败，请检查网络连通性是否正常');
            }
            return $json;
        }, 3600);
    }

}