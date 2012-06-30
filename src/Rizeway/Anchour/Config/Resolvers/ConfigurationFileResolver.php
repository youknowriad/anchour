<?php
namespace Rizeway\Anchour\Config\Resolvers;

use Symfony\Component\Yaml\Yaml;

use jubianchi\Adapter\AdapterInterface;

use Rizeway\Anchour\Config\ConfigurableInterface;
use Rizeway\Anchour\Config\Resolver;

class ConfigurationFileResolver extends Resolver
{
    /**
     * @var array
     */
    private $config;

    /**
     * @param string $filename
     */
    public function __construct(\SplFileInfo $file, AdapterInterface $adapter = null)
    {
        $this->setAdapter($adapter);

        if (false === $file->isFile()) {
            throw new \RuntimeException(sprintf('File %s does not exist', $file->getRealPath()));
        }

        switch ($file->getExtension()) {
            case 'yml':
                $this->config = Yaml::parse($file->getRealPath());
                break;
            case 'ini':
                $this->config = $this->getAdapter()->parse_ini_file($file->getRealPath());
                break;
            case 'json':
                $this->config = json_decode($this->getAdapter()->file_get_contents($file->getRealPath()), true);
                break;
        }
    }

    /**
     * @param Command $configurable
     */
    public function resolve(ConfigurableInterface $configurable)
    {
        return $this->replaceValuesInRecursiveArray($configurable->getConfig(), $this->config);
    }
}
