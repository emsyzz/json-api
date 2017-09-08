<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition\ConfigurationProcessor;

use Mikemirten\Component\JsonApi\Mapper\Definition\Definition;
use Mikemirten\Component\JsonApi\Mapper\Definition\Attribute;

/**
 * Processor of attributes
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Definition\AnnotationProcessor
 */
class AttributeProcessor extends AbstractProcessor
{
    /**
     * Pattern of "type" parameter of attribute annotation
     */
    const DATATYPE_PATTERN = '~^(?<type>[a-z_][a-z0-9_]*(?:\.[a-z_][a-z0-9_]*)*)\s*(?:\((?<params>[^\)]*)\))?(?<many>\[\])?$~i';

    /**
     * {@inheritdoc}
     */
    public function process(array $config, Definition $definition)
    {
        if (! isset($config['attributes'])) {
            return;
        }

        $reflection = new \ReflectionClass($definition->getClass());

        foreach ($config['attributes'] as $name => $data)
        {
            $attribute = $this->createAttribute($reflection, $name, $data);

            $definition->addAttribute($attribute);
        }
    }

    /**
     * Create attribute
     *
     * @param  string $name
     * @param  array  $data
     * @return Attribute
     */
    protected function createAttribute(\ReflectionClass $reflection, string $name, array $data): Attribute
    {
        $getter = isset($data['getter'])
            ? $data['getter']
            : $this->resolveGetter($reflection, $name);

        $setter = isset($data['setter'])
            ? $data['setter']
            : $this->resolveSetter($reflection, $name);

        $attribute = new Attribute($name, $getter);

        if ($setter !== null) {
            $attribute->setSetter($setter);
        }

        $this->processAttributeOptions($data, $attribute);

        return $attribute;
    }

    /**
     * Process optional properties of attribute
     *
     * @param array     $data
     * @param Attribute $attribute
     */
    protected function processAttributeOptions(array $data, Attribute $attribute)
    {
        if (isset($data['type'])) {
            $this->processDataType($data['type'], $attribute);
        }

        if (isset($data['many'])) {
            $attribute->setMany($data['many']);
        }

        if (isset($data['processNull'])) {
            $attribute->setProcessNull($data['processNull']);
        }
    }

    /**
     * Process data-type
     *
     * @param string    $definition
     * @param Attribute $attribute
     */
    protected function processDataType(string $definition, Attribute $attribute)
    {
        if (! preg_match(self::DATATYPE_PATTERN, $definition, $matches)) {
            throw new \LogicException(sprintf('Data-type definition "%s" is invalid.', $definition));
        }

        $attribute->setType($matches['type']);

        if (! empty($matches['many'])) {
            $attribute->setMany();
        }

        if (empty($matches['params'])) {
            return;
        }

        $parameters = explode(',', $matches['params']);
        $parameters = array_map('trim', $parameters);

        $attribute->setTypeParameters($parameters);
    }
}