<?php

namespace Wohlie\Behat\Environment\Context;

use Wohlie\Behat\Environment\Env;

interface EnvAwareContext
{
    /**
     * Set teh environment.
     *
     * @param Env $env
     * @return $this
     */
    public function setEnv(Env $env);

    /**
     * Return the current environment.
     *
     * @return Env
     */
    public function getEnv();
}