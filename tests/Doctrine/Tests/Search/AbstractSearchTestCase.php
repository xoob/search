<?php

namespace Doctrine\Tests\Search;

abstract class AbstractSearchTestCase extends \Doctrine\Tests\OrmTestCase
{
    /**
     * Metadata cache shared between all tests
     * @var Doctrine\Common\Cache\ArrayCache
     */
    private static $sharedMetadataCacheImpl = null;

    /**
     * @var \Doctrine\Search\SearchManager
     */
    protected $sm;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->sm = $this->getTestSearchManager();
    }

    /**
     * Creates a SearchManager for testing purposes
     *
     * @return Doctrine\Search\SearchManager
     */
    protected function getTestSearchManager($conf = null, $client = null, $eventManager = null, $withSharedMetadata = true)
    {
        $metadataCache = $withSharedMetadata
            ? self::getSharedMetadataCacheImpl()
            : new \Doctrine\Common\Cache\ArrayCache;

        $config = new \Doctrine\Search\Configuration();

        $config->setMetadataCacheImpl($metadataCache);
        $config->setMetadataDriverImpl($config->newDefaultAnnotationDriver(array(), true));
        $config->setEntityManager($this->_getTestEntityManager());

        if ($client === null) {
            $client = $this->getMock('Doctrine\Search\ElasticSearch\Client', array(), array(), '', false);
        }

        return new \Doctrine\Search\SearchManager($config, $client, $eventManager);
    }

    private static function getSharedMetadataCacheImpl()
    {
        if (self::$sharedMetadataCacheImpl === null) {
            self::$sharedMetadataCacheImpl = new \Doctrine\Common\Cache\ArrayCache;
        }
        return self::$sharedMetadataCacheImpl;
    }
}
