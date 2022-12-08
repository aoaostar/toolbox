set -e

lastest_url=https://api.github.com/repos/aoaostar/toolbox/releases/latest

echo "正在获取下载地址"

download_url=$(curl -fsSL "$lastest_url" | grep "browser_download_url.*full" | cut -d '"' -f 4)

echo "获取下载地址成功[$download_url]"

curl -o toolbox-full.zip $download_url

unzip -q toolbox-full.zip -d ./www

\cp -rf ./www/docker/. ./

mv -f ./.env.docker ./www/.env.example

chown -R www-data:www-data ./www

rm -rf toolbox-full.zip ./www/docker

echo "下载完成"

echo "使用 docker-compose up -d 命令启动"