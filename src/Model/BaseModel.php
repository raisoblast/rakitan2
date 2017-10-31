<?php declare(strict_types=1);
namespace Rakitan\Model;

use Valitron\Validator;

/**
 * base model
 * @author arifk
 */
abstract class BaseModel
{
    protected $rules = [];
    protected $labels = [];
    protected $errors;

    /**
     * validasi data $_POST
     * @param $data
     * @return boolean
     */
    public function validate($data)
    {
        $v = new Validator($data, [], 'id'); // validator dgn bahasa
        foreach ($this->rules as $rule => $columns) {
            $v->rule($rule, $columns);
        }
        $v->labels($this->labels);
        if ($v->validate()) {
            return true;
        } else {
            $this->errors = $v->errors();
            return false;
        }
    }

    public function getErrors()
    {
        $result = '';
        if (!is_array($this->errors)) {
            return $result;
        }
        foreach ($this->errors as $errs) {
            foreach ($errs as $err) {
                $result .= $err . '<br/>';
            }
        }
        return $result;
    }

    /**
     * Set object property dari array
     * @param array $data
     * @return mixed
     */
    public function fill(array $data)
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public function __get($name)
    {
        if (property_exists(self::class, $name))
            return $this->$name;
        return NULL;
    }
}