<?php

namespace Waad\Repository\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Repository extends Command
{
    const START_TAG_PHP = "<?php\n\n";

    /**
     * Generate migration
     *
     * @param string $table
     * @return void
     */
    public function migration($table)
    {
        try {
            Artisan::call('make:migration create_' . strtolower($table) . '_table');
            $return[] = 'Migration';
        } catch (\Throwable $th) {
        }
    }

    /**
     * Generate model
     *
     * @param string $name
     * @return void
     */
    public function model($name)
    {
        $path = 'app/Models/' . $name . '.php';
        $myfile = fopen($path, "w") or die("Unable to open file!");
        $file = file_get_contents($this->model);
        $text = self::START_TAG_PHP;
        fwrite($myfile, $text);
        $result = str_replace('{{name}}', $name, $file);
        fwrite($myfile, $result);
        fclose($myfile);
    }


    /**
     * Generate controller
     *
     * @param string $name
     * @param string $model
     * @return void
     */
    public function Controller($name, $model)
    {
        if (!$model) {
            $model = "ModelHere";
        }
        $path = 'app/Http/Controllers/' . $name . 'Controller.php';
        $myfile = fopen($path, "w") or die("Unable to open file!");
        $file = file_get_contents($this->controller);
        $text = self::START_TAG_PHP;
        fwrite($myfile, $text);
        $result = str_replace('{{name}}', $model, $file);
        $name_small = preg_split('#([A-Z][^A-Z]*)#', $model, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        $result = str_replace('{{name_small}}', lcfirst($name_small[0]), $file);
        $result = str_replace('{{model}}', $model, $result);
        fwrite($myfile, $result);
        fclose($myfile);
        $this->Request($name);
    }



    /**
     * Generate route
     *
     * @param string $name
     * @return void
     */
    public function route($name)
    {
        $controller = $name . 'Controller';
        $file = "routes/api.php";
        $fc = fopen($file, "r");
        while (!feof($fc)) {
            $buffer = fgets($fc, 4096);
            $lines[] = $buffer;
        }
        fclose($fc);

        $name_small = preg_split('#([A-Z][^A-Z]*)#', $name, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

        $f = fopen($file, "w") or die("couldn't open $file");
        $lineCount = count($lines);
        for ($i = 0; $i < $lineCount - 1; $i++) {
            fwrite($f, $lines[$i]);
        }
        fwrite($f, "Route::resource('" . lcfirst($name_small[0]) . "', '$controller');" . PHP_EOL);
        fwrite($f, $lines[$lineCount - 1]);
        fclose($f);
    }




    /**
     * Generate repository
     *
     * @param string $name
     * @param string $file
     * @return void
     */
    public function Repo($name, $file)
    {
        $myfile = fopen($file, "w") or die("Unable to open file!");
        $file = file_get_contents($this->repository);
        $text = self::START_TAG_PHP;
        fwrite($myfile, $text);
        $result = str_replace('{{name}}', $name, $file);
        fwrite($myfile, $result);
        fclose($myfile);
    }



    /**
     * Generate request
     *
     * @return null
     */
    public function Request($name)
    {
        $this->createLimitRequest();
        $path = 'app/Http/Requests/'.$name;

        if (!is_dir($path)) {
            mkdir($path, 0777);
        }
        $create = $path . '/' . $name . 'Form.php';
        $this->createRequest($create, $name, $name);
    }


    /**
     * generate create request validation
     *
     * @param string $path
     * @param string $name
     * @param string $shortname
     * @return void
     */
    public function createRequest($path, $name, $shortname)
        {
            $myfile = fopen($path, "w") or die("Unable to open file!");
            $file = file_get_contents($this->request);
            $text = self::START_TAG_PHP;
            fwrite($myfile, $text);
            $result = str_replace(['{{name}}', '{{shortname}}'], [$name, $shortname], $file);
            fwrite($myfile, $result);
            fclose($myfile);
        }


    /**
     * generate limit request validation
     *
     * @return void
     */
    public function createLimitRequest()
    {
        $path = 'app/Http/Requests/Pagination.php';
        $create = null;

        if (!is_dir('app/Http/Requests')) {
            mkdir('app/Http/Requests', 0777);
            if (!file_exists($path)) {
                $create = 'ok';
            }
        } else {
            if (!file_exists($path)) {
                $create = 'ok';
            }
        }

        if (!is_null($create)) {
            $myfile = fopen($path, "w") or die("Unable to open file!");
            $file = file_get_contents($this->limitRequest);
            $text = self::START_TAG_PHP;
            fwrite($myfile, $text);
            fwrite($myfile, $file);
            fclose($myfile);
        }
    }
}
