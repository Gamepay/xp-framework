<?php
/* This class is part of the XP framework
 *
 * $Id$ 
 */

  uses(
    'util.collections.HashTable', 
    'util.collections.HashSet', 
    'util.collections.Vector',
    'util.collections.Stack',
    'util.collections.Queue',
    'util.collections.LRUBuffer'
  );

  /**
   * TestCase
   *
   * @see      xp://util.collections.HashTable 
   * @see      xp://util.collections.HashSet 
   * @see      xp://util.collections.Vector
   * @see      xp://util.collections.Stack
   * @see      xp://util.collections.Queue
   * @see      xp://util.collections.LRUBuffer
   * @purpose  Unittest
   */
  class GenericsTest extends TestCase {

    /**
     * Tests HashTable::equals()
     *
     */
    #[@test]
    public function differingGenericHashTablesNotEquals() {
      $this->assertNotEquals(
        create('new HashTable<lang.XPObject, lang.XPObject>'),
        create('new HashTable<lang.types.XPString, lang.XPObject>')
      );
    }

    /**
     * Tests HashTable::equals()
     *
     */
    #[@test]
    public function sameGenericHashTablesAreEqual() {
      $this->assertEquals(
        create('new HashTable<lang.types.XPString, lang.XPObject>'),
        create('new HashTable<lang.types.XPString, lang.XPObject>')
      );
    }

    /**
     * Tests HashSet::equals()
     *
     */
    #[@test]
    public function differingGenericHashSetsNotEquals() {
      $this->assertNotEquals(
        create('new HashSet<lang.XPObject>'),
        create('new HashSet<lang.types.XPString>')
      );
    }

    /**
     * Tests HashSet::equals()
     *
     */
    #[@test]
    public function sameGenericHashSetsAreEqual() {
      $this->assertEquals(
        create('new HashSet<lang.types.XPString>'),
        create('new HashSet<lang.types.XPString>')
      );
    }

    /**
     * Tests Vector::equals()
     *
     */
    #[@test]
    public function differingGenericVectorsNotEquals() {
      $this->assertNotEquals(
        create('new Vector<lang.XPObject>'),
        create('new Vector<lang.types.XPString>')
      );
    }

    /**
     * Tests Vector::equals()
     *
     */
    #[@test]
    public function sameGenericVectorsAreEqual() {
      $this->assertEquals(
        create('new Vector<lang.types.XPString>'),
        create('new Vector<lang.types.XPString>')
      );
    }

    /**
     * Tests Queue::equals()
     *
     */
    #[@test]
    public function differingGenericQueuesNotEquals() {
      $this->assertNotEquals(
        create('new Queue<lang.XPObject>'),
        create('new Queue<lang.types.XPString>')
      );
    }

    /**
     * Tests Queue::equals()
     *
     */
    #[@test]
    public function sameGenericQueuesAreEqual() {
      $this->assertEquals(
        create('new Queue<lang.types.XPString>'),
        create('new Queue<lang.types.XPString>')
      );
    }

    /**
     * Tests Stack::equals()
     *
     */
    #[@test]
    public function differingGenericStacksNotEquals() {
      $this->assertNotEquals(
        create('new Stack<lang.XPObject>'),
        create('new Stack<lang.types.XPString>')
      );
    }

    /**
     * Tests Stack::equals()
     *
     */
    #[@test]
    public function sameGenericStacksAreEqual() {
      $this->assertEquals(
        create('new Stack<lang.types.XPString>'),
        create('new Stack<lang.types.XPString>')
      );
    }

    /**
     * Tests LRUBuffer::equals()
     *
     */
    #[@test]
    public function differingGenericLRUBuffersNotEquals() {
      $this->assertNotEquals(
        create('new LRUBuffer<lang.XPObject>', array(10)),
        create('new LRUBuffer<lang.types.XPString>', array(10))
      );
    }

    /**
     * Tests LRUBuffer::equals()
     *
     */
    #[@test]
    public function sameGenericLRUBuffersAreEqual() {
      $this->assertEquals(
        create('new LRUBuffer<lang.types.XPString>', array(10)),
        create('new LRUBuffer<lang.types.XPString>', array(10))
      );
    }

    /**
     * Tests non-generic objects
     *
     */
    #[@test, @expect('lang.IllegalArgumentException')]
    public function nonGenericPassedToCreate() {
      create('new lang.XPObject<lang.types.XPString>');
    }
  
    /**
     * Tests HashTable<string, lang.types.XPString>
     *
     */
    #[@test]
    public function stringStringHash() {
      create('new util.collections.HashTable<string, lang.types.XPString>')->put('hello', new XPString('World'));
    }

    /**
     * Tests HashTable<string, lang.types.XPString>
     *
     */
    #[@test]
    public function getFromStringStringHash() {
      with ($h= create('new util.collections.HashTable<string, lang.types.XPString>')); {
        $h->put('hello', new XPString('World'));
        $this->assertEquals(new XPString('World'), $h->get('hello'));
      }
    }

    /**
     * Tests HashTable<string, lang.types.XPString>
     *
     */
    #[@test]
    public function removeFromStringStringHash() {
      with ($h= create('new util.collections.HashTable<string, lang.types.XPString>')); {
        $h->put('hello', new XPString('World'));
        $this->assertEquals(new XPString('World'), $h->remove('hello'));
      }
    }

    /**
     * Tests HashTable<lang.types.XPString, lang.types.XPString>
     *
     */
    #[@test]
    public function testStringStringHash() {
      with ($h= create('new util.collections.HashTable<string, lang.types.XPString>')); {
        $h->put('hello', new XPString('World'));
        $this->assertTrue($h->containsKey('hello'));
      }
    }

    /**
     * Tests HashTable<lang.types.XPString, lang.types.XPString>
     *
     */
    #[@test, @expect('lang.IllegalArgumentException')]
    public function stringStringHashPutIllegalValue() {
      create('new util.collections.HashTable<string, lang.types.XPString>')->put('hello', new Integer(1));
    }

    /**
     * Tests HashTable<lang.types.XPString, lang.types.XPString>
     *
     */
    #[@test, @expect('lang.IllegalArgumentException')]
    public function stringStringHashGetIllegalValue() {
      create('new util.collections.HashTable<lang.types.XPString, lang.types.XPString>')->get(new Integer(1));
    }

    /**
     * Tests HashTable<lang.types.XPString, lang.types.XPString>
     *
     */
    #[@test, @expect('lang.IllegalArgumentException')]
    public function stringStringHashRemoveIllegalValue() {
      create('new util.collections.HashTable<lang.types.XPString, lang.types.XPString>')->remove(new Integer(1));
    }

    /**
     * Tests HashTable<lang.types.XPString, lang.types.XPString>
     *
     */
    #[@test, @expect('lang.IllegalArgumentException')]
    public function stringStringHashContainsKeyIllegalValue() {
      create('new util.collections.HashTable<lang.types.XPString, lang.types.XPString>')->containsKey(new Integer(1));
    }

    /**
     * Tests HashTable<lang.types.XPString, lang.types.XPString>
     *
     */
    #[@test, @expect('lang.IllegalArgumentException')]
    public function stringStringHashContainsValueIllegalValue() {
      create('new util.collections.HashTable<lang.types.XPString, lang.types.XPString>')->containsValue(new Integer(1));
    }

    /**
     * Tests HashTable<lang.types.XPString, lang.types.XPString>
     *
     */
    #[@test, @expect('lang.IllegalArgumentException')]
    public function stringStringHashIllegalKey() {
      create('new util.collections.HashTable<lang.types.XPString, lang.types.XPString>')->put(1, new XPString('World'));
    }

    /**
     * Tests Vector<lang.types.XPString>
     *
     */
    #[@test]
    public function stringVector() {
      create('new util.collections.Vector<lang.types.XPString>')->add(new XPString('Hi'));
    }

    /**
     * Tests Vector<lang.types.XPString>
     *
     */
    #[@test]
    public function createStringVector() {
      $this->assertEquals(
        new XPString('one'), 
        create('new util.collections.Vector<lang.types.XPString>', array(new XPString('one')))->get(0)
      );
    }

    /**
     * Tests Vector<lang.types.XPString>
     *
     */
    #[@test, @expect('lang.IllegalArgumentException')]
    public function stringVectorAddIllegalValue() {
      create('new util.collections.Vector<lang.types.XPString>')->add(new Integer(1));
    }

    /**
     * Tests Vector<lang.types.XPString>
     *
     */
    #[@test, @expect('lang.IllegalArgumentException')]
    public function stringVectorSetIllegalValue() {
      create('new util.collections.Vector<lang.types.XPString>', array(new XPString('')))->set(0, new Integer(1));
    }

    /**
     * Tests Vector<lang.types.XPString>
     *
     */
    #[@test, @expect('lang.IllegalArgumentException')]
    public function stringVectorContainsIllegalValue() {
      create('new util.collections.Vector<lang.types.XPString>')->contains(new Integer(1));
    }

    /**
     * Tests Vector<lang.types.XPString>
     *
     */
    #[@test, @expect('lang.IllegalArgumentException')]
    public function createStringVectorWithIllegalValue() {
      create('new util.collections.Vector<lang.types.XPString>', array(new Integer(1)));
    }

    /**
     * Tests Stack<lang.types.XPString>
     *
     */
    #[@test]
    public function stringStack() {
      create('new util.collections.Stack<lang.types.XPString>')->push(new XPString('One'));
    }

    /**
     * Tests Stack<lang.types.XPString>
     *
     */
    #[@test, @expect('lang.IllegalArgumentException')]
    public function stringStackPushIllegalValue() {
      create('new util.collections.Stack<lang.types.XPString>')->push(new Integer(1));
    }

    /**
     * Tests Stack<lang.types.XPString>
     *
     */
    #[@test, @expect('lang.IllegalArgumentException')]
    public function stringStackSearchIllegalValue() {
      create('new util.collections.Stack<lang.types.XPString>')->search(new Integer(1));
    }

    /**
     * Tests Queue<lang.types.XPString>
     *
     */
    #[@test]
    public function stringQueue() {
      create('new util.collections.Queue<lang.types.XPString>')->put(new XPString('One'));
    }

    /**
     * Tests Queue<lang.types.XPString>
     *
     */
    #[@test, @expect('lang.IllegalArgumentException')]
    public function stringQueuePutIllegalValue() {
      create('new util.collections.Queue<lang.types.XPString>')->put(new Integer(1));
    }

    /**
     * Tests Queue<lang.types.XPString>
     *
     */
    #[@test, @expect('lang.IllegalArgumentException')]
    public function stringQueueSearchIllegalValue() {
      create('new util.collections.Queue<lang.types.XPString>')->search(new Integer(1));
    }

    /**
     * Tests Queue<lang.types.XPString>
     *
     */
    #[@test, @expect('lang.IllegalArgumentException')]
    public function stringQueueRemoveIllegalValue() {
      create('new util.collections.Queue<lang.types.XPString>')->remove(new Integer(1));
    }

    /**
     * Tests LRUBuffer<lang.types.XPString>
     *
     */
    #[@test]
    public function stringLRUBuffer() {
      create('new util.collections.LRUBuffer<lang.types.XPString>', 1)->add(new XPString('One'));
    }

    /**
     * Tests LRUBuffer<lang.types.XPString>
     *
     */
    #[@test, @expect('lang.IllegalArgumentException')]
    public function stringLRUBufferAddIllegalValue() {
      create('new util.collections.LRUBuffer<lang.types.XPString>', 1)->add(new Integer(1));
    }

    /**
     * Tests LRUBuffer<lang.types.XPString>
     *
     */
    #[@test, @expect('lang.IllegalArgumentException')]
    public function stringLRUBufferUpdateIllegalValue() {
      create('new util.collections.LRUBuffer<lang.types.XPString>', 1)->update(new Integer(1));
    }

    /**
     * Tests HashSet<lang.types.XPString>
     *
     */
    #[@test]
    public function stringHashSet() {
      create('new util.collections.HashSet<lang.types.XPString>')->add(new XPString('One'));
    }

    /**
     * Tests HashSet<lang.types.XPString>
     *
     */
    #[@test, @expect('lang.IllegalArgumentException')]
    public function stringHashSetAddIllegalValue() {
      create('new util.collections.HashSet<lang.types.XPString>')->add(new Integer(1));
    }

    /**
     * Tests HashSet<lang.types.XPString>
     *
     */
    #[@test, @expect('lang.IllegalArgumentException')]
    public function stringHashSetContainsIllegalValue() {
      create('new util.collections.HashSet<lang.types.XPString>')->contains(new Integer(1));
    }

    /**
     * Tests HashSet<lang.types.XPString>
     *
     */
    #[@test, @expect('lang.IllegalArgumentException')]
    public function stringHashSetRemoveIllegalValue() {
      create('new util.collections.HashSet<lang.types.XPString>')->remove(new Integer(1));
    }

    /**
     * Tests HashSet<lang.types.XPString>
     *
     */
    #[@test, @expect('lang.IllegalArgumentException')]
    public function stringHashSetAddAllIllegalValue() {
      create('new util.collections.HashSet<lang.types.XPString>')->addAll(array(
        new XPString('HELLO'),    // Still OK
        new Integer(2),         // Blam
      ));
    }

    /**
     * Tests HashSet<lang.types.XPString>
     *
     */
    #[@test]
    public function stringHashSetUnchangedAferAddAllIllegalValue() {
      $h= create('new util.collections.HashSet<lang.types.XPString>');
      try {
        $h->addAll(array(new XPString('HELLO'), new Integer(2)));
      } catch (IllegalArgumentException $expected) {
      }
      $this->assertTrue($h->isEmpty());
    }

    /**
     * Tests HashTable<string[], lang.types.XPString>
     *
     */
    #[@test]
    public function arrayAsKeyLookupWithMatchingKey() {
      with ($h= create('new util.collections.HashTable<string[], lang.types.XPString>')); {
        $h->put(array('hello'), new XPString('World'));
        $this->assertEquals(new XPString('World'), $h->get(array('hello')));
      }
    }

    /**
     * Tests HashTable<string[], lang.types.XPString>
     *
     */
    #[@test]
    public function arrayAsKeyLookupWithMismatchingKey() {
      with ($h= create('new util.collections.HashTable<string[], lang.types.XPString>')); {
        $h->put(array('hello'), new XPString('World'));
        $this->assertNull($h->get(array('world')));
      }
    }

    /**
     * Tests HashTable<string[], lang.types.XPString>
     *
     */
    #[@test, @expect('lang.IllegalArgumentException')]
    public function arrayAsKeyArrayComponentTypeMismatch() {
      create('new util.collections.HashTable<string[], lang.types.XPString>')->put(array(1), new XPString('World'));
    }

    /**
     * Tests HashTable<string[], lang.types.XPString>
     *
     */
    #[@test, @expect('lang.IllegalArgumentException')]
    public function arrayAsKeyTypeMismatch() {
      create('new util.collections.HashTable<string[], lang.types.XPString>')->put('hello', new XPString('World'));
    }

    /**
     * Tests HashTable with float keys
     *
     * @see   issue://31
     */
    #[@test]
    public function floatKeyHashTable() {
      $c= create('new util.collections.HashTable<float, string>');
      $c[0.1]= '1/10';
      $c[0.2]= '2/10';
      $this->assertEquals('1/10', $c[0.1], '0.1');
      $this->assertEquals('2/10', $c[0.2], '0.2');
    }

    /**
     * Tests HashSet with floats
     *
     * @see   issue://31
     */
    #[@test]
    public function floatInHashSet() {
      $c= create('new util.collections.HashSet<float>');
      $c->add(0.1);
      $c->add(0.2);
      $this->assertEquals(array(0.1, 0.2), $c->toArray());
    }

    /**
     * Tests LRUBuffer with floats
     *
     * @see   issue://31
     */
    #[@test]
    public function floatInLRUBuffer() {
      $c= create('new util.collections.LRUBuffer<float>', $irrelevant= 10);
      $c->add(0.1);
      $c->add(0.2);
      $this->assertEquals(2, $c->numElements());
    }

    /**
     * Tests HashTable::toString() in conjunction with primitives
     *
     * @see   issue://32
     */
    #[@test]
    public function primitiveInHashTableToString() {
      $c= create('new util.collections.HashTable<string, string>');
      $c->put('hello', 'World');
      $this->assertNotEquals('', $c->toString());
    }

    /**
     * Tests HashSet::toString() in conjunction with primitives
     *
     * @see   issue://32
     */
    #[@test]
    public function primitiveInHashSetToString() {
      $c= create('new util.collections.HashSet<string>');
      $c->add('hello');
      $this->assertNotEquals('', $c->toString());
    }

    /**
     * Tests Vector::toString() in conjunction with primitives
     *
     * @see   issue://32
     */
    #[@test]
    public function primitiveInVectorToString() {
      $c= create('new util.collections.Vector<string>');
      $c->add('hello');
      $this->assertNotEquals('', $c->toString());
    }
  }
?>
