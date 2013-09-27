<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.doctrine-project.org>.
 */

namespace Doctrine\Search;

use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Annotations\SimpleAnnotationReader;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriver;
use Doctrine\Search\Mapping\ClassMetadataFactory;
use Doctrine\Search\Mapping\Driver\AnnotationDriver;
use Doctrine\Search\SerializerInterface;
use Doctrine\Search\Serializer\CallbackSerializer;

/**
 * Configuration SearchManager
 *
 * @license http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @author  Mike Lohmann <mike.h.lohmann@googlemail.com>
 */
class Configuration
{
    /**
     * @var array
     */
    private $attributes;

    /**
     * Sets the driver that is used to store the class metadata .
     *
     * @param MappingDriver $concreteDriver
     */
    public function setMetadataDriverImpl(MappingDriver $concreteDriver)
    {
        $this->attributes['concreteMetadataDriver'] = $concreteDriver;
    }

    /**
     * Gets the cache driver implementation that is used for the mapping metadata.
     * (Annotation is the default)
     *
     * @return MappingDriver
     */
    public function getMetadataDriverImpl()
    {
        if (!isset($this->attributes['concreteMetadataDriver'])) {
            $this->attributes['concreteMetadataDriver'] = $this->newDefaultAnnotationDriver();
        }
        return $this->attributes['concreteMetadataDriver'];
    }

    /**
     * Add a new default annotation driver with a correctly configured annotation reader. If $useSimpleAnnotationReader
     * is true, the notation `@Entity` will work, otherwise, the notation `@ORM\Entity` will be supported.
     *
     * @param array $paths
     * @param bool $useSimpleAnnotationReader
     * @return AnnotationDriver
     */
    public function newDefaultAnnotationDriver($paths = array(), $useSimpleAnnotationReader = true)
    {
        AnnotationRegistry::registerFile(__DIR__ . '/Mapping/Driver/DoctrineAnnotations.php');

        if ($useSimpleAnnotationReader) {
            // Register the annotations in the AnnotationRegistry
            $reader = new SimpleAnnotationReader();
            $reader->addNamespace('Doctrine\Search\Mapping\Annotations');
            $cachedReader = new CachedReader($reader, new ArrayCache());

            return new AnnotationDriver($cachedReader, (array) $paths);
        }

        return new AnnotationDriver(
            new CachedReader(new AnnotationReader(), new ArrayCache()),
            (array) $paths
        );
    }

    /**
     * Sets the cache driver that is used to cache the metadata.
     *
     * @param Cache $concreteCache
     */
    public function setMetadataCacheImpl(Cache $concreteCache)
    {
        $this->attributes['metadataCacheImpl'] = $concreteCache;
    }

    /**
     * Returns the cache driver that is used to cache the metadata.
     *
     * @return Cache
     */
    public function getMetadataCacheImpl()
    {
        if (!isset($this->attributes['metadataCacheImpl'])) {
            $this->attributes['metadataCacheImpl'] = new ArrayCache();
        }
        return $this->attributes['metadataCacheImpl'];
    }

    /**
     * Set the class metadata factory class name.
     *
     * @param string $cmfName
     */
    public function setClassMetadataFactoryName($cmfName)
    {
        $this->attributes['classMetadataFactoryName'] = $cmfName;
    }

    /**
     * Gets the class metadata factory class name.
     *
     * @return string
     */
    public function getClassMetadataFactoryName()
    {
        if (!isset($this->attributes['classMetadataFactoryName'])) {
            $this->attributes['classMetadataFactoryName'] = 'Doctrine\Search\Mapping\ClassMetadataFactory';
        }
        return $this->attributes['classMetadataFactoryName'];
    }

    /**
     * Gets the class metadata factory.
     *
     * @return ClassMetadataFactory
     */
    public function getClassMetadataFactory()
    {
        if (!isset($this->attributes['classMetadataFactory'])) {
            $classMetaDataFactoryName = $this->getClassMetadataFactoryName();
            $this->attributes['classMetadataFactory'] = new $classMetaDataFactoryName;
        }
        return $this->attributes['classMetadataFactory'];
    }

    /**
     * Sets an entity serializer
     *
     * @param SerializerInterface $serializer
     */
    public function setEntitySerializer(SerializerInterface $serializer)
    {
        $this->attributes['serializer'] = $serializer;
    }

    /**
     * Gets the entity serializer or provides a default if not set
     *
     * @return SerializerInterface
     */
    public function getEntitySerializer()
    {
        if (!isset($this->attributes['serializer'])) {
            $this->attributes['serializer'] = new CallbackSerializer();
        }
        return $this->attributes['serializer'];
    }

    /**
     * @param ObjectManager $entityManager
     */
    public function setEntityManager(ObjectManager $entityManager)
    {
        $this->attributes['entityManager'] = $entityManager;
    }

    /**
     * @return ObjectManager
     */
    public function getEntityManager()
    {
        return isset($this->attributes['entityManager'])
            ? $this->attributes['entityManager']
            : null;
    }
}
