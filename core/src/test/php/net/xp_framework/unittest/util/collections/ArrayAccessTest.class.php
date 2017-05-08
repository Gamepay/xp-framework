<?php
/* This class is part of the XP framework
 *
 * $Id$ 
 */

  uses(
    'unittest.TestCase',
    'lang.types.XPString',
    'util.collections.HashTable',
    'util.collections.HashSet',
    'util.collections.Vector'
  );

  /**
   * TestCase
   *
   * @see   xp://util.collections.HashTable
   * @see   xp://util.collections.HashSet
   * @see   xp://util.collections.Vector
   */
  class ArrayAccessTest extends TestCase {

    /**
     * Tests array access operator is overloaded for reading
     *
     */
    #[@test]
    public function hashTableReadElement() {
      $c= new HashTable();
      $world= new XPString('world');
      $c->put(new XPString('hello'), $world);
      $this->assertEquals($world, $c[new XPString('hello')]);
    }

    /**
     * Tests array access operator is overloaded for reading
     *
     */
    #[@test]
    public function hashTableReadNonExistantElement() {
      $c= new HashTable();
      $this->assertEquals(NULL, $c[new XPString('hello')]);
    }

    /**
     * Tests array access operator is overloaded for reading
     *
     */
    #[@test, @expect('lang.IllegalArgumentException')]
    public function hashTableReadIllegalElement() {
      $c= create('new HashTable<string, XPObject>()');
      $c[STDIN];
    }

    /**
     * Tests array access operator is overloaded for writing
     *
     */
    #[@test]
    public function hashTableWriteElement() {
      $c= new HashTable();
      $world= new XPString('world');
      $c[new XPString('hello')]= $world;
      $this->assertEquals($world, $c->get(new XPString('hello')));
    }

    /**
     * Tests array access operator is overloaded for writing
     *
     */
    #[@test, @expect('lang.IllegalArgumentException')]
    public function hashTableWriteIllegalKey() {
      $c= create('new HashTable<string, XPObject>()');
      $c[STDIN]= new XPString('Hello');
    }

    /**
     * Tests array access operator is overloaded for writing
     *
     */
    #[@test, @expect('lang.IllegalArgumentException')]
    public function hashTableWriteIllegalValue() {
      $c= create('new HashTable<string, XPObject>()');
      $c['hello']= 'scalar';
    }

    /**
     * Tests array access operator is overloaded for isset()
     *
     */
    #[@test]
    public function hashTableTestElement() {
      $c= new HashTable();
      $c->put(new XPString('hello'), new XPString('world'));
      $this->assertTrue(isset($c[new XPString('hello')]));
      $this->assertFalse(isset($c[new XPString('world')]));
    }

    /**
     * Tests array access operator is overloaded for unset()
     *
     */
    #[@test]
    public function hashTableRemoveElement() {
      $c= new HashTable();
      $c->put(new XPString('hello'), new XPString('world'));
      $this->assertTrue(isset($c[new XPString('hello')]));
      unset($c[new XPString('hello')]);
      $this->assertFalse(isset($c[new XPString('hello')]));
    }

    /**
     * Tests array access operator is overloaded for reading
     *
     */
    #[@test]
    public function vectorReadElement() {
      $v= new Vector();
      $world= new XPString('world');
      $v->add($world);
      $this->assertEquals($world, $v[0]);
    }

    /**
     * Tests array access operator is overloaded for reading
     *
     */
    #[@test, @expect('lang.IndexOutOfBoundsException')]
    public function vectorReadNonExistantElement() {
      $v= new Vector();
      $v[0];
    }

    /**
     * Tests array access operator is overloaded for adding
     *
     */
    #[@test]
    public function vectorAddElement() {
      $v= new Vector();
      $world= new XPString('world');
      $v[]= $world;
      $this->assertEquals($world, $v[0]);
    }
    
    /**
     * Tests array access operator is overloaded for writing
     *
     */
    #[@test]
    public function vectorWriteElement() {
      $v= new Vector(array(new XPString('hello')));
      $world= new XPString('world');
      $v[0]= $world;
      $this->assertEquals($world, $v[0]);
    }

    /**
     * Tests array access operator is overloaded for writing
     *
     */
    #[@test, @expect('lang.IndexOutOfBoundsException')]
    public function vectorWriteElementBeyondBoundsKey() {
      $v= new Vector();
      $v[0]= new XPString('world');
    }

    /**
     * Tests array access operator is overloaded for writing
     *
     */
    #[@test, @expect('lang.IndexOutOfBoundsException')]
    public function vectorWriteElementNegativeKey() {
      $v= new Vector();
      $v[-1]= new XPString('world');
    }

    /**
     * Tests array access operator is overloaded for isset()
     *
     */
    #[@test]
    public function vectorTestElement() {
      $v= new Vector();
      $v[]= new XPString('world');
      $this->assertTrue(isset($v[0]));
      $this->assertFalse(isset($v[1]));
      $this->assertFalse(isset($v[-1]));
    }

    /**
     * Tests array access operator is overloaded for unset()
     *
     */
    #[@test]
    public function vectorRemoveElement() {
      $v= new Vector();
      $v[]= new XPString('world');
      unset($v[0]);
      $this->assertFalse(isset($v[0]));
    }

    /**
     * Tests Vector is usable in foreach()
     *
     */
    #[@test]
    public function vectorIsUsableInForeach() {
      $values= array(new XPString('hello'), new XPString('world'));
      foreach (new Vector($values) as $i => $value) {
        $this->assertEquals($values[$i], $value);
      }
      $this->assertEquals(sizeof($values)- 1, $i);
    }

    /**
     * Tests string class array access operator overloading
     *
     */
    #[@test]
    public function stringReadChar() {
      $s= new XPString('Hello');
      $this->assertEquals(new Character('H'), $s[0]);
      $this->assertEquals(new Character('e'), $s[1]);
      $this->assertEquals(new Character('l'), $s[2]);
      $this->assertEquals(new Character('l'), $s[3]);
      $this->assertEquals(new Character('o'), $s[4]);
    }

    /**
     * Tests string class array access operator overloading
     *
     */
    #[@test, @expect('lang.IndexOutOfBoundsException')]
    public function stringReadBeyondOffset() {
      $s= new XPString('Hello');
      $s[5];
    }

    /**
     * Tests string class array access operator overloading
     *
     */
    #[@test, @expect('lang.IndexOutOfBoundsException')]
    public function stringReadNegativeOffset() {
      $s= new XPString('Hello');
      $s[-1];
    }

    /**
     * Tests string class array access operator overloading
     *
     */
    #[@test]
    public function stringReadUtfChar() {
      $s= new XPString('Übercoder', 'iso-8859-1');
      $this->assertEquals(new Character('Ü', 'iso-8859-1'), $s[0]);
    }

    /**
     * Tests string class array access operator overloading
     *
     */
    #[@test]
    public function stringWriteChar() {
      $s= new XPString('Übercoder', 'iso-8859-1');
      $s[0]= 'U';
      $this->assertEquals(new XPString('Ubercoder'), $s);
    }

    /**
     * Tests string class array access operator overloading
     *
     */
    #[@test]
    public function stringWriteUtfChar() {
      $s= new XPString('Ubercoder');
      $s[0]= new Character('Ü', 'iso-8859-1');
      $this->assertEquals(new XPString('Übercoder', 'iso-8859-1'), $s);
    }

    /**
     * Tests string class array access operator overloading
     *
     */
    #[@test, @expect('lang.IllegalArgumentException')]
    public function stringWriteMoreThanOneChar() {
      $s= new XPString('Hallo');
      $s[0]= 'Halli H';   // Hoping somehow this would become "Halli Hallo":)
    }

    /**
     * Tests string class array access operator overloading
     *
     */
    #[@test, @expect('lang.IndexOutOfBoundsException')]
    public function stringWriteBeyondOffset() {
      $s= new XPString('Hello');
      $s[5]= 's';
    }

    /**
     * Tests string class array access operator overloading
     *
     */
    #[@test, @expect('lang.IndexOutOfBoundsException')]
    public function stringWriteNegativeOffset() {
      $s= new XPString('Hello');
      $s[-1]= "\x00";
    }

    /**
     * Tests string class array access operator overloading
     *
     */
    #[@test, @expect('lang.IllegalArgumentException')]
    public function stringAppend() {
      $s= new XPString('Hello');
      $s[]= ' ';   // use concat() instead
    }

    /**
     * Tests string class array access operator overloading
     *
     */
    #[@test]
    public function stringTestChar() {
      $s= new XPString('Übercoder', 'iso-8859-1');
      $this->assertTrue(isset($s[0]));
      $this->assertTrue(isset($s[$s->length()- 1]));
      $this->assertFalse(isset($s[$s->length()]));
      $this->assertFalse(isset($s[-1]));
    }

    /**
     * Tests string class array access operator overloading
     *
     */
    #[@test]
    public function stringRemoveChar() {
      $s= new XPString('Übercoder', 'iso-8859-1');
      unset($s[0]);
      $this->assertEquals(new XPString('bercoder'), $s);
    }

    /**
     * Tests hashset array access operator overloading
     *
     */
    #[@test]
    public function hashSetAddElement() {
      $s= new HashSet();
      $s[]= new XPString('X');
      $this->assertTrue($s->contains(new XPString('X')));
    }

    /**
     * Tests hashset array access operator overloading
     *
     */
    #[@test, @expect('lang.IllegalArgumentException')]
    public function hashSetWriteElement() {
      $s= new HashSet();
      $s[0]= new XPString('X');
    }

    /**
     * Tests hashset array access operator overloading
     *
     */
    #[@test, @expect('lang.IllegalArgumentException')]
    public function hashSetReadElement() {
      $s= new HashSet();
      $s[]= new XPString('X');
      $x= $s[0];
    }

    /**
     * Tests hashset array access operator overloading
     *
     */
    #[@test]
    public function hashSetTestElement() {
      $s= new HashSet();
      $this->assertFalse(isset($s[new XPString('X')]));
      $s[]= new XPString('X');
      $this->assertTrue(isset($s[new XPString('X')]));
    }

    /**
     * Tests hashset array access operator overloading
     *
     */
    #[@test]
    public function hashSetRemoveElement() {
      $s= new HashSet();
      $s[]= new XPString('X');
      unset($s[new XPString('X')]);
      $this->assertFalse(isset($s[new XPString('X')]));
    }

    /**
     * Tests hashset array access operator overloading
     *
     */
    #[@test]
    public function hashSetUsableInForeach() {
      $s= new HashSet();
      $s->addAll(array(new XPString('0'), new XPString('1'), new XPString('2')));
      foreach ($s as $i => $element) {
        $this->assertEquals(new XPString($i), $element);
      }
    }
  }
?>
