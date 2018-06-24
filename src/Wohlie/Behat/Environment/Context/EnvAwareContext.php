<?php
/**
 * This file is part of the Behat Environment Extension.
 * (c) Erik Wohllebe <Erik.Wohllebe@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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