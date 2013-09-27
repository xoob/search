<?php

namespace Unit\Doctrine\Search;

use Doctrine\Search\Configuration;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Configuration
     */
    protected $configuration;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->configuration = new Configuration();
    }

    public function testGetMetadataDriverImpl()
    {
       $metadataDriverImpl = $this->configuration->getMetadataDriverImpl();
       $this->assertInstanceOf('\Doctrine\Search\Mapping\Driver\AnnotationDriver', $metadataDriverImpl);
    }

    public function testSetMetadataDriverImpl()
    {
        $mockedMetadataDriverImpl = $this->getMock('Doctrine\Common\Persistence\Mapping\Driver\MappingDriver'
                                                   , array(), array(), '', false);
        $this->configuration->setMetadataDriverImpl($mockedMetadataDriverImpl);

        $metadataDriverImpl = $this->configuration->getMetadataDriverImpl();
        $this->assertInstanceOf('Doctrine\Common\Persistence\Mapping\Driver\MappingDriver', $metadataDriverImpl);

    }

    public function testSetMetadataCacheImpl()
    {
        $mockedMetadataCacheImpl = $this->getMock('\Doctrine\Common\Cache\Cache');
        $this->configuration->setMetadataCacheImpl($mockedMetadataCacheImpl);

        $cacheMetadataImpl = $this->configuration->getMetadataCacheImpl();
        $this->assertInstanceOf('\Doctrine\Common\Cache\Cache', $cacheMetadataImpl);
    }

    public function testNewDefaultAnnotationDriver()
    {
        $defaultAnnotationDriver = $this->configuration->newDefaultAnnotationDriver();
        $this->assertInstanceOf('\Doctrine\Search\Mapping\Driver\AnnotationDriver', $defaultAnnotationDriver);
    }

    public function testSetClassMetadataFactoryName()
    {
        $this->configuration = new Configuration();
        $this->configuration->setClassMetadataFactoryName('test');
        $this->assertEquals('test', $this->configuration->getClassMetadataFactoryName());
    }


    public function testGetClassMetadataFactoryName()
    {
        $this->configuration = new Configuration();
        $className = $this->configuration->getClassMetadataFactoryName();
        $this->assertEquals($className, 'Doctrine\Search\Mapping\ClassMetadataFactory');
    }

    public function testSetGetNamingStrategy()
    {
        $this->assertInstanceOf('Doctrine\Search\Mapping\NamingStrategy\NamingStrategyInterface', $this->configuration->getNamingStrategy());
        $namingStrategy = $this->getMock('Doctrine\Search\Mapping\NamingStrategy\Dash');
        $this->configuration->setNamingStrategy($namingStrategy);
        $this->assertSame($namingStrategy, $this->configuration->getNamingStrategy());
    }
}
