<?php
namespace tests\unit\Rizeway\Anchour\Config\Resolvers;

use mageekguy\atoum\test;

class ConfigurationFileResolver extends test {
    public function test__construct() {
        $this
            ->if($file = new \mock\SplFileInfo(uniqid()))
            ->and($file->getMockController()->isFile = false)
            ->and($file->getMockController()->getRealPath = $path = uniqid())
            ->then()
                ->exception(function() use($file) {
                    new \Rizeway\Anchour\Config\Resolvers\ConfigurationFileResolver($file);
                })
                ->isInstanceOf('\\RuntimeException')
                ->hasMessage(sprintf('File %s does not exist', $path))

            ->if($adapter = new \jubianchi\Adapter\Test\Adapter())
            ->and($file->getMockController()->isFile = true)
            ->and($file->getMockController()->getExtension = 'ini')
            ->and($adapter->parse_ini_file = function() {})
            ->then()
                ->object(new \Rizeway\Anchour\Config\Resolvers\ConfigurationFileResolver($file, $adapter))
                ->adapter($adapter)
                    ->call('parse_ini_file')->withArguments($path)->once()

            ->if($file->getMockController()->getExtension = 'json')
            ->and($adapter->file_get_contents = function() {})
            ->then()
                ->object(new \Rizeway\Anchour\Config\Resolvers\ConfigurationFileResolver($file, $adapter))
                ->adapter($adapter)
                    ->call('file_get_contents')->withArguments($path)->once()
        ;
    }

    public function testResolve() {
        $this
            ->if($file = new \mock\SplFileInfo(uniqid()))
            ->and($file->getMockController()->isFile = true)
            ->and($object = new \mock\Rizeway\Anchour\Config\Resolvers\ConfigurationFileResolver($file))
            ->and($configurable = new \mock\Rizeway\Anchour\Config\ConfigurableInterface())
            ->and($configurable->getMockController()->getConfig = array())
            ->then()
                ->array($object->resolve($configurable))->isEqualTo(array())
                ->mock($object)
                    ->call('replaceValuesInRecursiveArray')->withArguments(array(), array())->once()
        ;
    }
}