<?php
/**
 * Created by PhpStorm.
 * User: danylo
 * Date: 6/22/18
 * Time: 9:48 AM
 */

use PHPUnit\Framework\TestCase;

class ArrayStackTest extends TestCase
{

    public function testSinglePushThenPopWithSizeCheck()
    {
        $stack = new ArrayStack();
        $this->assertEquals(0, $stack->size());
        $stack->push('test');
        $this->assertEquals(1, $stack->size());
        $this->assertEquals('test', $stack->pop());
        $this->assertEquals(0, $stack->size());
    }

    public function testMultiplePushThenPopWithSizeChecks()
    {
        $stack = new ArrayStack();
        $values = range(0, 10);

        $size = 0;
        foreach($values as $value) {
            $stack->push($value);
            $size++;

            $this->assertEquals($size, $stack->size());
        }

        // Popping is reversed, so we need to reverse $values too
        $values = array_reverse($values);

        foreach($values as $index => $value) {
            $this->assertEquals($value, $stack->pop());
            $size--;

            $this->assertEquals($size, $stack->size());
        }
    }

    public function testPopOfEmptyThrows()
    {
        $this->expectException(LengthException::class);

        $stack = new ArrayStack();
        $stack->pop();
    }
}
