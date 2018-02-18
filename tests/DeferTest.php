<?php

namespace Liamja\Defer\Tests;

use Liamja\Defer\Defer;

class DeferTest extends \PHPUnit_Framework_TestCase
{
    public static $deferrableOutput = array();

    public function setUp()
    {
        DeferTest::$deferrableOutput = array();
    }

    public function testClosureCanBeDeferred()
    {
        DeferTest::$deferrableOutput = array();

        $this->assertEmpty(DeferTest::$deferrableOutput);

        $this->methodWithDeferredFunctions();
        
        $this->assertNotEmpty(DeferTest::$deferrableOutput);

        $this->assertEquals(
            array('shouldBeFirst', 'shouldBeSecond', 'shouldBeThird', 'shouldBeLast'),
            DeferTest::$deferrableOutput
        );
    }

    public function testDeferredCallablesStillRunAfterException()
    {
        DeferTest::$deferrableOutput = array();

        $this->assertEmpty(DeferTest::$deferrableOutput);

        try {
            $this->methodWithDeferredFunctionsThatThrowsException();
        } catch (\Exception $e) {
            $this->assertNotEmpty(DeferTest::$deferrableOutput);

            $this->assertEquals(
                array('shouldBeFirst', 'shouldBeSecond', 'shouldBeThird', 'shouldBeLast'),
                DeferTest::$deferrableOutput
            );
        }
    }

    private function methodWithDeferredFunctions()
    {
        $this->assertEmpty(DeferTest::$deferrableOutput);

        $defer = new Defer;

        $this->assertEmpty(DeferTest::$deferrableOutput);

        $defer->push(function () {
            DeferTest::$deferrableOutput[] = 'shouldBeLast';
        });

        $this->assertEmpty(DeferTest::$deferrableOutput);

        $defer(function () {
            DeferTest::$deferrableOutput[] = 'shouldBeThird';
        });

        $this->assertEmpty(DeferTest::$deferrableOutput);

        $defer->push(function () {
            DeferTest::$deferrableOutput[] = 'shouldBeSecond';
        });

        $this->assertEmpty(DeferTest::$deferrableOutput);

        $defer(function () {
            DeferTest::$deferrableOutput[] = 'shouldBeFirst';
        });

        $this->assertEmpty(DeferTest::$deferrableOutput);
    }

    private function methodWithDeferredFunctionsThatThrowsException()
    {
        $this->assertEmpty(DeferTest::$deferrableOutput);

        $defer = new Defer;

        $this->assertEmpty(DeferTest::$deferrableOutput);

        $defer->push(function () {
            DeferTest::$deferrableOutput[] = 'shouldBeLast';
        });

        $this->assertEmpty(DeferTest::$deferrableOutput);

        $defer(function () {
            DeferTest::$deferrableOutput[] = 'shouldBeThird';
        });

        $this->assertEmpty(DeferTest::$deferrableOutput);

        $defer->push(function () {
            DeferTest::$deferrableOutput[] = 'shouldBeSecond';
        });

        $this->assertEmpty(DeferTest::$deferrableOutput);

        $defer(function () {
            DeferTest::$deferrableOutput[] = 'shouldBeFirst';
        });

        $this->assertEmpty(DeferTest::$deferrableOutput);

        throw new \Exception('test');
    }

    public function testNestedClosureCanBeDeferred()
    {
        DeferTest::$deferrableOutput = array();

        $this->assertEmpty(DeferTest::$deferrableOutput);

        $this->methodCallingMethodWithDeferredFunction();

        $this->assertNotEmpty(DeferTest::$deferrableOutput);

        $this->assertEquals(
            array('shouldBeFirst', 'shouldBeSecond', 'shouldBeThird', 'shouldBeLast'),
            DeferTest::$deferrableOutput
        );
    }

    private function methodCallingMethodWithDeferredFunction()
    {
        $this->assertEmpty(DeferTest::$deferrableOutput);

        $this->methodWithDeferredFunctions();

        $this->assertNotEmpty(DeferTest::$deferrableOutput);
    }


    public function testDeferCallsDeferredCallablesOnScopeLoss()
    {
        DeferTest::$deferrableOutput = array();

        $this->assertEmpty(DeferTest::$deferrableOutput);

        $this->level1();

        $this->assertNotEmpty(DeferTest::$deferrableOutput);

        $this->assertEquals(
            array('level3', 'level2', 'level1'),
            DeferTest::$deferrableOutput
        );
    }

    private function level1()
    {
        $this->assertEmpty(DeferTest::$deferrableOutput);

        $defer = new Defer;

        $this->assertEmpty(DeferTest::$deferrableOutput);

        $defer(function () {
            DeferTest::$deferrableOutput[] = 'level1';
        });

        $this->assertEmpty(DeferTest::$deferrableOutput);

        $this->level2($defer);

        $this->assertEmpty(DeferTest::$deferrableOutput);
    }

    private function level2(Defer $defer)
    {
        $this->assertEmpty(DeferTest::$deferrableOutput);

        $defer(function () {
            DeferTest::$deferrableOutput[] = 'level2';
        });

        $this->assertEmpty(DeferTest::$deferrableOutput);

        $this->level3($defer);

        $this->assertEmpty(DeferTest::$deferrableOutput);
    }

    private function level3(Defer $defer)
    {
        $this->assertEmpty(DeferTest::$deferrableOutput);

        $defer(function () {
            DeferTest::$deferrableOutput[] = 'level3';
        });

        $this->assertEmpty(DeferTest::$deferrableOutput);
    }
}
