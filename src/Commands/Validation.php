<?php

namespace Waad\Repository\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Validation extends Command
{

    /**
     * Check Laravel verison.
     * return model full path
     *
     * @return string
     */
    public function model($model)
    {
        $full_model = '\App\\Models\\' . $model;
        return $full_model;
    }


    /**
     * get table name using model
     *
     * @return string
     */
    public function tableName($full_model)
    {
        return (new $full_model())->getTable();
    }


    /**
     * generate validations
     * get validations from table
     *
     * @return string
     */
    public function generateValidation($table, $model)
    {
        $columns = DB::select('describe ' . $table . '');
        $blocked = array('created_at', 'updated_at', 'email_verified_at', 'remember_token','deleted_at');
        $images_array = array('image', 'images', 'avatar');
        $files_array = array('file', 'files');
        $result = [];
        $count = count($columns);
        $last = false;
        echo PHP_EOL . PHP_EOL;
        echo "\tprotected \$fillable = [" . PHP_EOL;
        echo "\t\t'id'," . PHP_EOL;

        foreach ($columns as $key => $column) {
            if ($count == $key + 1) {
                $last = true;
            }
            if ($column->Key !== 'PRI' and !in_array($column->Field, $blocked)) {
                $validation = [];
                if ($column->Null == 'YES') {
                    array_push($validation, "nullable");
                } else {
                    array_push($validation, "required");
                }
                if (in_array($column->Field, $images_array)) {
                    array_push($validation, 'mimes:jpg,bmp,png');
                } else if (in_array($column->Field, $files_array)) {
                    array_push($validation, 'file');
                } else if ($column->Field == 'email') {
                    array_push($validation, 'email');
                } else if ($column->Field == 'password') {
                    array_push($validation, 'min:6|max:255');
                } else {
                    array_push($validation, $this->getColumnType($column->Type));
                }

                if ($column->Key == 'UNI') {
                    array_push($validation, "unique:" . Str::plural($table) . "," . $column->Field . "");
                }

                if ($column->Key == 'MUL') {
                    $explode = explode("_", $column->Field);
                    $explode[0] = Str::plural($explode[0]);
                    $implode = implode(",", $explode);
                    $mul = str_replace('_', ',', $implode);
                    $mul_value = 'exists:' . $mul . '';
                    array_push($validation, $mul_value);
                }
                array_push(
                    $result,
                    $column->Field .
                        "=>" .
                        "" . implode("|", $validation) . ""
                );
                echo "\t\t'" . $column->Field . "'," . PHP_EOL;
            }
        }
        echo "\t\t'created_at'," . PHP_EOL;
        echo "\t\t'update_at'," . PHP_EOL;
        echo "\t];" . PHP_EOL . PHP_EOL;
        return str_replace('=>', '" => "', $result);
    }


    /**
     * Create Request File.
     *
     * @return null
     */
    public function createRequest($path, $name, $validations, $shortname)
    {
        $last = '';
        foreach ($validations as $validation) {
            $last .= '
            "' . $validation . '",' . "\n";
        }
        $myfile = fopen($path, "w") or die("Unable to open file!");
        $file = file_get_contents($this->requestValidation);
        $text = "<?php\n\n";
        fwrite($myfile, $text);
        $result = str_replace('{{name}}', $name, $file);
        $result = str_replace('{{shortname}}', $shortname, $result);
        $result = str_replace('//{{validation}}', $last, $result);
        fwrite($myfile, $result);
        fclose($myfile);
    }


    /**
     * get column type.
     *
     * @return string
     */
    public function getColumnType($column)
    {
        if (
            str_contains($column, 'varchar')
            or str_contains($column, 'char')
            or str_contains($column, 'binary')
            or str_contains($column, 'blob')
            or str_contains($column, 'tinyblob')
            or str_contains($column, 'float')
            or str_contains($column, 'double')
            or str_contains($column, 'datetime')
            or str_contains($column, 'timestamp')
            or str_contains($column, 'time')
            or str_contains($column, 'longtext')
            or str_contains($column, 'text')
        ) {
            return 'string|max:255';
        } else if (
            str_contains($column, 'bigint')
            or str_contains($column, 'mediumint')
            or str_contains($column, 'decimal')
            or str_contains($column, 'bit')
            or str_contains($column, 'real')
        ) {
            return 'integer';
        } else if (
            str_contains($column, 'tinyint')
        ) {
            return 'boolean';
        } else if (
            str_contains($column, 'date')
            or str_contains($column, 'year')
        ) {
            return 'date';
        }
    }
}
