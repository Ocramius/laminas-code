<?php

/**
 * @see       https://github.com/laminasframwork/laminas-code for the canonical source repository
 * @copyright https://github.com/laminasframwork/laminas-code/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminasframwork/laminas-code/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Code\Generator\TestAsset;

use Laminas\Code\Generic\Prototype\PrototypeInterface;

class PrototypeClass implements PrototypeInterface
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'prototype';
    }

}
