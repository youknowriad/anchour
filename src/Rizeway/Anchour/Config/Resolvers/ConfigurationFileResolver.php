<?php
namespace Rizeway\Anchour\Config\Resolvers;

use Symfony\Component\Yaml\Yaml;

use jubianchi\Adapter\AdapterInterface;

use Rizeway\Anchour\Config\ConfigurableInterface;
use Rizeway\Anchour\Config\Resolver;

class ConfigurationFileResolver extends Resolver
{
    /**
     * @var \SplFileInfo
     */
    private $file;

    /**
     * @param \SplFileInfo $file
     * @param \jubianchi\Adapter\AdapterInterface $adapter
     *
     * @throws \RuntimeException
     */
    public function __construct(\SplFileInfo $file, AdapterInterface $adapter = null)
    {
        $this->setAdapter($adapter);

        if (false === $file->isFile()) {
            throw new \RuntimeException(sprintf('File %s does not exist', $file->getRealPath()));
        }

        $this->file = $file;
    }

    /**
     * @param \Rizeway\Anchour\Config\ConfigurableInterface $command
     *
     * @return array
     */
    public function getValues(ConfigurableInterface $command) {
        $values = array();

        switch ($this->file->getExtension()) {
            case 'yml':
                $values = Yaml::parse($this->file->getRealPath());
                break;
            case 'ini':
                $values = $this->getAdapter()->parse_ini_file($this->file->getRealPath());
                break;
            case 'json':
                $values = json_decode($this->getAdapter()->file_get_contents($this->file->getRealPath()), true);
                break;
        }

        return (array)$values;
    }
}
