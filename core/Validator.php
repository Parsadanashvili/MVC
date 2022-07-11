<?php

namespace Core;

use Core\Request\Request;
use Core\Response\Response;
use Core\Session\Session;

class Validator
{
    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_MIN_LENGTH = 'min_length';
    public const RULE_MAX_LENGTH = 'max_length';
    public const RULE_MATCH = 'match';
    public const RULE_DATE = 'date';
    public const RULE_TIME = 'time';
    public const RULE_DATETIME = 'datetime';

    public const MESSAGE_REQUIRED = 'The :attribute field is required.';
    public const MESSAGE_EMAIL = 'The :attribute field must be a valid email address.';
    public const MESSAGE_MIN = 'The :attribute field must be at least :min.';
    public const MESSAGE_MAX = 'The :attribute field must be at most :max.';
    public const MESSAGE_MIN_LENGTH = 'The :attribute field must be at least :min characters.';
    public const MESSAGE_MAX_LENGTH = 'The :attribute field must be at most :max characters.';
    public const MESSAGE_MATCH = 'The :attribute field must match the :rule field.';
    public const MESSAGE_DATE = 'The :attribute field must be a valid date.';
    public const MESSAGE_TIME = 'The :attribute field must be a valid time.';
    public const MESSAGE_DATETIME = 'The :attribute field must be a valid date and time.';

    protected array $request = [];
    
    protected array $rules = [];

    protected array $messages = [];

    protected array $customAttributes = [];

    protected array $errors = [];

    public function __construct(array $request, array $rules, array $messages = [], array $customAttributes = [])
    {
        $this->request = $request;
        $this->rules = $rules;
        $this->messages = $messages;
        $this->customAttributes = $customAttributes;
    }

    public static function make(array $data, array $rules, array $messages = [], array $customAttributes = [])
    {
        return new Validator($data, $rules, $messages, $customAttributes);
    }

    public function validate()
    {
        foreach ($this->rules as $field => $rules) {
            foreach ($rules as $rule) {
                $this->validateField($field, $rule);
            }
        }

        if(count($this->errors) > 0) {
            Request::storeOldInputs($this->request);
            Response::redirect(Session::get('_previous_url'), 302, $this->errors);
        }

        return $this->request;
    }

    public function validateField($field, $rule)
    {
        $value = $this->request[$field] ?? null;

        $ruleParam = null;

        if(is_string($rule)) {
            $ruleName = $rule;
            if(strpos($rule, ':') !== false) {
                list($ruleName, $ruleParam) = explode(':', $rule);
            }
        }
        
        if(is_array($rule)) {
            $ruleName = key($rule);
            $ruleParam = $rule[$ruleName];
        }

        switch ($ruleName){
            case self::RULE_REQUIRED:
                if(empty($value) && $rule){
                    $this->setError($field, $this->message($field, self::RULE_REQUIRED, self::MESSAGE_REQUIRED));
                }
                break;

            case self::RULE_EMAIL:
                if (!filter_var($value, FILTER_VALIDATE_EMAIL) && $rule) {
                    $this->setError($field, $this->message($field, self::RULE_EMAIL, self::MESSAGE_EMAIL));
                }       
                break;

            case self::RULE_MIN:
                if ((is_numeric($value) && $value < $ruleParam) || (is_string($value) && strlen($value) < $ruleParam)) {
                    $this->setError($field, $this->message($field, self::RULE_MIN, self::MESSAGE_MIN, ['min' => $ruleParam]));
                }
                break;

            case self::RULE_MAX:
                if ((is_numeric($value) && $value > $ruleParam) || (is_string($value) && strlen($value) > $ruleParam)) {
                    $this->setError($field, $this->message($field, self::RULE_MAX, self::MESSAGE_MAX, ['max' => $ruleParam]));
                }
                break;

            case self::RULE_MIN_LENGTH:
                if (is_string($value) && strlen($value) < $ruleParam) {
                    $this->setError($field, $this->message($field, self::RULE_MIN_LENGTH, self::MESSAGE_MIN_LENGTH, ['min' => $ruleParam]));
                }
                break;

            case self::RULE_MAX_LENGTH:
                if (is_string($value) && strlen($value) > $ruleParam) {
                    $this->setError($field, $this->message($field, self::RULE_MAX_LENGTH, self::MESSAGE_MAX_LENGTH, ['max' => $ruleParam]));
                }
                break;

            case self::RULE_MATCH:
                if ($value != $this->request[$ruleParam] && $rule) {
                    $this->setError($field, $this->message($field, self::RULE_MATCH, self::MESSAGE_MATCH, ['rule' => $ruleParam]));
                }
                break;

            case self::RULE_DATE:
                if (!$this->validateDate($value) && $rule) {
                    $this->setError($field, $this->message($field, self::RULE_DATE, self::MESSAGE_DATE));
                }
                break;

            case self::RULE_TIME:
                if (!$this->validateTime($value) && $rule) {
                    $this->setError($field, $this->message($field, self::RULE_TIME, self::MESSAGE_TIME));
                }
                break;

            case self::RULE_DATETIME:
                if (!$this->validateDateTime($value) && $rule) {
                    $this->setError($field, $this->message($field, self::RULE_DATETIME, self::MESSAGE_DATETIME));
                }
                break;
        }
    }

    public function validateDate($date)
    {
        return (bool) preg_match('/^(0[1-9]|1[0-2])\/(0[1-9]|1\d|2\d|3[01])\/(19|20)\d{2}$/', $date);
    }

    public function validateTime($time)
    {
        return (bool) preg_match('/^(0[0-9]|1\d|2[0-3]):([0-5]\d):([0-5]\d)$/', $time);
    }

    public function validateDateTime($datetime)
    {
        return (bool) preg_match('/^(0[1-9]|1[0-2])\/(0[1-9]|1\d|2\d|3[01])\/(19|20)\d{2} (0[0-9]|1\d|2[0-3]):([0-5]\d):([0-5]\d)$/', $datetime);
    }

    protected function setError($field, $message): void
    {
        $this->errors[$field][] = $message;
    }

    protected function message($field, $rule, $message, $attribute = null): string
    {
        $message = $this->messages[$rule] ?? $message;

        $customAttribute = $this->customAttributes[$field] ?? $field;
        $message = str_replace(':attribute', $customAttribute, $message);

        if($attribute) {
            $message = str_replace(':'.key($attribute), $attribute[key($attribute)], $message);
        }

        $message = ucfirst(strtolower($message));

        return $message;
    }
}