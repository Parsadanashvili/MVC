<?php

namespace Core\Traits;

use Core\Request\Request;
use Core\Validator;

trait ValidateRequest
{
    public function validate(Request $request, array $rules,
                             array $messages = [], array $customAttributes = [])
    {
        return Validator::make($request->all(), $rules, $messages, $customAttributes)
                 ->validate();
    }
}