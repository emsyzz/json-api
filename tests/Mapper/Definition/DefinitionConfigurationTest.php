<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

/**
 * @group   mapper
 * @package Mikemirten\Component\JsonApi\Mapper\Definition
 */
class DefinitionConfigurationTest extends TestCase
{
    /**
     * @dataProvider getTestConfig
     *
     * @param array $source
     * @param array $expected
     */
    public function testConfiguration(array $source, array $expected)
    {
        $configuration = new DefinitionConfiguration();
        $processor     = new Processor();

        $result = $processor->processConfiguration($configuration, [$source]);

        $this->assertEquals($expected, $result);
    }

    /**
     * Get test config
     *
     * @return array
     */
    public function getTestConfig(): array
    {
        $fullConfig = $this->getFullConfig();
        $minConfig  = $this->getMinimumConfig();

        return [
            [ $fullConfig, $fullConfig ],
            [ $minConfig,  $fullConfig ]
        ];
    }

    /**
     * Config with all possible values
     *
     * @return array
     */
    protected function getFullConfig(): array
    {
        return [
            'type'  => 'user',
            'links' => [
                'self' => [
                    'resource'   => 'application.users',
                    'parameters' => ['id' => '@id'],
                    'metadata'   => ['method' => 'GET']
                ]
            ],

            'attributes' => [
                'firstName' => [
                    'type'        => 'string',
                    'getter'      => 'getFirstName',
                    'setter'      => 'setFirstName',
                    'processNull' => false
                ]
            ],

            'relationships' => [
                'roles' => [
                    'type'        => 'one',
                    'getter'      => 'getRole',
                    'dataAllowed' => false,
                    'dataLimit'   => 0,
                    'links' => [
                        'self' => [
                            'resource'   => 'application.users',
                            'parameters' => ['id' => '@id'],
                            'metadata'   => ['method' => 'GET']
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * Config without optional values has default values defined by schema
     *
     * @return array
     */
    protected function getMinimumConfig(): array
    {
        return [
            'type'  => 'user',
            'links' => [
                'self' => [
                    'resource'   => 'application.users',
                    'parameters' => ['id' => '@id'],
                    'metadata'   => ['method' => 'GET']
                ]
            ],

            'attributes' => [
                'firstName' => [
                    'type'        => 'string',
                    'getter'      => 'getFirstName',
                    'setter'      => 'setFirstName'
                ]
            ],

            'relationships' => [
                'roles' => [
                    'getter' => 'getRole',
                    'links'  => [
                        'self' => [
                            'resource'   => 'application.users',
                            'parameters' => ['id' => '@id'],
                            'metadata'   => ['method' => 'GET']
                        ]
                    ]
                ]
            ]
        ];
    }
}