<?php
/* This class is part of the XP framework
 *
 * $Id$
 */

  uses('unittest.TestCase');

  /**
   * Tests the lang.XPObject class
   *
   * @see      xp://lang.XPObject
   * @purpose  Testcase
   */
  class ObjectTest extends TestCase {

    /**
     * Ensures lang.XPObject does not have a constructor
     *
     */
    #[@test]
    public function noConstructor() {
      $this->assertFalse(XPClass::forName('lang.XPObject')->hasConstructor());
    }

    /**
     * Ensures lang.XPObject does not have a parent class
     *
     */
    #[@test]
    public function baseClass() {
      $this->assertNull(XPClass::forName('lang.XPObject')->getParentClass());
    }

    /**
     * Ensures lang.XPObject implements the lang.Generic interface
     *
     */
    #[@test]
    public function genericInterface() {
      $interfaces= XPClass::forName('lang.XPObject')->getInterfaces();
      $this->assertEquals(1, sizeof($interfaces));
      $this->assertInstanceOf('lang.XPClass', $interfaces[0]);
      $this->assertEquals('lang.Generic', $interfaces[0]->getName());
    }

    /**
     * Ensures the xp::typeOf() function returns the fully qualified 
     * class name, "lang.XPObject"
     *
     */
    #[@test]
    public function typeOf() {
      $this->assertEquals('lang.XPObject', xp::typeOf(new XPObject()));
    }

    /**
     * Tests the hashCode() method
     *
     * @see     xp://lang.XPObject#hashCode
     */
    #[@test]
    public function hashCodeMethod() {
      $o= new XPObject();
      $this->assertTrue((bool)preg_match('/^0\.[0-9]+ [0-9]+ [0-9a-f]+$/', $o->hashCode()));
    }

    /**
     * Tests the equals() method
     *
     * @see     xp://lang.XPObject#equals
     */
    #[@test]
    public function objectIsEqualToSelf() {
      $o= new XPObject();
      $this->assertTrue($o->equals($o));
    }

    /**
     * Tests the equals() method
     *
     * @see     xp://lang.XPObject#equals
     */
    #[@test]
    public function objectIsNotEqualToOtherObject() {
      $o= new XPObject();
      $this->assertFalse($o->equals(new XPObject()));
    }

    /**
     * Tests the equals() method
     *
     * @see     xp://lang.XPObject#equals
     */
    #[@test]
    public function objectIsNotEqualToPrimitive() {
      $o= new XPObject();
      $this->assertFalse($o->equals(0));
    }
    
    /**
     * Tests the getClassName() method returns the fully qualified
     * class name
     *
     * @see     xp://lang.XPObject#getClassName
     */
    #[@test]
    public function getClassNameMethod() {
      $o= new XPObject();
      $this->assertEquals('lang.XPObject', $o->getClassName());
    }

    /**
     * Tests the getClassName() method returns the fully qualified
     * class name
     *
     * @see     xp://lang.XPObject#getClass
     */
    #[@test]
    public function getClassMethod() {
      $o= new XPObject();
      $class= $o->getClass();
      $this->assertInstanceOf('lang.XPClass', $class);
      $this->assertEquals('lang.XPObject', $class->getName());
    }

    /**
     * Tests the toString() method
     *
     * @see     xp://lang.XPObject#toString
     */
    #[@test]
    public function toStringMethod() {
      $o= new XPObject();
      $this->assertEquals(
        'lang.XPObject {'."\n".
        '  __id => "'.$o->hashCode().'"'."\n".
        '}', 
        $o->toString()
      );
    }

    /**
     * Tests call to undefined method
     *
     * @see     xp://lang.XPObject#__call
     */
    #[@test, @expect(class= 'lang.XPError', withMessage= '/Call to undefined method .+::undefMethod\(\) from scope net\.xp_framework\.unittest\.core\.ObjectTest/')]
    public function callUndefinedMethod() {
      create(new XPObject())->undefMethod();
    }

    /**
     * Tests call to undefined method using call_user_func_array()
     *
     * @see     xp://lang.XPObject#__call
     */
    #[@test, @expect(class= 'lang.XPError', withMessage= '/Call to undefined method .+::undefMethod\(\) from scope net\.xp_framework\.unittest\.core\.ObjectTest/')]
    public function callUndefinedMethod_call_user_func_array() {
      call_user_func_array(array(new XPObject(), 'undefMethod'), array());
    }
  }
?>
