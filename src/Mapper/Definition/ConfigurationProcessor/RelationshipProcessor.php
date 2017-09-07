<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition\ConfigurationProcessor;

use Mikemirten\Component\JsonApi\Mapper\Definition\Definition;
use Mikemirten\Component\JsonApi\Mapper\Definition\Relationship;

/**
 * Processor of relationships
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Definition\AnnotationProcessor
 */
class RelationshipProcessor extends AbstractProcessor
{
    /**
     * {@inheritdoc}
     */
    public function process(array $config, Definition $definition)
    {
        if (! isset($config['relationships'])) {
            return;
        }

        $reflection = new \ReflectionClass($definition->getClass());

        foreach ($config['relationships'] as $name => $data)
        {
            $relationship = $this->createRelationship($reflection, $name, $data);

            $definition->addRelationship($relationship);
        }
    }

    /**
     * Process relationship
     *
     * @param  \ReflectionClass $reflection
     * @param  string           $name
     * @param  array            $data
     * @return Relationship
     */
    protected function createRelationship(\ReflectionClass $reflection, string $name, array $data): Relationship
    {
        $getter = isset($data['getter'])
            ? $data['getter']
            : $this->resolveGetter($reflection, $name);

        $type = ($data['type'] === 'one')
            ? Relationship::TYPE_X_TO_ONE
            : Relationship::TYPE_X_TO_MANY;

        $relationship = new Relationship($name, $type, $getter);

        $relationship->setIncludeData($data['dataAllowed']);
        $relationship->setDataLimit($data['dataLimit']);

        $this->handleLinks($data, $relationship);

        return $relationship;
    }

    /**
     * Handle links
     *
     * @param array        $data
     * @param Relationship $relationship
     */
    protected function handleLinks(array $data, Relationship $relationship)
    {
        if (! isset($data['links'])) {
            return;
        }

        foreach ($data['links'] as $linkName => $linkData)
        {
            $link = $this->createLink($linkName, $linkData);

            $relationship->addLink($link);
        }
    }
}