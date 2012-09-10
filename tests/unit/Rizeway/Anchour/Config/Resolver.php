<?php
namespace tests\unit\Rizeway\Anchour\Config;

use mageekguy\atoum\test;

class Resolver extends test
{
    public function testReplaceValuesInRecursiveArray()
    {
        $this
            ->if($object = new \mock\Rizeway\Anchour\Config\Resolver())
            ->and($array = array(
                'foo' => ($foo = uniqid()),
                'bar' => '%bar%',
                'arr' => array(
                    'foo' => 'another %foo%',
                    'bar' => 'don\'t replace me \%bar\%'
                ),
                'boo' => 'don\'t replace me \%foo\%'
            ))
            ->and($values = array(
                '%bar%' => ($bar = uniqid()),
                '%foo%' => ($otherFoo = uniqid())
            ))
            ->then()
                ->array($object->replaceValuesInRecursiveArray($array, $values))->isEqualTo(array(
                    'foo' => $foo,
                    'bar' => $bar,
                    'arr' => array(
                        'foo' => 'another ' . $otherFoo,
                        'bar' => 'don\'t replace me %bar%'
                    ),
                    'boo' => 'don\'t replace me %foo%'
                ))
        ;
    }

    public function testGetVariablesToAskInArray()
    {
        $this
            ->if($object = new \mock\Rizeway\Anchour\Config\Resolver())
            ->and($array = array(
                'foo' => ($foo = uniqid()),
                'bar' => '%bar%',
                'arr' => array(
                    'foo' => 'another %foo%'
                ),
                'boo' => 'don\'t match me \%boo\%'
            ))
            ->then()
                ->array($object->getVariablesToAskInArray($array))->isEqualTo(array(
                    'bar' => '%bar%',
                    'foo' => '%foo%'
                ))
        ;
    }
}