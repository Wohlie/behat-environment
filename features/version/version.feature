Feature: Test that the version will be read properly out of the environment.

  Background:
    Given a context file "features/bootstrap/FeatureContext.php" containing:
      """
      <?php

      use Behat\Behat\Context\Context;
      use Wohlie\Behat\Environment\Context\EnvAwareContext;
      use Wohlie\Behat\Environment\Env;

      class FeatureContext implements Context, EnvAwareContext
      {
          /**
           * Holds the environment.
           *
           * @var Env|null
           */
          protected $env = null;

          /** @Then it passes */
          public function itPasses() {}

          /** @Then /^it print the project version$/ */
          public function printTheProjectVersion()
          {
              echo $this->getEnv()->getProjectVersion();
          }

          /**
           * Set the environment.
           *
           * @param Env $env
           * @return $this
           */
          public function setEnv(Env $env)
          {
              $this->env = $env;
              return $this;
          }

          /**
           * Return the current environment.
           *
           * @return Env|null
           */
          public function getEnv()
          {
              return $this->env;
          }
      }
      """
    Given a package.json file "features/version/fixtures/package.json" containing:
      """
      {
        "version": "6.2.4"
      }
      """
    Given a build.property file "features/version/fixtures/build.property" containing:
      """
      # artifact properties
      artifact.group=behat
      artifact.name=release
      artifact.version=2.28.0
      """
    Given a build.property file "features/version/fixtures/build-no-version.property" containing:
      """
      # artifact properties
      artifact.group=behat
      artifact.name=release
      """

  Scenario: It prints the right version from the package.json.
    Given a Behat configuration containing:
      """
      default:
        suites:
          default:
            contexts:
            - FeatureContext
        extensions:
          Wohlie\Behat\Environment:
            property_file: %paths.base%/features/version/fixtures/package.json
            """
    Given a feature file containing:
            """
      Feature: Passing feature

        Scenario: Print the project version
          Then it print the project version
      """
    When I run Behat
    Then it should pass with "6.2.4"

  Scenario: It prints the right version from the build.property.
    Given a Behat configuration containing:
      """
      default:
        suites:
          default:
            contexts:
            - FeatureContext
        extensions:
          Wohlie\Behat\Environment:
            property_file: %paths.base%/features/version/fixtures/build.property
            """
    Given a feature file containing:
            """
      Feature: Passing feature

        Scenario: Print the project version
          Then it print the project version
      """
    When I run Behat
    Then it should pass with "2.28.0"

  Scenario: It fails if the package.json has an empty version.
    Given a package.json file "features/version/fixtures/package.json" containing:
      """
      {
        "version": ""
      }
      """
    Given a Behat configuration containing:
      """
      default:
        suites:
          default:
            contexts:
            - FeatureContext
        extensions:
          Wohlie\Behat\Environment:
            property_file: %paths.base%/features/version/fixtures/package.json
            """
    Given a feature file containing:
            """
      Feature: Failing feature

        Scenario: Missing property_file config
          Then it passes
      """
    When I run Behat
    Then it should fail with "The version can not determine form the property file"

  Scenario: It fails if the build.property has an empty version.
    Given a Behat configuration containing:
      """
      default:
        suites:
          default:
            contexts:
            - FeatureContext
        extensions:
          Wohlie\Behat\Environment:
            property_file: %paths.base%/features/version/fixtures/build-no-version.property
            """
    Given a feature file containing:
            """
      Feature: Failing feature

        Scenario: Missing property_file config
          Then it passes
      """
    When I run Behat
    Then it should fail with "The version can not determine form the property file"

  Scenario: It fails if the config property_file is missing.
    Given a Behat configuration containing:
      """
      default:
        suites:
          default:
            contexts:
            - FeatureContext
        extensions:
          Wohlie\Behat\Environment:
            """
    Given a feature file containing:
            """
      Feature: Failing feature

        Scenario: Missing property_file config
          Then it passes
      """
    When I run Behat
    Then it should fail with "does not exists"

  Scenario: It fails if the config property_file is missing.
    Given a Behat configuration containing:
      """
      default:
        suites:
          default:
            contexts:
            - FeatureContext
        extensions:
          Wohlie\Behat\Environment:
            property_file: %paths.base%/features/version/fixtures/not-existing.property
            """
    Given a feature file containing:
            """
      Feature: Failing feature

        Scenario: Missing property_file config
          Then it passes
      """
    When I run Behat
    Then it should fail with "does not exists"
