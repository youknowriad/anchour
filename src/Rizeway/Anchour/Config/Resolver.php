<?php
namespace Rizeway\Anchour\Config;

use jubianchi\Adapter\Adaptable;

use Rizeway\Anchour\Config\ConfigurableInterface;

abstract class Resolver extends Adaptable implements ResolverInterface
{
    const VARIABLE_REGEXP = '/%([a-zA-Z][a-zA-Z0-9_]*)%/';

    protected $resolved_values = array();

    /**
     * Replace all %option% in the $array with their values in $values
     *
     * @param mixed[]  $array
     * @param string[] $values
     *
     * @return mixed[]
     */
    public function replaceValuesInRecursiveArray($array, $values)
    {
        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result[$key] = $this->replaceValuesInRecursiveArray($value, $values);
            } else {
                $result[$key] = $value;

                $result[$key] = preg_replace_callback(
                    static::VARIABLE_REGEXP,
                    function($matches) use($values) {
                        return isset($values[$matches[1]]) ? $values[$matches[1]] : $matches[0];
                    },
                    $value
                );

                $result[$key] = str_replace('\%', '%', $result[$key]);
            }
        }

        return $result;
    }

    /**
     * Get The variables to ask %var% from a recursive array
     *
     * @param mixed[] $array
     *
     * @return string[]
     */
    public function getVariablesToAskInArray($array)
    {
        $variables = array();
        foreach ($array as $value) {
            if (is_array($value)) {
                $variables += $this->getVariablesToAskInArray($value);
            } elseif (preg_match_all(static::VARIABLE_REGEXP, $value, $matches)) {
                foreach($matches[0] as $key => $match) {
                    if (!isset($this->resolved_values[$matches[1][$key]])) {
                        $variables[$matches[1][$key]] = $match;
                    }
                }
            }
        }

        return array_unique($variables);
    }

    /**
     * Get Required Parameters From Prompt
     *
     * @param \Rizeway\Anchour\Config\ConfigurableInterface $command
     *
     * @return array
     */
    public function resolve(ConfigurableInterface $command)
    {
        $resolved_values = $this->getValues($command) + $this->resolved_values;
        $values = $this->replaceValuesInRecursiveArray($command->getConfig(), $resolved_values);
        $command->setConfig($values);

        return $resolved_values;
    }

    public function setResolvedValues($resolved_values)
    {
        $this->resolved_values = $resolved_values;
    }

    public function getResolvedValues()
    {
        return $this->resolved_values;
    }
}
