<?php

namespace Hitocean\CrudGenerator;

use Illuminate\Support\Collection;

class ModelConfig
{
    public function __construct(
        public string $modelName,
        public string $folder,
        /** @var Collection<ModelAttributeConfig> */
        public Collection $attributes,
        public string $tableName,
        public bool $has_abm
    ) {
    }

}
