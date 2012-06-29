<?php
namespace Rizeway\Anchour\Config\Resolvers;

use Symfony\Component\Yaml\Yaml;

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
    public function __construct($filename)
    {
        if (false == file_exists($filename)) {
            throw new \RuntimeException(sprintf('File %s does not exist', $filename));
        }

        $info = pathinfo($filename);
        switch ($info['extension']) {
            case 'yml':
                $this->config = Yaml::parse($filename);
                break;
            case 'ini':
                $this->config = parse_ini_file($filename);
                break;
            case 'json':
                $this->config = json_decode(file_get_contents($filename), true);
                break;
        }
    }

    /**
     * Get Required Parameters From File
     *
     * @param Command $command
     */
    public function resolve(ConfigurableInterface $command)
    {
        return $this->replaceValuesInRecursiveArray($command->getConfig(), $this->config);
    }
}
