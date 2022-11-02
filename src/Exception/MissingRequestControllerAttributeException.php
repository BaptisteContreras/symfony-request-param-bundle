<?php

namespace RequestParam\Bundle\Exception;

class MissingRequestControllerAttributeException extends \Exception
{
    public function __construct(string $missingAttributeName)
    {
        parent::__construct(
            sprintf('The attribute %s is missing from the request.', $missingAttributeName)
        );
    }
}
