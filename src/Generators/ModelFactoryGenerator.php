<?php

namespace Helilabs\Capital\Generators;

use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;

class ModelFactoryGenerator extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '
                            make:capital:factory
                            {name}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Capital Model Factory';

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    public function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\ModelFactories';
    }

    public function getStub()
    {
        return __DIR__. '/stubs/ModelFactory.stub';
    }
}
