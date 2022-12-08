<?php
declare (strict_types=1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;
use ZipArchive;

class PluginLogoFormat extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('plugin:logo_format')
            ->addArgument('space', Argument::REQUIRED, '插件域，例如：aoaostar_com')
            ->setDescription('将指定域的所有插件的Logo处理为规范格式');
    }

    protected function execute(Input $input, Output $output)
    {
        $space = $input->getArgument('space');
        // 指令输出
        $rootPath = app()->getRootPath() . 'plugin/';
        if (!is_dir($rootPath . $space)) {
            $output->writeln("该域不存在:[$space]");
            return;
        }
        $plugins = glob($rootPath . $space . '/*');
        $output->writeln("正在处理:[$space]下的文件");
        foreach ($plugins as $plugin) {
            $logo = $plugin . '/logo.png';
            $image_size = getimagesize($logo);
            if ($image_size[0] !== 200 || $image_size[1] !== 200) {
                switch ($image_size[2]) {
                    case 1:
                        $image = imagecreatefromgif($logo);
                        break;
                    case 2:
                        $image = imagecreatefromjpeg($logo);
                        break;
                    case 3:
                        $image = imagecreatefrompng($logo);
                        break;
                    default:
                        $image = imagecreatefromjpeg($logo);
                }
                $resource = imagescale($image, 200, 200);
                imagealphablending($resource, false);
                imagesavealpha($resource, true);
                imagepng($resource, $logo);
                unlink($plugin . '/logo_test.png');
                imagedestroy($image);
                imagedestroy($resource);
            }
            $output->writeln("处理成功:[$plugin]");
        }

        $output->writeln("该域所有插件都已处理完成:[$space]");
    }
}
