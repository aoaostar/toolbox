<?php
declare (strict_types=1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;
use ZipArchive;

class PluginPackage extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('plugin:package')
            ->addArgument('space', Argument::REQUIRED, '插件域，例如：aoaostar_com')
            ->setDescription('打包指定域的所有插件');
    }

    protected function execute(Input $input, Output $output)
    {
        $space = $input->getArgument('space');
        // 指令输出
        $rootPath = app()->getRootPath() . '/plugin/';
        if (!is_dir($rootPath . $space)) {
            $output->writeln("该域不存在:[$space]");
            return;
        }
        $plugins = glob($rootPath . $space . '/*');
        $output->writeln("正在打包:[$space]下的文件");
        foreach ($plugins as $plugin) {
            $zip = new  ZipArchive();
            $filename = $rootPath . 'output/' . $space . DIRECTORY_SEPARATOR . basename($plugin) . '.zip';
            if (!is_dir(dirname($filename))) {
                mkdir(dirname($filename), 0777, true);
            }
            if ($zip->open($filename, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {

                $tree_relative = tree_relative($plugin);
                $files = multi2one($tree_relative, '', '/');
                foreach ($files as $file) {
                    if ($file !== '.' && $file !== '..') {
                        $zip->addFile($plugin . '/' . $file, $space . '/' . basename($plugin) . '/' . $file);
                    }
                }
            }
            $zip->close();
            $output->writeln("打包成功:[$filename]");
        }

        $output->writeln("该域所有文件都已打包完成:[$space]");
    }
}
