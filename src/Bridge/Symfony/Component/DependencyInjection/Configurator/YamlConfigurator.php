<?php

/*
 * This file is part of the ReactPHP Foundation <https://github.com/itnelo/reactphp-foundation>.
 *
 * (c) 2020 Pavel Petrov <itnelo@gmail.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license https://opensource.org/licenses/mit MIT
 */

declare(strict_types=1);

namespace Foundation\Bridge\Symfony\Component\DependencyInjection\Configurator;

use Exception;
use Foundation\Bridge\Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use LogicException;
use RuntimeException;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\EnvPlaceholderParameterBag;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Dotenv\Exception\FormatException as EnvFileFormatException;
use Symfony\Component\Dotenv\Exception\PathException as EnvFilePathException;
use Symfony\Component\Yaml\Exception\ParseException as YamlFileParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * Configures a container instance according to passed environment variables, parameters and service definitions.
 * Uses Dotenv library to load environment variables and expects YAML format for configuration files.
 * Doesn't perform any sort of premature compilation for the container.
 *
 * It is designed to encapsulate boilerplate code for different entrypoints of application which utilizes
 * Symfony's DI container separately from the Kernel. Usage example:
 *
 * ```
 * $containerConfigurator = new YamlConfigurator(new \Symfony\Component\Config\FileLocator());
 * $containerConfigurator->setEnvironmentFilePath(__DIR__ . '/../.env');
 * $containerConfigurator->setParameterFilePath(__DIR__ . '/../config/parameters.yml');
 * $containerConfigurator->setDefinitionFilePaths([__DIR__ . '/../config/services.yml']);
 * $containerConfigurator->setDefinitionDefaultsFilePath(__DIR__ . '/../config/services.yml');
 *
 * $container = $containerConfigurator->getContainerBuilder();
 * // continue container compiling.
 * ```
 */
class YamlConfigurator
{
    /**
     * Finds file by the given logical path
     *
     * @var FileLocatorInterface
     */
    private FileLocatorInterface $fileLocator;

    /**
     * Path to file with environment variables
     *
     * @var string|null
     */
    private ?string $environmentFilePath;

    /**
     * Path to file with configuration parameters
     *
     * @var string|null
     */
    private ?string $parameterFilePath;

    /**
     * A set of file paths where service definitions are stored
     *
     * @var iterable|null
     */
    private ?iterable $definitionFilePaths;

    /**
     * Path to file with shared "_defaults" option set for all service definitions (will not override if that node
     * is already exists in the target file, see YamlFileLoader from the "Bridge\Symfony" scope)
     *
     * @var string|null
     *
     * @see YamlFileLoader::loadFile
     */
    private ?string $definitionDefaultsFilePath;

    /**
     * YamlConfigurator constructor.
     *
     * @param FileLocatorInterface $fileLocator Finds file by the given logical path
     */
    public function __construct(FileLocatorInterface $fileLocator)
    {
        $this->fileLocator = $fileLocator;

        $this->environmentFilePath        = null;
        $this->parameterFilePath          = null;
        $this->definitionFilePaths        = null;
        $this->definitionDefaultsFilePath = null;
    }

    /**
     * Returns ContainerBuilder instance with applied parameters and service definitions
     *
     * @return ContainerBuilder
     *
     * @throws EnvFilePathException   When a file with environment variables doesn't exist or isn't readable
     * @throws EnvFileFormatException When a file with environment variables has a syntax error
     * @throws YamlFileParseException If the file with configuration parameters couldn't be read or the YAML isn't valid
     * @throws Exception              If an error of any other type has been occurred during container configuration
     */
    public function getContainerBuilder(): ContainerBuilder
    {
        if (is_string($this->environmentFilePath) && file_exists($this->environmentFilePath)) {
            if (!class_exists(Dotenv::class)) {
                throw new LogicException(
                    'Install Dotenv component to be able to load environment variables from the env-type files.'
                );
            }

            $dotenv = new Dotenv();

            // will populate env variables from env-type files (only if they're not already set).
            $dotenv->loadEnv($this->environmentFilePath);
        }

        $parameters = [];

        if (is_string($this->parameterFilePath)) {
            $configuration = Yaml::parseFile($this->parameterFilePath);
            $parameters    = $configuration['parameters'] ?? [];
        }

        $parameterBag     = new EnvPlaceholderParameterBag($parameters);
        $containerBuilder = new ContainerBuilder($parameterBag);

        if (is_iterable($this->definitionFilePaths)) {
            if (!is_string($this->definitionDefaultsFilePath)) {
                throw new RuntimeException(
                    'Path to file with fallback "_defaults" option set (definitionDefaultsFilePath) ' .
                    'must be explicitly specified alongside service definitions if they have added.'
                );
            }

            // loading service definitions.
            $definitionLoader = new YamlFileLoader(
                $containerBuilder,
                $this->fileLocator,
                $this->definitionDefaultsFilePath
            );

            foreach ($this->definitionFilePaths as $definitionFilePath) {
                $definitionLoader->load($definitionFilePath);
            }
        }

        return $containerBuilder;
    }

    /**
     * Sets path to file with environment variables
     *
     * @param string $environmentFilePath Path to file with environment variables
     *
     * @return void
     */
    public function setEnvironmentFilePath(string $environmentFilePath): void
    {
        $this->environmentFilePath = $environmentFilePath;
    }

    /**
     * Sets path to file with configuration parameters
     *
     * @param string $parameterFilePath Path to file with configuration parameters
     *
     * @return void
     */
    public function setParameterFilePath(string $parameterFilePath): void
    {
        $this->parameterFilePath = $parameterFilePath;
    }

    /**
     * Sets a set of file paths where service definitions are stored
     *
     * @param iterable $definitionFilePaths A set of file paths where service definitions are stored
     *
     * @return void
     */
    public function setDefinitionFilePaths(iterable $definitionFilePaths): void
    {
        $this->definitionFilePaths = $definitionFilePaths;
    }

    /**
     * Sets path to file with shared "_defaults" option set for service definitions
     *
     * @param string $definitionDefaultsFilePath Path to file with shared "_defaults" option set for service definitions
     *
     * @return void
     */
    public function setDefinitionDefaultsFilePath(string $definitionDefaultsFilePath): void
    {
        $this->definitionDefaultsFilePath = $definitionDefaultsFilePath;
    }
}
