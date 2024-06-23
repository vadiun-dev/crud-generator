<?php

namespace Hitocean\CrudGenerator;

use DirectoryIterator;
use Hitocean\CrudGenerator\FileGenerators\Model\FileConfigs\ModelConfig;

class CrudGenerator
{
    /**
     * @return array<ModelConfig>
     */
    public static function handle(): array
    {
        $iterator = new DirectoryIterator(base_path('generators'));
        foreach ($iterator as $json_conf) {
            if (! $json_conf->isDot()) {
                $configData = json_decode(file_get_contents($json_conf->getPath().'/'.$json_conf->getFilename()), true);
                $configs[] = ModelConfigFactory::makeConfig($configData);
            }
        }

        return $configs;
    }
}
