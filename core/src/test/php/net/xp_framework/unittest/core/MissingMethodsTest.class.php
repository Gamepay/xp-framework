<?php
/* This class is part of the XP framework
 *
 * $Id$
 */

  uses('unittest.TestCase');

  /**
   * Verifies lang.XPObject's <tt>__call()</tt> implementation
   *
   */
  class MissingMethodsTest extends TestCase {

    /**
     * Tests missing methods
     *
     */
    #[@test, @expect(class= 'lang.XPError', withMessage= '/Call to undefined method lang.XPObject::run()/')]
    public function missingMethodInvocation() {
      $o= new XPObject();
      $o->run();
    }

    /**
     * Tests missing methods
     *
     * @see   https://github.com/xp-framework/xp-framework/issues/133
     */
    #[@test, @expect(class= 'lang.XPError', withMessage= '/Call to undefined method lang.XPObject::run()/')]
    public function missingParentMethodInvocation() {
      $o= newinstance('lang.XPObject', array(), '{
        public function run() {
          parent::run();
        }
      }');
      $o->run();
    }
  }
?>
