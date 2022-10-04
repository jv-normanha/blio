<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CrudGenerateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:generate {slug} {--api-version=V1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = Str::ucfirst($this->argument('slug'));
        $version = Str::ucfirst($this->option('api-version'));

        $this->call('make:model', ['name' => $name]);
        $this->call('make:custom-controller', [
            'name' => $name . 'Controller',
            'service' => $name . 'Service'
        ]);

//        $this->call('make:resource', ['name' => $name . 'Resource']);
//        $this->call('make:resource', [
//            'name' => 'Collections/' . $name . 'Collection',
//            '--collection' => true
//        ]);

        $this->call('make:filter', ['name' => $name . 'Filter']);
        $this->call('make:custom-resource', [
            'name' => $name . 'Resource'
        ]);
        $this->call('make:custom-collection', [
            'name' => $name . 'Collection',
            'resource' => $name . 'Resource',
            'filter' => $name . 'Filter'
        ]);

        $this->call('make:validate', ['name' => $name . 'Validator']);
        $this->call('make:repository', [
            'name' => $name . 'Repository',
            '--model' => $name,
            '--validator' => $name . 'Validator',
            '--filter' => $name . 'Filter'
        ]);

        $this->call('make:service', [
            'name' => $name . 'Service',
            'repository' => $name . 'Repository',
            'validator' => $name . 'Validator',
            'resource' => $name . 'Resource',
            'collection' => $name . 'Collection',
        ]);

        $this->comment('Estrutura criada com sucesso!');
    }

}
