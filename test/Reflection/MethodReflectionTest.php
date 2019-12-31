<?php

/**
 * @see       https://github.com/laminas/laminas-code for the canonical source repository
 * @copyright https://github.com/laminas/laminas-code/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-code/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Code\Reflection;

use Laminas\Code\Reflection\MethodReflection;
use LaminasTest\Code\Reflection\TestAsset\InjectableMethodReflection;

/**
 * @group      Laminas_Reflection
 * @group      Laminas_Reflection_Method
 */
class MethodReflectionTest extends \PHPUnit_Framework_TestCase
{
   public function testDeclaringClassReturn()
    {
        $method = new MethodReflection('LaminasTest\Code\Reflection\TestAsset\TestSampleClass2', 'getProp1');
        $this->assertInstanceOf('Laminas\Code\Reflection\ClassReflection', $method->getDeclaringClass());
    }

    public function testParemeterReturn()
    {
        $method = new MethodReflection('LaminasTest\Code\Reflection\TestAsset\TestSampleClass2', 'getProp2');
        $parameters = $method->getParameters();
        $this->assertEquals(2, count($parameters));
        $this->assertInstanceOf('Laminas\Code\Reflection\ParameterReflection', array_shift($parameters));
    }

    public function testStartLine()
    {
        $reflectionMethod = new MethodReflection('LaminasTest\Code\Reflection\TestAsset\TestSampleClass5', 'doSomething');

        $this->assertEquals(37, $reflectionMethod->getStartLine());
        $this->assertEquals(21, $reflectionMethod->getStartLine(true));
    }

    public function testInternalFunctionBodyReturn()
    {
        $reflectionMethod = new MethodReflection('DOMDocument', 'validate');
        $this->assertEmpty($reflectionMethod->getBody());
    }

    public function testGetBodyReturnsCorrectBody()
    {
        $body = '
        //we need a multi-line method body.
        $assigned = 1;
        $alsoAssigined = 2;
        return \'mixedValue\';
    ';
        $reflectionMethod = new MethodReflection('LaminasTest\Code\Reflection\TestAsset\TestSampleClass6', 'doSomething');
        $this->assertEquals($body, $reflectionMethod->getBody());

        $reflectionMethod = new MethodReflection('LaminasTest\Code\Reflection\TestAsset\TestSampleClass11', 'doSomething');
        $body = $reflectionMethod->getBody();
        $this->assertEquals(trim($body), "return 'doSomething';");

        $reflectionMethod = new MethodReflection('LaminasTest\Code\Reflection\TestAsset\TestSampleClass11', 'doSomethingElse');
        $body = $reflectionMethod->getBody();
        $this->assertEquals(trim($body), "return 'doSomethingElse';");

        $reflectionMethod = new MethodReflection('LaminasTest\Code\Reflection\TestAsset\TestSampleClass11', 'doSomethingAgain');
        $body = $reflectionMethod->getBody();
        $this->assertEquals(trim($body), "\$closure = function(\$foo) { return \$foo; };\n\n        return 'doSomethingAgain';");

        $reflectionMethod = new MethodReflection('LaminasTest\Code\Reflection\TestAsset\TestSampleClass11', 'doStaticSomething');
        $body = $reflectionMethod->getBody();
        $this->assertEquals(trim($body), "return 'doStaticSomething';");

        $reflectionMethod = new MethodReflection('LaminasTest\Code\Reflection\TestAsset\TestSampleClass11', 'inline1');
        $body = $reflectionMethod->getBody();
        $this->assertEquals(trim($body), "return 'inline1';");

        $reflectionMethod = new MethodReflection('LaminasTest\Code\Reflection\TestAsset\TestSampleClass11', 'inline2');
        $body = $reflectionMethod->getBody();
        $this->assertEquals(trim($body), "return 'inline2';");

        $reflectionMethod = new MethodReflection('LaminasTest\Code\Reflection\TestAsset\TestSampleClass11', 'inline3');
        $body = $reflectionMethod->getBody();
        $this->assertEquals(trim($body), "return 'inline3';");

        $reflectionMethod = new MethodReflection('LaminasTest\Code\Reflection\TestAsset\TestSampleClass11', 'emptyFunction');
        $body = $reflectionMethod->getBody();
        $this->assertEquals(trim($body), "");
        
        $reflectionMethod = new MethodReflection('LaminasTest\Code\Reflection\TestAsset\TestSampleClass11', 'visibility');
        $body = $reflectionMethod->getBody();
        $this->assertEquals(trim($body), "return 'visibility';");
    }

    public function testInternalMethodContentsReturn()
    {
        $reflectionMethod = new MethodReflection('DOMDocument', 'validate');
        $this->assertEquals('', $reflectionMethod->getContents());
    }

    public function testMethodContentsReturnWithoutDocBlock()
    {
        $contents = <<<CONTENTS
    public function doSomething()
    {
        return 'doSomething';
    }
CONTENTS;
        $reflectionMethod = new MethodReflection('LaminasTest\Code\Reflection\TestAsset\TestSampleClass11', 'doSomething');
        $this->assertEquals($contents, $reflectionMethod->getContents(false));

        $contents = '    public function doSomethingElse($one, $two = 2, $three = \'three\') { return \'doSomethingElse\'; }';
        $reflectionMethod = new MethodReflection('LaminasTest\Code\Reflection\TestAsset\TestSampleClass11', 'doSomethingElse');
        $this->assertEquals($contents, $reflectionMethod->getContents(false));

        $contents = <<<'CONTENTS'
    public function doSomethingAgain()
    {
        $closure = function($foo) { return $foo; };

        return 'doSomethingAgain';
    }
CONTENTS;
        $reflectionMethod = new MethodReflection('LaminasTest\Code\Reflection\TestAsset\TestSampleClass11', 'doSomethingAgain');
        $this->assertEquals($contents, $reflectionMethod->getContents(false));

        $contents = '    public function inline1() { return \'inline1\'; }';
        $reflectionMethod = new MethodReflection('LaminasTest\Code\Reflection\TestAsset\TestSampleClass11', 'inline1');
        $this->assertEquals($contents, $reflectionMethod->getContents(false));

        $contents = ' public function inline2() { return \'inline2\'; }';
        $reflectionMethod = new MethodReflection('LaminasTest\Code\Reflection\TestAsset\TestSampleClass11', 'inline2');
        $this->assertEquals($contents, $reflectionMethod->getContents(false));

        $contents = ' public function inline3() { return \'inline3\'; }';
        $reflectionMethod = new MethodReflection('LaminasTest\Code\Reflection\TestAsset\TestSampleClass11', 'inline3');
        $this->assertEquals($contents, $reflectionMethod->getContents(false));
        
        $contents = <<<'CONTENTS'
    public function visibility()
    {
        return 'visibility';
    }
CONTENTS;
        $reflectionMethod = new MethodReflection('LaminasTest\Code\Reflection\TestAsset\TestSampleClass11', 'visibility');
        $this->assertEquals($contents, $reflectionMethod->getContents(false));
    }

    public function testFunctionContentsReturnWithDocBlock()
    {
        $contents = <<<'CONTENTS'
/**
     * Doc block doSomething
     * @return string
     */
    public function doSomething()
    {
        return 'doSomething';
    }
CONTENTS;
        $reflectionMethod = new MethodReflection('LaminasTest\Code\Reflection\TestAsset\TestSampleClass11', 'doSomething');
        $this->assertEquals($contents, $reflectionMethod->getContents(true));
        $this->assertEquals($contents, $reflectionMethod->getContents());

                $contents = <<<'CONTENTS'
/**
     * Awesome doc block
     */
    public function emptyFunction() {}
CONTENTS;
        $reflectionMethod = new MethodReflection('LaminasTest\Code\Reflection\TestAsset\TestSampleClass11', 'emptyFunction');
        $this->assertEquals($contents, $reflectionMethod->getContents(true));
    }

    public function testGetPrototypeMethod()
    {
        $reflectionMethod = new MethodReflection('LaminasTest\Code\Reflection\TestAsset\TestSampleClass10', 'doSomethingElse');
        $prototype = array(
            'namespace' => 'LaminasTest\Code\Reflection\TestAsset',
            'class' => 'TestSampleClass10',
            'name' => 'doSomethingElse',
            'visibility' => 'public',
            'return' => 'int',
            'arguments' => array(
                'one' => array(
                    'type'     => 'int',
                    'required' => true,
                    'by_ref'   => false,
                    'default'  => null,
                ),
                'two' => array(
                    'type'     => 'int',
                    'required' => false,
                    'by_ref'   => false,
                    'default'  => 2,
                ),
                'three' => array(
                    'type'     => 'string',
                    'required' => false,
                    'by_ref'   => false,
                    'default'  => 'three',
                ),
            ),
        );
        $this->assertEquals($prototype, $reflectionMethod->getPrototype());
        $this->assertEquals('public int doSomethingElse(int $one, int $two = 2, string $three = \'three\')', $reflectionMethod->getPrototype(MethodReflection::PROTOTYPE_AS_STRING));

        $reflectionMethod = new MethodReflection('LaminasTest\Code\Reflection\TestAsset\TestSampleClass2', 'getProp2');
        $prototype = array(
            'namespace' => 'LaminasTest\Code\Reflection\TestAsset',
            'class' => 'TestSampleClass2',
            'name' => 'getProp2',
            'visibility' => 'public',
            'return' => 'mixed',
            'arguments' => array(
                'param1' => array(
                    'type'     => '',
                    'required' => true,
                    'by_ref'   => false,
                    'default'  => null,
                ),
                'param2' => array(
                    'type'     => 'LaminasTest\Code\Reflection\TestAsset\TestSampleClass',
                    'required' => true,
                    'by_ref'   => false,
                    'default'  => null,
                ),
            ),
        );
        $this->assertEquals($prototype, $reflectionMethod->getPrototype());
        $this->assertEquals('public mixed getProp2($param1, LaminasTest\Code\Reflection\TestAsset\TestSampleClass $param2)', $reflectionMethod->getPrototype(MethodReflection::PROTOTYPE_AS_STRING));

        $reflectionMethod = new MethodReflection('LaminasTest\Code\Reflection\TestAsset\TestSampleClass12', 'doSomething');
        $prototype = array(
            'namespace' => 'LaminasTest\Code\Reflection\TestAsset',
            'class' => 'TestSampleClass12',
            'name' => 'doSomething',
            'visibility' => 'protected',
            'return' => 'string',
            'arguments' => array(
                'one' => array(
                    'type'     => 'int',
                    'required' => true,
                    'by_ref'   => true,
                    'default'  => null,
                ),
                'two' => array(
                    'type'     => 'int',
                    'required' => true,
                    'by_ref'   => false,
                    'default'  => null,
                ),
            ),
        );
        $this->assertEquals($prototype, $reflectionMethod->getPrototype());
        $this->assertEquals('protected string doSomething(int &$one, int $two)', $reflectionMethod->getPrototype(MethodReflection::PROTOTYPE_AS_STRING));
    }

    public function testGetAnnotationsWithNoNameInformations()
    {
        $reflectionMethod = new InjectableMethodReflection(
            // TestSampleClass5 has the annotations required to get to the
            // right point in the getAnnotations method.
            'LaminasTest\Code\Reflection\TestAsset\TestSampleClass5',
            'doSomething'
        );

        $annotationManager = new \Laminas\Code\Annotation\AnnotationManager();

        $fileScanner = $this->getMockBuilder('Laminas\Code\Scanner\CachingFileScanner')
                            ->disableOriginalConstructor()
                            ->getMock();

        $reflectionMethod->setFileScanner($fileScanner);

        $fileScanner->expects($this->any())
                    ->method('getClassNameInformation')
                    ->will($this->returnValue(false));

        $this->assertFalse($reflectionMethod->getAnnotations($annotationManager));
    }

    /**
     * @group 5062
     */
    public function testGetContentsWithCoreClass()
    {
        $reflectionMethod = new MethodReflection('DateTime', 'format');
        $this->assertEquals("", $reflectionMethod->getContents(false));
    }

    public function testGetContentsReturnsEmptyContentsOnEvaldCode()
    {
        $className = uniqid('MethodReflectionTestGenerated');

        eval('name' . 'space ' . __NAMESPACE__ . '; cla' . 'ss ' . $className . '{fun' . 'ction foo(){}}');

        $reflectionMethod = new MethodReflection(__NAMESPACE__ . '\\' . $className, 'foo');

        $this->assertSame('', $reflectionMethod->getContents());
        $this->assertSame('', $reflectionMethod->getBody());
    }

    public function testGetContentsReturnsEmptyContentsOnInternalCode()
    {
        $reflectionMethod = new MethodReflection('ReflectionClass', 'getName');
        $this->assertSame('', $reflectionMethod->getContents());
    }
}
