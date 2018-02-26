<?php

namespace Helilabs\Capital\Generators;

use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;

class ModelControllerGenerator extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '
                            make:capital:controller
                            {name}
                            {model}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Controller that is compatible with capital factory';

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    public function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Http\Controllers';
    }

    public function getStub()
    {
        return __DIR__. '/stubs/ModelController.stub';
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceNamespace($stub, $name)
                    ->replaceClassName($stub, $name)
                    ->replaceModel($stub, $this->getModelInput());
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClassName(&$stub, $name)
    {
        $class = str_replace($this->getNamespace($name).'\\', '', $name);

        $stub = str_replace('DummyClass', $class, $stub);

        return $this;
    }


    protected function replaceModel($stub, $name)
    {
        $stub = str_replace(
            ['DummyModels', 'dummyModels', 'DummyModel', 'dummyModel'],
            [ucfirst(str_plural($name)), str_plural($name), ucfirst($name), $name],
            $stub
        );

        return $stub;
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getModelInput()
    {
        return trim($this->argument('model'));
    }
}
