<?php
namespace Rizeway\Anchour\Config;

use jubianchi\Adapter\Adaptable;

abstract class Resolver extends Adaptable implements ResolverInterface
{
    const VARIABLE_REGEXP = '/%([a-zA-Z]+[a-zA-Z0-9_]*)%/';

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
                        return $values[$matches[0]];
                    },
                    $value
                );
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
        foreach ($array as $key => $value) {
            $key = trim($key);

            if (is_array($value)) {
                $variables += $this->getVariablesToAskInArray($value, $key);
            } elseif (preg_match(static::VARIABLE_REGEXP, $value, $matches)) {
                $variables[$key] = $matches[0];
            }
        }

        return $variables;
    }
}
