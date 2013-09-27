<?php

class NamingStrategyTest extends \PHPUnit_Framework_TestCase
{
    static private $dashStrategy;
    static private $literalStrategy;
    static private $underscoreStrategy;

    static private function dashStrategy() {
        return self::$dashStrategy ?: self::$dashStrategy = new \Doctrine\Search\Mapping\NamingStrategy\Dash();
    }
    static private function literalStrategy() {
        return self::$literalStrategy ?: self::$literalStrategy = new \Doctrine\Search\Mapping\NamingStrategy\Literal();
    }
    static private function underscoreStrategy() {
        return self::$underscoreStrategy ?: self::$underscoreStrategy = new \Doctrine\Search\Mapping\NamingStrategy\Underscore();
    }

    static public function dataClassToTableName()
    {
        return array(
            // literal
            array(self::literalStrategy(), 'SomeClassName',
                'SomeClassName'
            ),
            array(self::literalStrategy(), 'SomeClassName',
                '\SomeClassName'
            ),
            array(self::literalStrategy(), 'someClassName',
                '\someClassName'
            ),
            array(self::literalStrategy(), 'Name',
                '\Some\Class\Name'
            ),

            // dash
            array(self::dashStrategy(), 'some-class-name',
                '\Name\Space\SomeClassName'
            ),
            array(self::dashStrategy(), 'class-name',
                '\Some\Class\ClassName'
            ),
            array(self::dashStrategy(), 'name',
                '\Name'
            ),
            array(self::dashStrategy(), 'some-class-name',
                'Foo\Bar\SomeCLASSName'
            ),

            // underscore
            array(self::underscoreStrategy(), 'some_class_name',
                '\Name\Space\someClassName'
            ),
            array(self::underscoreStrategy(), 'class_name',
                '\Some\Class\ClassName'
            ),
            array(self::underscoreStrategy(), 'name',
                '\Name'
            ),
            array(self::underscoreStrategy(), 'some_class_name',
                'Foo\Bar\SomeCLASSName'
            ),
        );
    }

    /**
     * @dataProvider dataClassToTableName
     */
    public function testClassToTableName(\Doctrine\Search\Mapping\NamingStrategy\NamingStrategyInterface $strategy, $expected, $className)
    {
        $this->assertEquals($expected, $strategy->classToTableName($className));
    }

    static public function dataPropertyToColumnName()
    {
        return array(
            // literal
            array(self::literalStrategy(), 'someProperty',
                'someProperty'
            ),
            array(self::literalStrategy(), 'SOME_PROPERTY',
                'SOME_PROPERTY'
            ),
            array(self::literalStrategy(), 'some_property',
                'some_property'
            ),

            // dash
            array(self::dashStrategy(), 'some-property',
                'someProperty'
            ),
            array(self::dashStrategy(), 'some-property',
                'someProperty'
            ),
            array(self::dashStrategy(), 'some-property',
                'some_property'
            ),
            array(self::dashStrategy(), 'some-pro-perty',
                'SOME_proPERTY'
            ),
            array(self::dashStrategy(), 'pa2a-title',
                'Pa2aTitle'
            ),

            // underscore
            array(self::underscoreStrategy(), 'some_property',
                'someProperty'
            ),
            array(self::underscoreStrategy(), 'some_property',
                'someProperty'
            ),
            array(self::underscoreStrategy(), 'some_property',
                'some_property'
            ),
            array(self::underscoreStrategy(), 'some_pro_perty',
                'SOME_proPERTY'
            ),
        );
    }
    
    /**
     * @dataProvider dataPropertyToColumnName
     */
    public function testPropertyToColumnName(\Doctrine\Search\Mapping\NamingStrategy\NamingStrategyInterface $strategy, $expected, $propertyName)
    {
        $this->assertEquals($expected, $strategy->propertyToColumnName($propertyName));
    }
}
