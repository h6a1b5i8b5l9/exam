<?php


class Validator
{
    private $passed = false, $errors = [];



    public function check($source, $items = []) {
        foreach ($items as $item => $rules) {
            foreach ($rules as $rule => $rule_value) {

                $value = $source[$item];

                if($rule == 'required' && empty($value)) {
                    $this->addError("{$item} is required");
                } else if (!empty($value)) {
                    switch ($rule) {
                        case 'min':
                            if (strlen($value) < $rule_value) {
                                $this->addError("{$item} must be at least {$rule_value} characters");
                            }
                            break;

                        case 'max':
                            if (strlen($value) > $rule_value) {
                                $this->addError("{$item} must be maximum {$rule_value} characters");
                            }
                            break;

                        case 'matches':
                            if ($value != $source[$rule_value]) {
                                $this->addError("{$rule_value} must match {$item}");
                            }
                            break;

                        case 'unique':
                            if(in_array($value, $rule_value)) {
                                $this->addError("{$item} already exists.");
                            }
                            break;

                        case 'email':
                            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                $this->addError("{$item} is not an email");
                            }
                            break;

                        case 'integer':
                            if (!filter_var($value, FILTER_VALIDATE_INT)) {
                                $this->addError("{$item} is not an integer");
                            }
                            break;

                        case 'float':
                            if (!filter_var($value, FILTER_VALIDATE_FLOAT)) {
                                $this->addError("{$item} is not an float");
                            }
                            break;
                    }
                }
            }
        }
        if(empty($this->errors)) {
            $this->passed = true;
        }

        return $this;
    }

    private function addError($error) {
        $this->errors[] = $error;
    }


    public function errors() {
        return $this->errors;
    }

    public function passed() {
        return $this->passed;
    }
}