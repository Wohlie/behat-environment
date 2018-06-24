<?php
/**
 * This file is part of the Behat Environment Extension.
 * (c) Erik Wohllebe <Erik.Wohllebe@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wohlie\Behat\Environment\Context\Initializer;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use Wohlie\Behat\Environment\Context\EnvAwareContext;
use Wohlie\Behat\Environment\Env;

class EnvAwareInitializer implements ContextInitializer
{
    /**
     * Holds the environment.
     *
     * @var Env
     */
    protected $env;

    /**
     * EnvAwareInitializer constructor.
     *
     * @param Env $env
     */
    public function __construct(Env $env)
    {
        $this->env = $env;
    }

    /**
     * Initializes provided context.
     *
     * @param Context $context
     */
    public function initializeContext(Context $context)
    {
        if (!$context instanceof EnvAwareContext) {
            return;
        }

        $context->setEnv($this->env);
    }
}