<?php

namespace Hitocean\CrudGenerator\Generators;

use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;
use Hitocean\CrudGenerator\Generators\FileConfigs\ControllerTestConfig;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpFile;

class ControllerTestGenerator extends FileGenerator
{
    /**
     * @param  ControllerTestConfig  $config
     */
    public function create($config): void
    {
        $file = new PhpFile();

        $test_case_import = 'Tests\TestCase';
        $namespace = $file->addNamespace($config->namespace())
            ->addUse($config->model_import)
            ->addUse($test_case_import)
            ->addUse($config->controller_import);

        $class = $namespace->addClass($config->className())
            ->setExtends($test_case_import);

        $this->storeMethod($config, $class);
        $this->updateMethod($config, $class);
        $this->destroyMethod($config, $class);
        $this->indexMethod($config, $class);
        $this->showMethod($config, $class);

        $this->createFile($config->filePath(), $file);

    }

    public function storeMethod(ControllerTestConfig $config, ClassType $class): void
    {
        $method = $class->addMethod('it_store_a_new_model')
            ->setVisibility('public')
            ->addBody('$data = [');

        /** @var ModelAttributeConfig $attr */
        foreach ($config->model_attributes as $attr) {
            $method->addBody("'{$attr->name}' => {$attr->type->fakerFunction()},");
        }

        $method->addBody('];')
            ->addBody("\$this->post(action([{$config->controllerClassName()}::class, 'store']), \$data)->assertOk();")
            ->addBody("\$this->assertDatabaseHas({$config->modelClassName()}::class, [");

        /** @var ModelAttributeConfig $attr */
        foreach ($config->model_attributes as $attr) {
            $method->addBody("'{$attr->name}' => \$data['{$attr->name}'],");
        }

        $method->addBody(']);')
            ->setReturnType('void');

    }

    public function updateMethod(ControllerTestConfig $config, ClassType $class): void
    {
        $method = $class->addMethod('it_updates_a_model')
            ->setVisibility('public')
            ->addBody("\$model = {$config->modelClassName()}::factory()->create();\n")
            ->addBody('$data = [');

        /** @var ModelAttributeConfig $attr */
        foreach ($config->model_attributes as $attr) {
            $method->addBody("'{$attr->name}' => {$attr->type->fakerFunction()},");
        }

        $method->addBody('];')
            ->addBody("\$this->put(action([{$config->controllerClassName()}::class, 'update'], \$model->id), \$data)->assertOk();")
            ->addBody("\$this->assertDatabaseHas({$config->modelClassName()}::class, [")
            ->addBody("'id' => \$model->id,");

        /** @var ModelAttributeConfig $attr */
        foreach ($config->model_attributes as $attr) {
            $method->addBody("'{$attr->name}' => \$data['{$attr->name}'],");
        }

        $method->addBody(']);')
            ->setReturnType('void');

    }

    public function destroyMethod(ControllerTestConfig $config, ClassType $class): void
    {
        $class->addMethod('it_deletes_a_model')
            ->setVisibility('public')
            ->addBody("\$model = {$config->modelClassName()}::factory()->create();\n")
            ->addBody("\$this->delete(action([{$config->controllerClassName()}::class, 'destroy'], \$model->id))->assertOk();")
            ->addBody("\$this->assertDatabaseMissing({$config->modelClassName()}::class, ['id' => \$model->id]);")
            ->setReturnType('void');

    }

    public function indexMethod(ControllerTestConfig $config, ClassType $class): void
    {
        $method = $class->addMethod('it_returns_a_collection_of_models')
            ->setVisibility('public')
            ->addBody("\$models = {$config->modelClassName()}::factory(1)->create();\n")
            ->addBody("\$this->get(action([{$config->controllerClassName()}::class, 'index']))->assertOk()")
            ->addBody('->assertExactJson([')
            ->addBody('[');

        foreach ($config->model_attributes as $attr) {
            $method->addBody("'{$attr->name}' => \$models[0]->{$attr->name},");
        }

        $method->addBody(']]);')
            ->setReturnType('void');

    }

    public function showMethod(ControllerTestConfig $config, ClassType $class): void
    {
        $method = $class->addMethod('it_returns_a_model')
            ->setVisibility('public')
            ->addBody("\$model = {$config->modelClassName()}::factory()->create();\n")
            ->addBody("\$this->get(action([{$config->controllerClassName()}::class, 'show'], \$model->id))->assertOk();")
            ->addBody('$this->assertExactJson([');

        foreach ($config->model_attributes as $attr) {
            $method->addBody("'{$attr->name}' => \$model->{$attr->name},");
        }

        $method->addBody(']);')
            ->setReturnType('void');

    }
}
