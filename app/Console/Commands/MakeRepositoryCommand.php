<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;

class MakeRepositoryCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name} {--model=} {--validator=} {--filter=}';

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

        if ($this->option('model'))
            $stub = str_replace(
                'GenericModel',
                $this->option('model'),
                $stub
            );

        if ($this->option('validator')) {
            $stub = str_replace(
                'GenericValidators',
                $this->option('validator'),
                $stub
            );
        }

        if ($this->option('filter')) {
            $stub = str_replace(
                'GenericFilter',
                $this->option('filter'),
                $stub
            );
        }

        $result = str_replace(
            'GenericRepository',
            $this->argument('name'),
            $stub
        );

        $this->info('Repository created successfully');
        return $result;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return  app_path() . '/Console/Commands/Stubs/make-repository.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Repositories';
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
