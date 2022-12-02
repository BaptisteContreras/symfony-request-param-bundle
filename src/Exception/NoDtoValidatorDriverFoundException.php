<?php

namespace BaptisteContreras\SymfonyRequestParamBundle\Exception;

class NoDtoValidatorDriverFoundException extends \Exception
{
    public function __construct()
    {
        parent::__construct('No DTO validator found for source type');
    }
}
