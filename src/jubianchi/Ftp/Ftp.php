<?php
namespace jubianchi\Ftp;

use Symfony\Component\Console\Output\OutputInterface;

class Ftp 
{
    /**
     * @var resource
     */
    private $connection;


    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    private $output;

    /**
     * @param string $host
     * @param string $login
     * @param string $password
     * @param int    $port
     * @param int    $timeout
     */
    public function __construct($host, $login, $password, $port = 21, $timeout = 90) 
    {
        $this->connect($host, $login, $password, $port, $timeout);
    }

    /**
     * @param string $host
     * @param string $login
     * @param string $password
     * @param int    $timeout
     *
     * @throws \RuntimeException
     */
    public function connect($host, $login, $password, $port = 21, $timeout = 90) 
    {
        if (false === is_resource($this->connection = \ftp_connect($host, $port, $timeout)))
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
        if (false === ftp_login($this->getConnection(), $login, $password)) 
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
        if (false === ftp_mkdir($this->getConnection(), $directory))
        {
            $cwd = ftp_pwd($this->getConnection());

            if (false === ftp_chdir($this->getConnection(), $directory))
            {
                throw new \RuntimeException(sprintf('Could not create the %s directory', $directory));
            }

            ftp_chdir($this->getConnection(), $cwd);
        }
        else
        {
            $this->log('Created directory <info>' . $directory . '</info>');
        }

        return true;
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

        if (false === ftp_put($this->getConnection(), $distant, $local, FTP_BINARY))
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
        if (true === is_null($this->connection))
        {    
            throw new \RuntimeException('You are not connected to any FTP server');
        }

        return $this->connection;
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @return \Symfony\Component\Console\Output\OutputInterface
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
}