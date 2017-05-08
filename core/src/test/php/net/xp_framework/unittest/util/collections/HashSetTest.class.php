<?php
/* This class is part of the XP framework
 *
 * $Id$
 */
 
  uses(
    'unittest.TestCase',
    'util.collections.HashSet',
    'lang.types.XPString'
  );

  /**
   * Test HashSet class
   *
   * @see      xp://util.collections.HashSet
   * @purpose  Unit Test
   */
  class HashSetTest extends TestCase {
    public
      $set= NULL;
    
    /**
     * Setup method. Creates the set member
     *
     */
    public function setUp() {
      $this->set= new HashSet();
    }
        
    /**
     * Tests the set is initially empty
     *
     */
    #[@test]
    public function initiallyEmpty() {
      $this->assertTrue($this->set->isEmpty());
    }

    /**
     * Tests set equals its clone
     *
     */
    #[@test]
    public function equalsClone() {
      $this->set->add(new XPString('green'));
      $this->assertTrue($this->set->equals(clone($this->set)));
    }
 
    /**
     * Tests set equals another set with the same contents
     *
     */
    #[@test]
    public function equalsOtherSetWithSameContents() {
      $other= new HashSet();
      $this->set->add(new XPString('color'));
      $other->add(new XPString('color'));
      $this->assertTrue($this->set->equals($other));
    }

    /**
     * Tests set does not equal set with different contents
     *
     */
    #[@test]
    public function doesNotEqualSetWithDifferentContents() {
      $other= new HashSet();
      $this->set->add(new XPString('blue'));
      $other->add(new XPString('yellow'));
      $this->assertFalse($this->set->equals($other));
    }
   
    /**
     * Tests add()
     *
     */
    #[@test]
    public function add() {
      $this->set->add(new XPString('green'));
      $this->assertFalse($this->set->isEmpty());
      $this->assertEquals(1, $this->set->size());
    }

    /**
     * Tests addAll()
     *
     */
    #[@test]
    public function addAll() {
      $array= array(new XPString('one'), new XPString('two'), new XPString('three'));
      $this->set->addAll($array);
      $this->assertFalse($this->set->isEmpty());
      $this->assertEquals(3, $this->set->size());
    }

    /**
     * Tests addAll() uniques the array given
     *
     */
    #[@test]
    public function addAllUniques() {
      $array= array(new XPString('one'), new XPString('one'), new XPString('two'));
      $this->set->addAll($array);
      $this->assertFalse($this->set->isEmpty());
      $this->assertEquals(2, $this->set->size());   // String{"one"} and String{"two"}
    }

    /**
     * Tests addAll() returns TRUE if the set changed as a result if the
     * call, FALSE otherwise.
     *
     */
    #[@test]
    public function addAllReturnsWhetherSetHasChanged() {
      $array= array(new XPString('caffeine'), new XPString('nicotine'));
      $this->assertTrue($this->set->addAll($array));
      $this->assertFalse($this->set->addAll($array));
      $this->assertFalse($this->set->addAll(array(new XPString('caffeine'))));
      $this->assertFalse($this->set->addAll(array()));
    }

    /**
     * Tests contains() method
     *
     */
    #[@test]
    public function contains() {
      $this->set->add(new XPString('key'));
      $this->assertTrue($this->set->contains(new XPString('key')));
      $this->assertFalse($this->set->contains(new XPString('non-existant-key')));
    }

    /**
     * Tests add() returns TRUE if the set did not already contain the
     * given element, FALSE otherwise
     *
     */
    #[@test]
    public function addSameValueTwice() {
      $color= new XPString('green');
      $this->assertTrue($this->set->add($color));
      $this->assertFalse($this->set->add($color));
    }

    /**
     * Tests remove()
     *
     */
    #[@test]
    public function remove() {
      $this->set->add(new XPString('key'));
      $this->assertTrue($this->set->remove(new XPString('key')));
      $this->assertTrue($this->set->isEmpty());
    }

    /**
     * Tests remove() returns FALSE when given object cannot be 
     * contained in the set (because the set is empty)
     *
     */
    #[@test]
    public function removeOnEmptySet() {
      $this->assertFalse($this->set->remove(new XPString('irrelevant-set-is-empty-anyway')));
    }

    /**
     * Tests remove() returns FALSE when given object is not contained
     * in the set.
     *
     */
    #[@test]
    public function removeNonExistantObject() {
      $this->set->add(new XPString('key'));
      $this->assertFalse($this->set->remove(new XPString('non-existant-key')));
    }

    /**
     * Tests clear() method
     *
     */
    #[@test]
    public function clear() {
      $this->set->add(new XPString('key'));
      $this->set->clear();
      $this->assertTrue($this->set->isEmpty());
    }

    /**
     * Tests toArray() method
     *
     */
    #[@test]
    public function toArray() {
      $color= new XPString('red');
      $this->set->add($color);
      $this->assertEquals(array($color), $this->set->toArray());
    }

    /**
     * Tests toArray() method
     *
     */
    #[@test]
    public function toArrayOnEmptySet() {
      $this->assertEquals(array(), $this->set->toArray());
    }

    /**
     * Tests iteration via foreach()
     *
     */
    #[@test]
    public function iteration() {
      $this->set->add(new XPString('1'));
      $this->set->add(new XPString('2'));
      $this->set->add(new XPString('3'));
      
      foreach ($this->set as $i => $value) {
        $this->assertEquals(new XPString($i+ 1), $value);
      }
    }

    /**
     * Tests toString() method
     *
     */
    #[@test]
    public function stringRepresentation() {
      $this->set->add(new XPString('color'));
      $this->set->add(new XPString('price'));
      $this->assertEquals(
        "util.collections.HashSet[2] {\n  color,\n  price\n}",
        $this->set->toString()
      );
    }

    /**
     * Tests toString() method on an empty set
     *
     */
    #[@test]
    public function stringRepresentationOfEmptySet() {
      $this->assertEquals(
        'util.collections.HashSet[0] { }',
        $this->set->toString()
      );
    }
  }
?>
