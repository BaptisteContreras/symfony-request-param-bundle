<?php

namespace RequestParam\Bundle\Exception;

class RequestParameterMustBeObjectException extends \Exception
{
    public function __construct(string $parameterName)
    {
        parent::__construct(
            sprintf('Parameter %s\'s type must be an object, not a built-in type.', $parameterName)
        );
    }
}
