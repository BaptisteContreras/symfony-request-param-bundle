<?php

namespace BaptisteContreras\SymfonyRequestParamBundle\Exception;

class NoErrorPresenterFoundException extends \Exception
{
    public function __construct()
    {
        parent::__construct('No error presenter found');
    }
}
