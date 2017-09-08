<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition;

use Mikemirten\Component\JsonApi\Mapper\Definition\ConfigurationProcessor\ConfigurationProcessorInterface;
use Mikemirten\Component\JsonApi\Mapper\Definition\Exception\DefinitionNotFoundException;
use Mikemirten\Component\JsonApi\Mapper\Definition\Exception\DefinitionProviderException;
use Symfony\Component\Config\Definition\ConfigurationInterface as Configuration;
use Symfony\Component\Config\Definition\NodeInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Parser;

/**
 * YAML-config based definition provider
 *
 *                  +--------+                +-------------+
 *                  | Schema |->-+        +-->| Processor A |--+
 *                  +--------+   |        |   +-------------+  |
 *                               |        |                    |
 * +------+   +--------+   +-----------+  |   +-------------+  |   +------------+
 * | File |-->| Parser |-->| Config    |--+-->| Processor B |--+-->| Definition |
 * +------+   +--------+   | processor |      +-------------+      +------------+
 *                         +-----------+
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Definition
 */
class YamlDefinitionProvider implements DefinitionProviderInterface
{
    /**
     * Path to directory with mapping definitions
     *
     * @var string
     */
    protected $basePath;

    /**
     * YAML-parser
     *
     * @var Parser
     */
    protected $parser;

    /**
     * Configuration processor
     * Handles schema, normalization and default values
     *
     * @var Processor
     */
    protected $processor;

    /**
     * Configuration
     * Provides schema of mapping definition
     *
     * @var Configuration
     */
    protected $config;

    /**
     * Lazy initialized schema of mapping definition
     *
     * @var NodeInterface
     */
    private $schema;

    /**
     * Registered configuration processors
     * Each processor handles certain part of definition
     *
     * @var ConfigurationProcessorInterface[]
     */
    private $processors = [];

    /**
     * Cache of created definitions
     *
     * @var array
     */
    private $definitionCache = [];

    /**
     * YamlDefinitionProvider constructor.
     *
     * @param string        $basePath
     * @param Parser        $parser
     * @param Processor     $processor
     * @param Configuration $config
     */
    public function __construct(string $basePath, Parser $parser, Processor $processor, Configuration $config)
    {
        $this->basePath  = $basePath;
        $this->parser    = $parser;
        $this->processor = $processor;
        $this->config    = $config;
    }

    /**
     * Register configuration processor
     *
     * @param ConfigurationProcessorInterface $processor
     */
    public function registerProcessor(ConfigurationProcessorInterface $processor)
    {
        $this->processors[] = $processor;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefinition(string $class): Definition
    {
        if (! isset($this->definitionCache[$class])) {
            $this->definitionCache[$class] = $this->createDefinition($class);
        }

        return $this->definitionCache[$class];
    }

    /**
     * Create definition
     *
     * @param  string $class
     * @return Definition
     */
    protected function createDefinition(string $class): Definition
    {
        $path = $this->basePath . DIRECTORY_SEPARATOR . str_replace('\\', '.', $class) . '.yml';

        if (! is_file($path)) {
            throw new DefinitionNotFoundException($class);
        }

        $config = $this->readConfig($path);
        $definition = new Definition($class);

        foreach ($this->processors as $processor)
        {
            $processor->process($config, $definition);
        }

        return $definition;
    }

    /**
     * Read configuration of mapping definition
     *
     * @param  string $path
     * @return array
     */
    protected function readConfig(string $path): array
    {
        if (! is_readable($path)) {
            throw new DefinitionProviderException(sprintf('File "%s" with definition is not readable.', $path));
        }

        $content = file_get_contents($path);
        $config  = $this->parser->parse($content);
        $schema  = $this->getSchema();

        return $this->processor->process($schema, [$config]);
    }

    /**
     * Get schema of mapping definition
     *
     * @return NodeInterface
     */
    protected function getSchema(): NodeInterface
    {
        if ($this->schema === null) {
            $this->schema = $this->config->getConfigTreeBuilder()->buildTree();
        }

        return $this->schema;
    }
}