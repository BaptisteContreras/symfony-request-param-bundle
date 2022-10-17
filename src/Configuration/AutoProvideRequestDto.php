<?php


namespace RequestParam\Bundle\Configuration;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class AutoProvideRequestDto extends ParamConverter
{
    public function __construct()
    {
        parent::__construct(self::class);
    }
}