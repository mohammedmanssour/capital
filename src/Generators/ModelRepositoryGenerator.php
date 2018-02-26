<?php

namespace Helilabs\Capital\Generators;

class ModelRepositoryGenerator extends ModelControllerGenerator
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '
                            make:capital:repository
                            {name}
                            {model}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Repository that is compatible with capital';

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    public function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Repositories';
    }

    public function getStub()
    {
        return __DIR__. '/stubs/ModelsRepository.stub';
    }
}
