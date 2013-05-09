<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Zend\Di\Definition\Builder;

use Zend\Di\Di;

/**
 * Definitions for an injection endpoint method
 */
class InjectionMethod
{
    /**
     * @var string|null
     */
    protected $name = null;

    /**
     * @var array
     */
    protected $parameters = array();

    /**
     * @param  string|null $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param  string          $name
     * @param  string|null     $class
     * @param  mixed|null      $isRequired
     * @param  mixed|null      $default
     * @return InjectionMethod
     */
    public function addParameter($name, $class = null, $isRequired = null, $default = null)
    {
        if (is_bool($isRequired)) {
            $methodRequirementType = $isRequired ? Di::METHOD_IS_REQUIRED : Di::METHOD_IS_OPTIONAL;
        }

        if (null === $isRequired) {
            $methodRequirementType = Di::METHOD_IS_REQUIRED;
        }

        if (is_int($isRequired)) {
            $methodRequirementType = $isRequired;
        }

        if (is_string($isRequired)) {
            switch (strtolower($isRequired)) {
                case "require":
                case "required":
                    $methodRequirementType = Di::METHOD_IS_REQUIRED;
                    break;
                case "aware":
                    $methodRequirementType = Di::METHOD_IS_AWARE;
                    break;
                case "optional":
                    $methodRequirementType = Di::METHOD_IS_OPTIONAL;
                    break;
                case "constructor":
                    $methodRequirementType = Di::MEHTOD_IS_CONSTRUCTOR;
                    break;
                case "instantiator":
                    $methodRequirementType = Di::METHOD_IS_INSTANTIATOR;
                    break;
            }
        }
        $this->parameters[] = array(
            $name,
            $class,
            $methodRequirementType,
            $default,
        );

        return $this;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     *
     * @param mixed $requirement
     * @return int
     */
    public static function detectMethodRequirement($requirement)
    {
        if (is_bool($requirement)) {
            return $requirement ? Di::METHOD_IS_REQUIRED : Di::METHOD_IS_OPTIONAL;
        }

        if (null === $requirement) {
            //This is mismatch to ClassDefinition::addMethod is it ok ? is optional?
            return Di::METHOD_IS_REQUIRED;
        }

        if (is_int($requirement)) {
            return $requirement;
        }

        if (is_string($requirement)) {
            switch (strtolower($requirement)) {
                default:
                case "require":
                case "required":
                    return Di::METHOD_IS_REQUIRED;
                    break;
                case "aware":
                    return Di::METHOD_IS_AWARE;
                    break;
                case "optional":
                    return Di::METHOD_IS_OPTIONAL;
                    break;
                case "constructor":
                    return Di::MEHTOD_IS_CONSTRUCTOR;
                    break;
                case "instantiator":
                    return Di::METHOD_IS_INSTANTIATOR;
                    break;
            }
        }
        return 0;
    }
}
