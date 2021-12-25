![toolbox](https://socialify.git.ci/aoaostar/toolbox/image?description=1&forks=1&issues=1&logo=https%3A%2F%2Fraw.githubusercontent.com%2Faoaostar%2Ftoolbox%2Fmaster%2Fpublic%2Fstatic%2Fimages%2Flogo.png&name=1&owner=1&pattern=Floating%20Cogs&pulls=1&stargazers=1&theme=Light)


### 🎉What's this？
这是一款`在线工具箱`程序，您可以通过安装扩展增强她的功能

### 🍑演示地址

* <https://tool.aoaostar.com>


### 🍹演示图

![](docs/images/view_1.png)
## 🎑说明
> 严禁将用于非法用途

### 🎊环境要求

* `PHP` >= 7.1
* `MySQL` >= 5.6
* `fileinfo`扩展
* 使用`Redis`缓存需安装`Redis`扩展
* 去除禁用函数`proc_open`、`putenv`、`shell_exec`、`proc_get_status`(必须是命令行的PHP版本，你装了多个PHP版本，命令行版本的PHP和你的网站配置的PHP可能不是同一个)

### 🚠部署

* 下载源代码
* 设置运行目录为`public`
* 关闭防跨站（`open_basedir`）
* 设置伪静态
* 安装依赖
    + 配置阿里镜像源
    ```
    composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/
    ```
    + 升级compose
    ```
    composer self-update
    ```
    + 安装依赖
    ```
    composer install --no-dev
    ```
* 设置目录权限
    + 一般是默认允许的（如有无法上传、无法打开页面或其他未知问题可以设置一下目录权限）
    + `Apache`的所属组为`www-data`，那么就请修改`www`为`www-data`
    
    ```shell script
    chmod -R 755 *
    chown -R www:www *
    ```
* 打开`你的域名/install`

#### 🍰伪静态

* Nginx
```
location / {
	if (!-e $request_filename){
		rewrite  ^(.*)$  /index.php?s=$1  last;   break;
	}
}
```
* Apache
```
<IfModule mod_rewrite.c>
  Options +FollowSymlinks -Multiviews
  RewriteEngine On

  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteRule ^(.*)$ index.php/$1 [QSA,PT,L]
</IfModule>
```
#### 🍓鸣谢
* vue
* thinkphp
* daisyui
* tailwindcss
* layui
* layuimini
