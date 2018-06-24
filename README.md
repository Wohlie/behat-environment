# Behat Environment Extension [![License](https://img.shields.io/packagist/l/Wohlie/behat-environment.svg)](https://packagist.org/packages/Wohlie/behat-environment) [![Build Status](https://travis-ci.com/Wohlie/behat-environment.svg?branch=master)](https://travis-ci.com/Wohlie/behat-environment)

Allows to inject the current project environment into a context. The extension currently only supports the version
number.

The project environment can be extracted from the following resources:
  - `package.json`
    - The version will be extracted from the object key `version`
  - `*.property`
    - The version will be extracted from the key `artifact.version`

## Usage

1. Install it:
    
    ```bash
    $ composer require wohlie/behat-environment
    ```

2. Enable this extension and configure Behat to use it:
    
    ```yaml
    # behat.yml
    default:
      # ...
      extensions:
        Wohlie\Behat\Environment:
          property_file: '%paths.base%/package.json'
    ```
    
The parameter `property_file` is required.

3. Implement the interface `\Wohlie\Behat\Environment\Context\EnvAwareContext` in your context class to get the
environment object.