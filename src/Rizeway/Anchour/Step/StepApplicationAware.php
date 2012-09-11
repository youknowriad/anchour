<?php
namespace Rizeway\Anchour\Step;

abstract class StepApplicationAware extends Step
{
    private $application;

    public function setApplication($application)
    {
        $this->application = $application;
    }

    public function getApplication()
    {
        if (is_null($this->application)) {
            throw new \Exception(sprintf('The application was not set to the step "%s"', get_class($this)));
        }

        return $this->application;
    }
}
