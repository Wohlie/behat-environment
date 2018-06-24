<?php
/**
 * This file is part of the Behat Environment Extension.
 * (c) Erik Wohllebe <Erik.Wohllebe@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wohlie\Behat\Environment\ServiceContainer;

use Behat\Behat\Context\ServiceContainer\ContextExtension;
use Behat\Testwork\ServiceContainer\Extension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class EnvironmentExtension implements Extension
{
    const ID_ENV = 'ds_env';
    const ID_ENV_VERSION_RESOLVER = 'ds_env.version_resolver';
    const ID_ENV_CONTEXT_INITIALIZER = 'ds_env.context_initializer';

    /**
     * Returns the extension config key.
     *
     * @return string
     */
    public function getConfigKey()
    {
        return 'ds_env';
    }

    /**
     * Setups configuration for the extension.
     *
     * @param ArrayNodeDefinition $builder
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        //@formatter:off
        $builder
            ->children()
                ->scalarNode('property_file')
                ->info('Path to property file which can be a *.property or package.json file.')
                ->validate()
                ->ifEmpty()
                ->thenInvalid("Failed to find file.")
                ->end()
            ->end();
        //@formatter:on
    }

    /**
     * Loads extension services into temporary container.
     *
     * @param ContainerBuilder $container
     * @param array            $config
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $this->loadVersionResolver($container, $config);
        $this->loadEnv($container);
        $this->loadContextInitializer($container);
    }

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
    }

    /**
     * Initializes other extensions.
     *
     * This method is called immediately after all extensions are activated but
     * before any extension `configure()` method is called. This allows extensions
     * to hook into the configuration of other extensions providing such an
     * extension point.
     *
     * @param ExtensionManager $extensionManager
     */
    public function initialize(ExtensionManager $extensionManager)
    {
    }

    protected function loadVersionResolver(ContainerBuilder $container, array $config)
    {
        $container->setDefinition(
            self::ID_ENV_VERSION_RESOLVER,
            new Definition(
                'Wohlie\Behat\Environment\Resolver\Version',
                [
                    $config['property_file'],
                ]
            )
        );
    }

    /**
     * Initialize the environment.
     *
     * @param ContainerBuilder $container
     */
    protected function loadEnv(ContainerBuilder $container)
    {
        $container->setDefinition(
            self::ID_ENV,
            new Definition(
                'Wohlie\Behat\Environment\Env',
                [
                    new Reference(self::ID_ENV_VERSION_RESOLVER),
                ]
            )
        );
    }

    /**
     * Define the context initializer.
     *
     * @param ContainerBuilder $container
     */
    protected function loadContextInitializer(ContainerBuilder $container)
    {
        $definition = new Definition(
            'Wohlie\Behat\Environment\Context\Initializer\EnvAwareInitializer',
            [
                new Reference(self::ID_ENV),
            ]
        );
        $definition->addTag(ContextExtension::INITIALIZER_TAG, ['priority' => 0]);
        $container->setDefinition(self::ID_ENV_CONTEXT_INITIALIZER, $definition);
    }
}
