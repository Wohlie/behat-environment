<?php
/**
 * This file is part of the Behat Environment Extension.
 * (c) Erik Wohllebe <Erik.Wohllebe@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wohlie\Behat\Environment;

use Wohlie\Behat\Environment\Resolver\Version as VersionResolver;

class Env
{
    /**
     * Holds the current project version.
     *
     * @var string
     */
    protected $projectVersion;

    /**
     * Env constructor.
     *
     * @param VersionResolver $versionResolver
     */
    public function __construct(VersionResolver $versionResolver)
    {
        $this->setProjectVersion($versionResolver->get());
    }

    /**
     * Return the current project version.
     *
     * @return string
     */
    public function getProjectVersion()
    {
        return $this->projectVersion;
    }

    /**
     * Set the given project version.
     *
     * @param string $projectVersion
     * @return $this
     */
    public function setProjectVersion($projectVersion)
    {
        if (!$projectVersion) {
            throw new \UnexpectedValueException("The project version number can't be empty.");
        }

        $this->projectVersion = $projectVersion;
        return $this;
    }
}