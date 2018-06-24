<?php
/**
 * This file is part of the Behat Environment Extension.
 * (c) Erik Wohllebe <Erik.Wohllebe@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wohlie\Behat\Environment\Resolver;

use xformat\Properties;

/**
 * Class BootstrapFileService
 *
 * @package Wohlie\Behat\Environment
 */
class Version
{
    /**
     * Holds the current file path.
     *
     * @var string
     */
    protected $filePath;

    /**
     * Version constructor.
     *
     * @param string $filePath
     */
    public function __construct($filePath)
    {
        $this->setFilePath($filePath);
    }

    /**
     * Parse the property file to extract the version.
     *
     * @return string
     */
    public function get()
    {
        $version  = null;
        $filePath = $this->getFilePath();

        if ($this->isPackageJson($filePath)) {
            $json = json_decode(file_get_contents($filePath), true);
            if (!empty($json['version'])) {
                $version = $json['version'];
            }
        } elseif ($this->isPropertyFile($filePath)) {
            $properties = new Properties();
            $properties = $properties->getProperties($filePath);
            if (!empty($properties['artifact.version'])) {
                $version = $properties['artifact.version'];
            }
        } else {
            throw new \UnexpectedValueException("The property file '{$filePath}' is not supported.");
        }

        if (!$version) {
            throw new \UnexpectedValueException(
                "The version can not determine form the property file '{$filePath}'."
            );
        }

        return $version;
    }

    /**
     * Return true if the given file can be parsed.
     *
     * @param string $filePath
     * @return bool
     */
    public function isValid($filePath)
    {
        return $this->isFile($filePath) && ($this->isPropertyFile($filePath) || $this->isPackageJson($filePath));
    }

    /**
     * Return true if the given file path can be handel as file.
     *
     * @param string $filePath
     * @return bool
     */
    protected function isFile($filePath)
    {
        return file_exists($filePath) && is_file($filePath) && is_readable($filePath);
    }

    /**
     * Return true if the given file is a package json.
     *
     * @param string $filePath
     * @return bool
     */
    protected function isPackageJson($filePath)
    {
        return 'package.json' === strtolower(pathinfo($filePath, PATHINFO_BASENAME));
    }

    /**
     * Return true if the given file is a property file.
     *
     * @param string $filePath
     * @return bool
     */
    protected function isPropertyFile($filePath)
    {
        return 'property' === strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    }

    /**
     * Set the given file path to resolve the version.
     *
     * @param string $filePath
     * @return $this
     */
    public function setFilePath($filePath)
    {
        if (!$this->isValid($filePath)) {
            throw new \RuntimeException("The given file '{$filePath}' does not exists.");
        }

        $this->filePath = $filePath;
        return $this;
    }

    /**
     * Return the file path.
     *
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }
}