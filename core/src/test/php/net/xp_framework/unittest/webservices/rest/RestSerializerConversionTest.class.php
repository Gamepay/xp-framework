<?php
/* This class is part of the XP framework
 *
 * $Id$ 
 */

  uses(
    'unittest.TestCase',
    'webservices.rest.RestSerializer',
    'net.xp_framework.unittest.webservices.rest.ConstructorFixture',
    'net.xp_framework.unittest.webservices.rest.IssueWithField',
    'net.xp_framework.unittest.webservices.rest.IssueWithUnderscoreField',
    'net.xp_framework.unittest.webservices.rest.IssueWithSetter',
    'net.xp_framework.unittest.webservices.rest.IssueWithUnderscoreSetter'
  );

  /**
   * TestCase
   *
   * @see   xp://webservices.rest.RestDeserializer
   */
  class RestSerializerConversionTest extends TestCase {
    protected $fixture= NULL;
  
    /**
     * Sets up test case
     *
     */
    public function setUp() {
      $this->fixture= newinstance('RestSerializer', array(), '{
        public function contentType() { /* Intentionally empty */ }
        public function serialize($payload) { /* Intentionally empty */ }
      }');
    }
    
    /**
     * Test null
     *
     */
    #[@test]
    public function null() {
      $this->assertEquals(NULL, $this->fixture->convert(NULL));
    }

    /**
     * Test a string
     *
     */
    #[@test]
    public function string() {
      $this->assertEquals('Hello', $this->fixture->convert('Hello'));
    }

    /**
     * Test an integer
     *
     */
    #[@test]
    public function int() {
      $this->assertEquals(6100, $this->fixture->convert(6100));
    }

    /**
     * Test a double
     *
     */
    #[@test]
    public function double() {
      $this->assertEquals(1.5, $this->fixture->convert(1.5));
    }

    /**
     * Test a boolean
     *
     */
    #[@test]
    public function bool() {
      $this->assertEquals(TRUE, $this->fixture->convert(TRUE));
    }

    /**
     * Test an array of strings
     *
     */
    #[@test]
    public function string_array() {
      $this->assertEquals(array('Hello', 'World'), $this->fixture->convert(array('Hello', 'World')));
    }

    /**
     * Test an array of strings
     *
     */
    #[@test]
    public function string_map() {
      $this->assertEquals(
        array('greeting' => 'Hello', 'name' => 'World'),
        $this->fixture->convert(array('greeting' => 'Hello', 'name' => 'World'))
      );
    }

    /**
     * Test a date instance
     *
     */
    #[@test]
    public function date_instance() {
      $this->assertEquals(
        '2012-12-31T18:00:00+01:00',
        $this->fixture->convert(new Date('2012-12-31 18:00:00', new TimeZone('Europe/Berlin')))
      );
    }

    /**
     * Test a date instance
     *
     */
    #[@test]
    public function date_array() {
      $this->assertEquals(
        array('2012-12-31T18:00:00+01:00'),
        $this->fixture->convert(array(new Date('2012-12-31 18:00:00', new TimeZone('Europe/Berlin'))))
      );
    }

    /**
     * Test value object
     *
     */
    #[@test]
    public function issue_with_field() {
      $issue= new net�xp_framework�unittest�webservices�rest�IssueWithField(1, 'test');
      $this->assertEquals(
        array('issueId' => 1, 'title' => 'test'), 
        $this->fixture->convert($issue)
      );
    }

    /**
     * Test value object
     *
     */
    #[@test]
    public function issue_with_getter() {
      $issue= new net�xp_framework�unittest�webservices�rest�IssueWithGetter(1, 'test');
      $this->assertEquals(
        array('issueId' => 1, 'title' => 'test', 'createdAt' => NULL), 
        $this->fixture->convert($issue)
      );
    }

    /**
     * Test array of value objects
     *
     */
    #[@test]
    public function array_of_issues() {
      $issues= array(
        new net�xp_framework�unittest�webservices�rest�IssueWithField(1, 'test1'),
        new net�xp_framework�unittest�webservices�rest�IssueWithField(2, 'test2')
      );
      $this->assertEquals(
        array(array('issueId' => 1, 'title' => 'test1'), array('issueId' => 2, 'title' => 'test2')),
        $this->fixture->convert($issues)
      );
    }

    /**
     * Test map of value objects
     *
     */
    #[@test]
    public function map_of_issues() {
      $issues= array(
        'one' => new net�xp_framework�unittest�webservices�rest�IssueWithField(1, 'test1'),
        'two' => new net�xp_framework�unittest�webservices�rest�IssueWithField(2, 'test2')
      );
      $this->assertEquals(
        array('one' => array('issueId' => 1, 'title' => 'test1'), 'two' => array('issueId' => 2, 'title' => 'test2')),
        $this->fixture->convert($issues)
      );
    }

    /**
     * Test value object
     *
     */
    #[@test]
    public function static_member_excluded() {
      $o= newinstance('lang.Object', array(), '{
        public $name= "Test";
        public static $instance;
      }');
      $this->assertEquals(array('name' => 'Test'), $this->fixture->convert($o));
    }
  }
?>
