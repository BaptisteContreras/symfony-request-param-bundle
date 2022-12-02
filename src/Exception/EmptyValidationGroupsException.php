<?php

namespace BaptisteContreras\SymfonyRequestParamBundle\Exception;

class EmptyValidationGroupsException extends \Exception
{
    public function __construct()
    {
        parent::__construct('You must provide a valid validation group (not [] or \'\') if you enable DTO validation');
    }
}
