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
                    'foo' => 'another %foo%'
                )
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
                        'foo' => 'another ' . $otherFoo
                    )
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
                )
            ))
            ->then()
                ->array($object->getVariablesToAskInArray($array))->isEqualTo(array(
                    'bar' => '%bar%',
                    'foo' => '%foo%'
                ))
        ;
    }
}