<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;

class MakeCustomControllerCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:custom-controller {name} {service} {--api-version=\V1}';

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
            'GenericService',
            $this->argument('service'),
            $stub
        );

        $result = str_replace(
            'GenericController',
            $this->argument('name'),
            $stub
        );
        $this->info('Custom Controller created successfully');
        return $result;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return  app_path() . '/Console/Commands/Stubs/make-controller.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Http\Controllers' . $this->option('api-version');
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
