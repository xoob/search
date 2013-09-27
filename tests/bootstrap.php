<?php

$loader = require_once __DIR__.'/../vendor/autoload.php';

$loader->add('Doctrine\\Tests\\Search', __DIR__);
$loader->add('Doctrine\\ODM\\MongoDB\\Tests', __DIR__.'/../vendor/doctrine/mongodb-odm/tests');
$loader->add('Doctrine\\Tests', __DIR__.'/../vendor/doctrine/orm/tests');

use Doctrine\Common\Annotations\AnnotationRegistry;

AnnotationRegistry::registerFile(__DIR__ . '/../lib/Doctrine/Search/Mapping/Driver/DoctrineAnnotations.php');
AnnotationRegistry::registerFile(__DIR__ . '/../vendor/doctrine/mongodb-odm/lib/Doctrine/ODM/MongoDB/Mapping/Annotations/DoctrineAnnotations.php');
AnnotationRegistry::registerFile(__DIR__ . '/../vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php');
