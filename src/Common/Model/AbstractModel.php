<?php

namespace Shift\Common\Model;


use Boost\BoostTrait;
use Boost\Accessors\ProtectedAccessorsTrait;

abstract class AbstractModel
{
    use BoostTrait;
    use ProtectedAccessorsTrait;
}
