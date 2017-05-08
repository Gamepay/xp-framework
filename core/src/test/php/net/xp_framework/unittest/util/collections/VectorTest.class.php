<?php
/* This class is part of the XP framework
 *
 * $Id$ 
 */

  uses(
    'unittest.TestCase',
    'lang.types.XPString',
    'lang.types.ArrayList',
    'util.collections.Vector'
  );

  /**
   * TestCase for vector class
   *
   * @see      xp://util.collections.Vector
   * @purpose  Unittest
   */
  class VectorTest extends TestCase {
  
    /**
     * Test a newly created vector is empty
     *
     */
    #[@test]
    public function initiallyEmpty() {
      $this->assertTrue(create(new Vector())->isEmpty());
    }

    /**
     * Test a newly created vector is empty
     *
     */
    #[@test]
    public function sizeOfEmptyVector() {
      $this->assertEquals(0, create(new Vector())->size());
    }
    
    /**
     * Test a newly created vector is empty
     *
     */
    #[@test]
    public function nonEmptyVector() {
      $v= new Vector(array(new XPObject()));
      $this->assertEquals(1, $v->size());
      $this->assertFalse($v->isEmpty());
    }

    /**
     * Test adding elements
     *
     */
    #[@test]
    public function adding() {
      $v= new Vector();
      $v->add(new XPObject());
      $this->assertEquals(1, $v->size());
    }

    /**
     * Test adding elements via addAll
     *
     */
    #[@test]
    public function addAllArray() {
      $v= new Vector();
      $this->assertTrue($v->addAll(array(new XPObject(), new XPObject())));
      $this->assertEquals(2, $v->size());
    }

    /**
     * Test adding elements via addAll
     *
     */
    #[@test]
    public function addAllVector() {
      $v1= new Vector();
      $v2= new Vector();
      $v2->add(new XPObject());
      $v2->add(new XPObject());
      $this->assertTrue($v1->addAll($v2));
      $this->assertEquals(2, $v1->size());
    }

    /**
     * Test adding elements via addAll
     *
     */
    #[@test]
    public function addAllArrayList() {
      $v= new Vector();
      $this->assertTrue($v->addAll(new ArrayList(new XPObject(), new XPObject())));
      $this->assertEquals(2, $v->size());
    }

    /**
     * Test adding elements via addAll
     *
     */
    #[@test]
    public function addAllEmptyArray() {
      $this->assertFalse(create(new Vector())->addAll(array()));
    }

    /**
     * Test adding elements via addAll
     *
     */
    #[@test]
    public function addAllEmptyVector() {
      $this->assertFalse(create(new Vector())->addAll(new Vector()));
    }

    /**
     * Test adding elements via addAll
     *
     */
    #[@test]
    public function addAllEmptyArrayList() {
      $this->assertFalse(create(new Vector())->addAll(new ArrayList()));
    }

    /**
     * Test adding elements via addAll
     *
     */
    #[@test]
    public function unchangedAfterNullInAddAll() {
      $v= create('new Vector<XPObject>()');
      try {
        $v->addAll(array(new XPObject(), NULL));
        $this->fail('addAll() did not throw an exception', NULL, 'lang.IllegalArgumentException');
      } catch (IllegalArgumentException $expected) {
      }
      $this->assertTrue($v->isEmpty());
    }

    /**
     * Test adding elements via addAll
     *
     */
    #[@test]
    public function unchangedAfterIntInAddAll() {
      $v= create('new Vector<string>()');
      try {
        $v->addAll(array('hello', 5));
        $this->fail('addAll() did not throw an exception', NULL, 'lang.IllegalArgumentException');
      } catch (IllegalArgumentException $expected) {
      }
      $this->assertTrue($v->isEmpty());
    }

    /**
     * Test adding NULL does not work
     *
     */
    #[@test, @expect('lang.IllegalArgumentException')]
    public function addingNull() {
      create('new Vector<XPObject>()')->add(NULL);
    }

    /**
     * Test replacing elements
     *
     */
    #[@test]
    public function replacing() {
      $v= new Vector();
      $o= new XPString('one');
      $v->add($o);
      $r= $v->set(0, new XPString('two'));
      $this->assertEquals(1, $v->size());
      $this->assertEquals($o, $r);
    }

    /**
     * Test replacing elements with NULL does not work
     *
     */
    #[@test, @expect('lang.IllegalArgumentException')]
    public function replacingWithNull() {
      create('new Vector<XPObject>', array(new XPObject()))->set(0, NULL);
    }

    /**
     * Test replacing elements
     *
     */
    #[@test, @expect('lang.IndexOutOfBoundsException')]
    public function settingPastEnd() {
      create(new Vector())->set(0, new XPObject());
    }

    /**
     * Test replacing elements
     *
     */
    #[@test, @expect('lang.IndexOutOfBoundsException')]
    public function settingNegative() {
      create(new Vector())->set(-1, new XPObject());
    }

    /**
     * Test reading elements
     *
     */
    #[@test]
    public function reading() {
      $v= new Vector();
      $o= new XPString('one');
      $v->add($o);
      $r= $v->get(0);
      $this->assertEquals($o, $r);
    }

    /**
     * Test reading elements
     *
     */
    #[@test, @expect('lang.IndexOutOfBoundsException')]
    public function readingPastEnd() {
      create(new Vector())->get(0);
    }

    /**
     * Test reading elements
     *
     */
    #[@test, @expect('lang.IndexOutOfBoundsException')]
    public function readingNegative() {
      create(new Vector())->get(-1);
    }

    /**
     * Test removing elements
     *
     */
    #[@test]
    public function removing() {
      $v= new Vector();
      $o= new XPString('one');
      $v->add($o);
      $r= $v->remove(0);
      $this->assertEquals(0, $v->size());
      $this->assertEquals($o, $r);
    }

    /**
     * Test removing elements
     *
     */
    #[@test, @expect('lang.IndexOutOfBoundsException')]
    public function removingPastEnd() {
      create(new Vector())->get(0);
    }

    /**
     * Test removing elements
     *
     */
    #[@test, @expect('lang.IndexOutOfBoundsException')]
    public function removingNegative() {
      create(new Vector())->get(-1);
    }

    /**
     * Test clearing the vector
     *
     */
    #[@test]
    public function clearing() {
      $v= new Vector(array(new XPString('Goodbye cruel world')));
      $this->assertFalse($v->isEmpty());
      $v->clear();
      $this->assertTrue($v->isEmpty());
    }

    /**
     * Test elements()
     *
     */
    #[@test]
    public function elementsOfEmptyVector() {
      $this->assertEquals(array(), create(new Vector())->elements());
    }

    /**
     * Test elements()
     *
     */
    #[@test]
    public function elementsOf() {
      $el= array(new XPString('a'), new XPObject());
      $this->assertEquals($el, create(new Vector($el))->elements());
    }

    /**
     * Test contains()
     *
     */
    #[@test]
    public function addedStringIsContained() {
      $v= new Vector();
      $o= new XPString('one');
      $v->add($o);
      $this->assertTrue($v->contains($o));
    }

    /**
     * Test contains()
     *
     */
    #[@test]
    public function emptyVectorDoesNotContainString() {
      $this->assertFalse(create(new Vector())->contains(new XPObject()));
    }

    /**
     * Test indexOf()
     *
     */
    #[@test]
    public function indexOfOnEmptyVector() {
      $this->assertFalse(create(new Vector())->indexOf(new XPObject()));
    }

    /**
     * Test indexOf()
     *
     */
    #[@test]
    public function indexOf() {
      $a= new XPString('A');
      $this->assertEquals(0, create(new Vector(array($a)))->indexOf($a));
    }

    /**
     * Test indexOf()
     *
     */
    #[@test]
    public function indexOfElementContainedTwice() {
      $a= new XPString('A');
      $this->assertEquals(0, create(new Vector(array($a, new XPObject(), $a)))->indexOf($a));
    }

    /**
     * Test lastIndexOf()
     *
     */
    #[@test]
    public function lastIndexOfOnEmptyVector() {
      $this->assertFalse(create(new Vector())->lastIndexOf(new XPObject()));
    }

    /**
     * Test lastIndexOf()
     *
     */
    #[@test]
    public function lastIndexOf() {
      $a= new XPString('A');
      $this->assertEquals(0, create(new Vector(array($a)))->lastIndexOf($a));
    }

    /**
     * Test lastIndexOf()
     *
     */
    #[@test]
    public function lastIndexOfElementContainedTwice() {
      $a= new XPString('A');
      $this->assertEquals(2, create(new Vector(array($a, new XPObject(), $a)))->lastIndexOf($a));
    }

    /**
     * Test toString()
     *
     */
    #[@test]
    public function stringOfEmptyVector() {
      $this->assertEquals(
        "util.collections.Vector[0]@{\n}",
        create(new Vector())->toString()
      );
    }

    /**
     * Test toString()
     *
     */
    #[@test]
    public function stringOf() {
      $this->assertEquals(
        "util.collections.Vector[2]@{\n  0: One\n  1: Two\n}",
        create(new Vector(array(new XPString('One'), new XPString('Two'))))->toString()
      );
    }

    /**
     * Test iteration
     *
     */
    #[@test]
    public function iteration() {
      $v= new Vector();
      for ($i= 0; $i < 5; $i++) {
        $v->add(new XPString('#'.$i));
      }
      
      $i= 0;
      foreach ($v as $offset => $string) {
        $this->assertEquals($offset, $i);
        $this->assertEquals(new XPString('#'.$i), $string);
        $i++;
      }
    }

    /**
     * Test equals()
     *
     */
    #[@test]
    public function twoEmptyVectorsAreEqual() {
      $this->assertTrue(create(new Vector())->equals(new Vector()));
    }

    /**
     * Test equals()
     *
     */
    #[@test]
    public function sameVectorsAreEqual() {
      $a= new Vector(array(new XPString('One'), new XPString('Two')));
      $this->assertTrue($a->equals($a));
    }

    /**
     * Test equals()
     *
     */
    #[@test]
    public function vectorsWithSameContentsAreEqual() {
      $a= new Vector(array(new XPString('One'), new XPString('Two')));
      $b= new Vector(array(new XPString('One'), new XPString('Two')));
      $this->assertTrue($a->equals($b));
    }

    /**
     * Test equals() does not choke on NULL values
     *
     */
    #[@test]
    public function aVectorIsNotEqualToNull() {
      $this->assertFalse(create(new Vector())->equals(NULL));
    }

    /**
     * Test equals()
     *
     */
    #[@test]
    public function twoVectorsOfDifferentSizeAreNotEqual() {
      $this->assertFalse(create(new Vector(array(new XPObject())))->equals(new Vector()));
    }

    /**
     * Test equals()
     *
     */
    #[@test]
    public function orderMattersForEquality() {
      $a= array(new XPString('a'), new XPString('b'));
      $b= array(new XPString('b'), new XPString('a'));
      $this->assertFalse(create(new Vector($a))->equals(new Vector($b)));
    }
  }
?>
