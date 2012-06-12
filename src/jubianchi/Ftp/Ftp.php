<?php
namespace jubianchi\Ftp;

use jubianchi\Output\OutputInterface;
use jubianchi\Adapter\Adaptable;
use jubianchi\Adapter\AdapterInterface;

class Ftp extends Adaptable
{
    /**
     * @var resource
     */
    private $connection;

    /**
     * @var \jubianchi\Output\OutputInterface
     */
    private $output;

    /**
     * @param string $host
     * @param string $login
     * @param string $password
     * @param int    $port
     * @param int    $timeout
     */
    public function __construct(AdapterInterface $adapter = null) 
    {
        $this->setAdapter($adapter);

        if(false === $this->getAdapter()->extension_loaded('ftp'))
        {
            throw new \RuntimeException('FTP extension is not loaded');
        }        
    }

    /**
     * @param string $host
     * @param string $login
     * @param string $password
     * @param int    $timeout
     *
     * @throws \RuntimeException
     *
     * @return bool
     */
    public function connect($host, $login, $password, $port = 21, $timeout = 90) 
    {
        $this->connection = $this->getAdapter()->ftp_connect($host, $port, $timeout);

        if (false === $this->getAdapter()->is_resource($this->connection))
        {
            throw new \RuntimeException('FTP connection has failed');
        }

        try 
        {
            $this->login($login, $password);
        }        
        catch (\RuntimeException $exc) 
        {
            $this->connection = null;

            throw $exc;
        }

        return true;
    }

    /**
     * @param string $host
     * @param string $login
     *
     * @throws \RuntimeException
     *
     * @return bool
     */
    public function login($login, $password) 
    {
        if (false === $this->getAdapter()->ftp_login($this->getConnection(), $login, $password)) 
        {
            throw new \RuntimeException('Could not login with the given crednetials');
        }

        return true;
    }

    /**
     * @param string $directory
     *
     * @return bool
     */
    public function createDirectory($directory) 
    {
        if (false === $this->directoryExists($directory))
        {
            if(false === $this->getAdapter()->ftp_mkdir($this->getConnection(), $directory)) {
                throw new \RuntimeException(sprintf('Could not create the %s directory', $directory));
            }
        }        

        return true;
    }

    /**
     * @param string $directory
     *
     * @return bool
     */
    public function directoryExists($directory) 
    {
        $cwd = $this->getAdapter()->ftp_pwd($this->getConnection());
        $exists = $this->getAdapter()->ftp_chdir($this->getConnection(), $directory);            
        $this->getAdapter()->ftp_chdir($this->getConnection(), $cwd);   

        return $exists;
    }

    /**
     * @param string $directory
     *
     * @return bool
     */
    public function createDirectoryRecursive($directory) 
    {
        $parts = explode(DIRECTORY_SEPARATOR, trim($directory, '/'));

        $path = '';    
        foreach ($parts as $dirname) 
        {
            $path .= DIRECTORY_SEPARATOR . $dirname;

            $this->createDirectory($path);
        }

        return true;
    }

    /**
     * @param string $local
     * @param string $distant
     *
     * @throws \RuntimeException
     *
     * @return bool
     */
    public function uploadFile($local, $distant) 
    {
        $this->log('Uploading file <info>' . $local . '</info> to <info>' . $distant . '</info>');

        if (false === $this->getAdapter()->ftp_put($this->getConnection(), $distant, $local, FTP_BINARY))
        {            
            throw new \RuntimeException(sprintf('Could not send the %s local file to %s', $local, $distant));
        }

        return true;
    }

    /**
     * @param string $local
     * @param string $distant
     *
     * @throws \RuntimeException
     *
     * @return bool
     */
    public function uploadDirectory($local, $distant) 
    {
        $this->log('Uploading directory <info>' . $local . '</info> to <info>' . $distant . '</info>');

        $this->createDirectoryRecursive($distant);

        $iterator = new \DirectoryIterator($local);
        foreach ($iterator as $entry) {
            if (false === in_array($entry->getBasename(), array('.', '..'))) 
            {
                switch (true) 
                {
                    case $entry->isDir():
                        $this->uploadDirectory($entry->getRealPath(), $distant . DIRECTORY_SEPARATOR . $entry->getBasename());
                        break;
                    case $entry->isFile():
                        $file = $distant . DIRECTORY_SEPARATOR . preg_replace(sprintf('`^%s\/`', $local), '', $entry->getRealPath());

                        $this->uploadFile($entry->getRealPath(), $file);
                        break;
                    default:
                        //The entry is a link
                        break;
                }
            }            
        }

        return true;
    }

    /**
     * @return resource
     */
    public function getConnection() 
    {
        return $this->connection;
    }

    /**
     * @param \jubianchi\Output\OutputInterface $output
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @return \jubianchi\Output\OutputInterface
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param string $message
     */
    public function log($message)
    {
        if (false === is_null($this->getOutput()))
        {
            $this->getOutput()->writeln($message);
        }
    }

    public function isConnected()
    {
        return (true === $this->getAdapter()->is_resource($this->getConnection()));
    }

    public function __destruct()
    {
        $this->getAdapter()->ftp_quit($this->getConnection());
    }
}