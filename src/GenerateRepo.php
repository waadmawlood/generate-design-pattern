<?php

namespace Waad\RepoMedia;

use Waad\RepoMedia\Commands\Repository;
use Illuminate\Support\Str;

class GenerateRepo extends Repository
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'repo:model
                        {name : Repo name}
                        {--c : Create Controller With Repository}
                        {--m : Create Model With Repository}
                        {--r : Create apiResource Route}
                        {--force : Allows to override existing Repository}
                        {--model= : Insert model in controller}
                        {--table= : Insert table name to create migration}
                        ';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create repository';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $controller = __DIR__ . '/stubs/controller.stub';
    protected $model = __DIR__ . '/stubs/model.stub';
    protected $repository = __DIR__ . '/stubs/repository.stub';
    protected $request = __DIR__ . '/stubs/request.stub';
    protected $limitRequest = __DIR__ . '/stubs/limitRequest.stub';

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
        $path = 'app/Http/Repositories';
        $name = $this->argument('name');

        /**
         * conver model name to plural.
         *
         * @return string
         */
        $table = Str::plural($name);

        /**
         * make migration.
         *
         * @return string
         */
        $return[] = $this->migration($table);

        $file = $path . '/' . $name . 'Repository.php';

        /**
         * check if path exists.
         * create path
         */
        if (!is_dir($path)) {
            mkdir($path, 0777);
        }

        /**
         * force create.
         *
         * @return string
         */
        if (!$this->option('force') and file_exists($file)) {
            return $this->comment($name . 'Repository exists. Use --force to override');
        }

        /**
         * conver model name to plural.
         * create model
         *
         * @return string
         */
        if ($this->option('model')) {
            $model = $this->option('model');
        } else if ($this->option('m')) {
            $model = ucfirst($name);
            $this->model($name);
            $return[] = 'Model';
        } else {
            $model = null;
        }


        /**
         *create controller
         *
         * @return string
         */
        if ($this->option('c')) {
            $this->Controller($name, $model);
            $return[] = 'Controller';
        }


        /**
         *Add Api Route
         *
         * @return string
         */
        if ($this->option('r')) {
            $this->route($name);
            $return[] = 'Route';
        }


        /**
         * Create Repository
         *
         * @return string
         */
        $this->Repo($name, $file);

        $return[] = 'Repostitory';

        /**
         * Convert 'return' To string
         *
         * @return string
         */
        $data = implode(", ", $return);

        /**
         * response message
         *
         * @return string
         */
        return $this->comment($name . ' (' . $data . ') Created.');
    }
}
