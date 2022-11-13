<?php

namespace Waad\Repository;

use Waad\Repository\Commands\Validation;

class GenerateValidation extends Validation
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'repo:validation
                            {model : Model Name}
                            ';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto generate validation';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    protected $requestValidation = __DIR__ . '/stubs/request.stub';

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
        /**
         * Model Name.
         */
        $model = $this->argument('model');

        /**
         * Model full path.
         *
         * @return string
         */
        $full_model = $this->model($model);

        /**
         * get table name from model.
         *
         * @return string
         */
        try {
            $table = $this->tableName($full_model);
        } catch (\Throwable $th) {
            return $this->comment('[' . $model . '] Model Not Found.');
        }

        /**
         * Request file path.
         */
        $path = 'app/Http/Requests';

        /**
         * create path when not exists.
         */
        if (!is_dir($path)) {
            mkdir($path, 0777);
        }

        /**
         * create validations.
         *
         * @return string
         */
        $validation = $this->generateValidation($table, $model);

        /**
         * create request file.
         * update request file.
         */
        $create = $path . '/' . $model . '/' . $model . 'Form.php';
        $this->createRequest($create, $model . 'Form', $validation, $model);

        /**
         * return message.
         */
        return $this->comment('app\\Http\\Requests\\' . $model . '\\'. $model . 'Form >> Validated.');
    }
}
