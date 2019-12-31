<?php

/**
 * @see       https://github.com/laminas/laminas-code for the canonical source repository
 * @copyright https://github.com/laminas/laminas-code/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-code/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Code\Generator;

/**
 * @category   Laminas
 * @package    Laminas_Code_Generator
 */
class PropertyValueGenerator extends ValueGenerator
{

    /**
     * generate()
     *
     * @return string
     */
    public function generate()
    {
        return parent::generate() . ';';
    }

}
