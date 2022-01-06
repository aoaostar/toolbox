<?php
// 应用公共文件
use app\model\User;
use think\facade\Session;

require dirname(__DIR__) . "/plugin/common.php";
function template_path_get(): string
{
    return app()->getRootPath() . config("view.view_dir_name") . '/index/' . config_get('global.template') . DIRECTORY_SEPARATOR;
}

function msg($status = "ok", $message = "success", $data = [])
{
    return json([
        "status" => $status,
        "message" => $message,
        "data" => $data,
    ]);
}

function is_login()
{
    $user = get_user();
    return $user !== null && !empty($user->id);
}

function get_user()
{
    $user = Session::get("user");

    if (!empty($user)) {
        $user = User::where('id', $user->id)->cache(60)->findOrEmpty();
        if (!$user->isEmpty()) {
            return $user;
        }
    }
    return null;
}

function get_username()
{
    return get_user()->login;
}

function is_admin()
{

    $user = get_user();

    $username = config_get('oauth.username');
    return $user !== null && !empty($user->username) && $user->username === $username;
}


function get_master_path($path = '')
{
    $path = trim($path, '\\//');
    $adminPath = trim(config_get('oauth.admin_path'), '\\//');
    if (!empty($path)) {
        $adminPath .= "/$path";
    }
    return "/$adminPath";
}

function reset_opcache()
{
    if (function_exists('opcache_reset')) opcache_reset();
}

function aoaostar_get($url, $headers = [])
{

    $headers = array_merge([
        'User-Agent:Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_8; en-us) AppleWebKit/534.50 (KHTML, like Gecko) Version/5.1 Safari/534.50',
    ], $headers);
    // 创建一个新 cURL 资源
    $curl = curl_init();
    // 设置URL和相应的选项
    // 需要获取的 URL 地址
    curl_setopt($curl, CURLOPT_URL, $url);
    #启用时会将头文件的信息作为数据流输出。
    curl_setopt($curl, CURLOPT_HEADER, false);
    #在尝试连接时等待的秒数。设置为 0，则无限等待。
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
    #允许 cURL 函数执行的最长秒数。
    curl_setopt($curl, CURLOPT_TIMEOUT, 60);
    #关闭ssl
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    #TRUE 将 curl_exec获取的信息以字符串返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    #设置header
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    // 跟踪重定向
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    // 抓取 URL 并把它传递给浏览器
    $res = curl_exec($curl);
    // 关闭 cURL 资源，并且释放系统资源
    if ($res === false) {
        return "CURL Error:" . curl_error($curl);
    }
    curl_close($curl);
    return $res;
}

function aoaostar_post(
    $url,
    $post,
    $headers = []
)
{
    // 初始化
    $headers = array_merge([
        'User-Agent:Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_8; en-us) AppleWebKit/534.50 (KHTML, like Gecko) Version/5.1 Safari/534.50',

    ], $headers);
    // 创建一个新 cURL 资源
    $curl = curl_init();
    // 设置URL和相应的选项
    // 需要获取的 URL 地址
    curl_setopt($curl, CURLOPT_URL, $url);
    #启用时会将头文件的信息作为数据流输出。
    curl_setopt($curl, CURLOPT_HEADER, false);
    #设置头部信息
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    #在尝试连接时等待的秒数。设置为 0，则无限等待。
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
    #允许 cURL 函数执行的最长秒数。
    curl_setopt($curl, CURLOPT_TIMEOUT, 60);
    #设置请求信息
    //设置post方式提交
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
    #关闭ssl
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    #TRUE 将 curl_exec获取的信息以字符串返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    // 抓取 URL 并把它传递给浏览器
    $return = curl_exec($curl);
    if ($return === false) {
        return 'CURL Error:' . curl_error($curl);
    }
    curl_close($curl);
    return $return;
}


function aoaostar_curl($url, $put, $headers = [],
                       $type = 'PUT'
)
{

    $headers = array_merge([
        'User-Agent:Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_8; en-us) AppleWebKit/534.50 (KHTML, like Gecko) Version/5.1 Safari/534.50',
    ], $headers);
    // 创建一个新 cURL 资源
    $curl = curl_init();
    // 设置URL和相应的选项
    // 需要获取的 URL 地址
    curl_setopt($curl, CURLOPT_URL, $url);
    #启用时会将头文件的信息作为数据流输出。
    curl_setopt($curl, CURLOPT_HEADER, false);
    #设置头部信息
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    #在尝试连接时等待的秒数。设置为 0，则无限等待。
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
    #允许 cURL 函数执行的最长秒数。
    curl_setopt($curl, CURLOPT_TIMEOUT, 60);
    #设置请求信息
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $type);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $put); //定义提交的数据
    #关闭ssl
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    #TRUE 将 curl_exec获取的信息以字符串返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    // 抓取 URL 并把它传递给浏览器
    $return = curl_exec($curl);
    if ($return === false) {
        return "CURL Error:" . curl_error($curl);
    }
    curl_close($curl);
    return $return;
}

function unzip($filepath, $filename)
{
    if (!file_exists($filepath)) {
        return false;
    }
    $zip = new ZipArchive;

    if ($zip->open($filepath) === true) {
        $zip->extractTo($filename);
        $zip->close();
        return true;
    }
    return false;
}

//多维转一维数组
function multi2one($data, $dir = '', $step = '')
{
    $list = [];
    foreach ($data as $k => $v) {
        if (is_array($v)) {
            $list = array_merge($list, multi2one($v, $dir . $step . $k, $step));
        } else {
            $list[] = ltrim($dir . $step . $v, '\\/');
        }
    }
    return $list;
}

function tree_relative($dir)
{
    if (!is_dir($dir)) {
        return [basename($dir)];
    }
    $arr = [];
    $scandir = scandir($dir);
    foreach ($scandir as $v) {
        if ($v != '.' && $v != '..') {
            if (is_dir("$dir/$v")) {
                $arr[$v] = tree_relative("$dir/$v");
            } else {
                $arr[] = $v;
            }
        }
    }
    return $arr;
}

function copy_dir($src, $target)
{
    if (!is_dir($target)) {
        mkdir($target, 0777, true);
    }
    foreach (glob($src . '/*') as $filename) {
        $targetFilename = $target . '/' . basename($filename);
        if (is_dir($filename)) {
            // 如果是目录，递归合并子目录下的文件。
            copy_dir($filename, $targetFilename);
        } elseif (is_file($filename)) {
            copy($filename, $targetFilename);
        }
    }
}

function del_tree($dir)
{
    if (!file_exists($dir)) {
        return true;
    }
    $files = array_diff(scandir($dir), array('.', '..'));

    foreach ($files as $file) {
        (is_dir("$dir/$file")) ? del_tree("$dir/$file") : unlink("$dir/$file");
    }

    return rmdir($dir);
}


function parse_github_url($url)
{
    $parse_url = parse_url($url);

    $explode = explode('/', $parse_url['path']);
    $explode = array_values(array_filter($explode));
    $github = new stdClass();
    $github->owner = $explode[0];
    $github->repo = $explode[1];
    if (count($explode) <= 2) {
        $github->action = 'tree';
        $github->branch = 'master';
        $github->path = '';
    } else {
        $github->action = $explode[2];
        $github->branch = $explode[3];
        $github->path = implode('/', array_slice($explode, 4));
    }
    return $github;
}

function tree_github($github, $type = 'tree')
{
    $arr = [];
    $get = aoaostar_get("https://api.github.com/repos/$github->owner/$github->repo/contents/$github->path?ref=$github->branch");
    $res = json_decode($get);
    if (empty($res)) {
        throw new Exception('请求失败');
    }
    foreach ($res as $v) {
        if (empty($v->download_url)) {
            $tmp = clone $github;
            $tmp->path = "$tmp->path/$v->name";
            if ($type == 'tree') {
                $arr[$v->name] = tree_github($tmp);
            } else {
                $arr = array_merge($arr, tree_github($tmp, $type));
            }
        } else {
            if ($type == 'tree') {
                $arr[] = $v->name;
            } else {
//                $arr["$github->path/$v->name"] = $v->download_url;
                $arr[$v->path] = $v->download_url;
            }
        }
    }
    return $arr;

}

function config_get($key)
{
    $model = \app\model\Config::getByKey($key);
    if (isset($model->value)) {

        return $model->value;
    }
    $arr = [];
    foreach ($model as $v) {
        $arr[substr($v->key, strlen($key))] = $v->value;
    }
    return $arr;
}

function config_set($key, $value)
{
    $model = \app\model\Config::getByKey($key);
    $model->key = $key;
    $model->value = $value;
    return $model->save();
}

function get_version()
{
    return VERSION;
}

function format_date($timestamp = null)
{
    if ($timestamp === null) {
        $timestamp = time();
    }
    return date('Y-m-d H:i:s', $timestamp);
}

//当前命名空间的包名
function base_space_name($space)
{
    $str_replace = str_replace('\\', '/', $space);
    return basename($str_replace);
}


if (!function_exists('str_starts_with')) {
    function str_starts_with($str, $start)
    {
        return (@substr_compare($str, $start, 0, strlen($start)) == 0);
    }
}
if (!function_exists('str_ends_with')) {
    function str_ends_with(string $haystack, string $needle): bool
    {
        $needle_len = strlen($needle);
        return ($needle_len === 0 || 0 === substr_compare($haystack, $needle, -$needle_len));
    }
}