<?php
namespace tests\unit\jubianchi\Ftp;

use mageekguy\atoum\test;

class Ftp extends test
{
    public function test__construct()
    {
        $this
            ->if($adapter = new \jubianchi\Adapter\Test\Adapter())
            ->and($adapter->extension_loaded = true)
            ->and($adapter->ftp_quit = function() {})
            ->then()
                ->object(new \jubianchi\Ftp\Ftp($adapter))->isInstanceOf('\\jubianchi\\Ftp\\Ftp')

            ->if($adapter->extension_loaded = false)
            ->then()
                ->exception(function() use($adapter) {
                    new \jubianchi\Ftp\Ftp($adapter);
                })
                ->isInstanceOf('\\RuntimeException')
                ->hasMessage('FTP extension is not loaded')
        ;
    }

    public function testGetAdapter()
    {
        $this
            ->if($adapter = new \jubianchi\Adapter\Test\Adapter())
            ->and($adapter->extension_loaded = true)
            ->and($adapter->ftp_quit = function() {})
            ->and($object = new \jubianchi\Ftp\Ftp($adapter))
            ->and($object->setAdapter(null))
            ->then()
                ->object($object->getAdapter())->isInstanceOf('\\jubianchi\\Adapter\\AdapterInterface')

            ->if($object->setAdapter($adapter))
            ->then()
                ->object($object->getAdapter())->isIdenticalTo($adapter)
        ;
    }

    public function testSetAdapter()
    {
        $this
            ->if($adapter = new \jubianchi\Adapter\Test\Adapter())
            ->and($adapter->extension_loaded = true)
            ->and($adapter->ftp_quit = function() {})
            ->and($object = new \jubianchi\Ftp\Ftp($adapter))
            ->and($object->setAdapter(null))
            ->then()
                ->object($object->setAdapter(null))->isIdenticalTo($object)
                ->object($object->setAdapter($adapter))->isIdenticalTo($object)
        ;
    }

    public function testConnect()
    {
        $this
            ->if($adapter = new \jubianchi\Adapter\Test\Adapter())
            ->and($adapter->extension_loaded = true)
            ->and($adapter->is_resource = true)
            ->and($adapter->ftp_connect = true)
            ->and($adapter->ftp_login = true)
            ->and($adapter->ftp_quit = function() {})
            ->and($object = new \jubianchi\Ftp\Ftp($adapter))
            ->then()
                ->boolean($object->connect(uniqid(), uniqid(), uniqid()))->isTrue()

            ->if($adapter->is_resource = false)
            ->then()
                ->exception(function() use($object) {
                    $object->connect(uniqid(), uniqid(), uniqid());
                })
                ->isInstanceOf('\\RuntimeException')
                ->hasMessage('FTP connection has failed')
        ;
    }

    public function testLogin()
    {
        $this
            ->if($adapter = new \jubianchi\Adapter\Test\Adapter())
            ->and($adapter->extension_loaded = true)
            ->and($adapter->is_resource = true)
            ->and($adapter->ftp_login = true)
            ->and($adapter->ftp_quit = function() {})
            ->and($object = new \jubianchi\Ftp\Ftp($adapter))
            ->then()
                ->boolean($object->login(uniqid(), uniqid()))->isTrue()

            ->if($adapter->ftp_login = false)
            ->then()
                ->exception(function() use($object) {
                    $object->login(uniqid(), uniqid());
                })
                ->isInstanceOf('\\RuntimeException')
                ->hasMessage('Could not login with the given crednetials')
        ;
    }

    public function testCreateDirectory()
    {
        $this
            ->if($adapter = new \jubianchi\Adapter\Test\Adapter())
            ->and($adapter->extension_loaded = true)
            ->and($adapter->ftp_mkdir = true)
            ->and($adapter->ftp_chdir = true)
            ->and($adapter->ftp_pwd = uniqid())
            ->and($adapter->ftp_quit = function() {})
            ->and($object = new \mock\jubianchi\Ftp\Ftp($adapter))
            ->then()
                ->boolean($object->createDirectory(uniqid()))->isTrue()

            ->if($adapter->ftp_mkdir = false)
            ->and($adapter->ftp_chdir = false)
            ->and($directory = uniqid())
            ->then()
                ->exception(function() use($object, $directory) {
                    $object->createDirectory($directory);
                })
                ->isInstanceOf('\\RuntimeException')
                ->hasMessage(sprintf('Could not create the %s directory', $directory))
        ;
    }

    public function testDirectoryExists()
    {
        $this
            ->if($adapter = new \jubianchi\Adapter\Test\Adapter())
            ->and($adapter->extension_loaded = true)
            ->and($adapter->is_resource = true)
            ->and($adapter->ftp_connect = true)
            ->and($adapter->ftp_quit = function() {})
            ->and($adapter->ftp_pwd = uniqid())
            ->and($adapter->ftp_chdir = true)
            ->and($object = new \jubianchi\Ftp\Ftp($adapter))
            ->then()
                ->boolean($object->directoryExists(uniqid()))->isTrue()

            ->if($adapter->ftp_chdir = false)
            ->then()
                ->boolean($object->directoryExists(uniqid()))->isFalse()
        ;
    }

    public function testCreateDirectoryRecursive()
    {
        $this
            ->if($adapter = new \jubianchi\Adapter\Test\Adapter())
            ->and($adapter->extension_loaded = true)
            ->and($adapter->ftp_mkdir = true)
            ->and($adapter->ftp_quit = function() {})
            ->and($object = new \mock\jubianchi\Ftp\Ftp($adapter))
            ->and($object->getMockController()->createDirectory = true)
            ->and($object->getMockController()->directoryExists = false)
            ->and($directory = implode(DIRECTORY_SEPARATOR, array($p1 = uniqid(), $p2 = uniqid(), $p3 = uniqid())))
            ->then()
                ->boolean($object->createDirectoryRecursive($directory))->isTrue()
                ->mock($object)
                    ->call('createDirectory')                    
                    ->withArguments(DIRECTORY_SEPARATOR . $p1)->once()
                    ->withArguments(DIRECTORY_SEPARATOR . $p1 . DIRECTORY_SEPARATOR . $p2)->once()
                    ->withArguments(DIRECTORY_SEPARATOR . $p1 . DIRECTORY_SEPARATOR . $p2 . DIRECTORY_SEPARATOR . $p3)->once()
        ;
    }

    public function testGetConnection()
    {
        $this
            ->if($adapter = new \jubianchi\Adapter\Test\Adapter())
            ->and($adapter->extension_loaded = true)
            ->and($adapter->is_resource = true)
            ->and($adapter->ftp_connect = $connection = uniqid())
            ->and($adapter->ftp_login = true)
            ->and($adapter->ftp_quit = function() {})
            ->and($object = new \jubianchi\Ftp\Ftp($adapter))
            ->then()
                ->variable($object->getConnection())->isNull()                

            ->if($object->connect(uniqid(), uniqid(), uniqid()))
            ->then()
                ->variable($object->getConnection())->isIdenticalTo($connection)
        ;
    }
}