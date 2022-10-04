<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;

class MakeCustomCollectionCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:custom-collection {name} {filter} {resource}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $output = 'Base struct created successfully.';

    /**
     * Replace the class name for the given stub. asd
     *
     * @param $stub
     * @param $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $stub = parent::replaceClass($stub, $name);

        $stub = str_replace(
            'GenericResource',
            $this->argument('resource'),
            $stub
        );

        $stub = str_replace(
            'GenericFilter',
            $this->argument('filter'),
            $stub
        );

        $result = str_replace(
            'GenericCollection',
            $this->argument('name'),
            $stub
        );

        $this->info('Custom Collection created successfully');
        return $result;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return  app_path() . '/Console/Commands/Stubs/make-collection.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Http\Resources\Collections';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the contract.'],
        ];
    }
}
