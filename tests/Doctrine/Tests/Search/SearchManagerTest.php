<?php

namespace Doctrine\Tests\Search;

class SearchManagerTest extends \Doctrine\Tests\Search\AbstractSearchTestCase
{
    public function testGetConfiguration()
    {
        $this->assertInstanceOf('Doctrine\Search\Configuration', $this->sm->getConfiguration());
    }

    public function testGetClient()
    {
        $this->assertInstanceOf('Doctrine\Search\SearchClientInterface', $this->sm->getClient());
    }

    public function testGetMetadataFactory()
    {
        $this->assertInstanceOf('Doctrine\Search\Mapping\ClassMetadataFactory', $this->sm->getMetadataFactory());
    }

    public function testGetUnitOfWork()
    {
        $this->assertInstanceOf('Doctrine\Search\UnitOfWork', $this->sm->getUnitOfWork());
    }

    public function testGetEventManager()
    {
        $this->assertInstanceOf('Doctrine\Common\EventManager', $this->sm->getEventManager());
    }

    public function testGetEntityManager()
    {
        $this->assertInstanceOf('Doctrine\Common\Persistence\ObjectManager', $this->sm->getEntityManager());
    }

    public function testGetSerializer()
    {
        $this->assertInstanceOf('Doctrine\Search\SerializerInterface', $this->sm->getSerializer());
    }

    public function testCreateQuery()
    {
        $this->assertInstanceOf('Doctrine\Search\Query', $this->sm->createQuery());
    }

    static public function dataMethodsAffectedByNoObjectArguments()
    {
        return array(
            array('persist'),
            array('remove'),

            //array('merge'),
            //array('refresh'),
            //array('detach')
        );
    }

    /**
     * @dataProvider dataMethodsAffectedByNoObjectArguments
     * @expectedException Doctrine\Search\Exception\UnexpectedTypeException
     */
    public function testThrowsExceptionOnNonObjectValues($methodName) {
        $this->sm->$methodName(null);
    }
}
