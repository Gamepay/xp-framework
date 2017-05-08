<?php
/* This class is part of the XP framework
 *
 * $Id$
 */

  uses(
    'unittest.TestCase',
    'text.doclet.ClassDoc',
    'text.doclet.RootDoc'
  );

  /**
   * TestCase
   *
   * @see      xp://text.doclet.ClassDoc
   * @purpose  Unittest
   */
  class ClassDocTest extends TestCase {
    protected
      $root = NULL;

    /**
     * Sets up test case
     *
     */
    public function setUp() {
      $this->root= new RootDoc();
    }

    /**
     * Test name() method
     *
     */
    #[@test]
    public function name() {
      $this->assertEquals('XPObject', $this->root->classNamed('lang.XPObject')->name());
    }

    /**
     * Test qualifiedName() method
     *
     */
    #[@test]
    public function qualifiedName() {
      $this->assertEquals('lang.XPObject', $this->root->classNamed('lang.XPObject')->qualifiedName());
    }

    /**
     * Test fully qualified classes' names
     *
     * @see   http://xp-framework.net/rfc/0037
     */
    #[@test]
    public function namesForFullyQualified() {
      with ($classdoc= $this->root->classNamed('lang.reflect.Parameter')); {
        $this->assertEquals('Parameter', $classdoc->name());
        $this->assertEquals('lang.reflect.Parameter', $classdoc->qualifiedName());
      }
    }

    /**
     * Test fully qualified classes' names in namespace
     *
     * @see   http://xp-framework.net/rfc/0222
     */
    #[@test]
    public function namesForNamespacedFullyQualified() {
      if (version_compare(PHP_VERSION, '5.3.0', 'gt')) {
        with ($classdoc= $this->root->classNamed('net.xp_framework.unittest.text.doclet.Namespaced')); {
          $this->assertEquals('Namespaced', $classdoc->name());
          $this->assertEquals('net.xp_framework.unittest.text.doclet.Namespaced', $classdoc->qualifiedName());
        }
      }
    }

    /**
     * Test containingPackage() method
     *
     */
    #[@test]
    public function containingPackage() {
      $this->assertEquals(
        $this->root->packageNamed('lang'),
        $this->root->classNamed('lang.XPObject')->containingPackage()
      );
    }

    /**
     * Test sourceFile() method
     *
     */
    #[@test]
    public function sourceFile() {
      $file= $this->root->classNamed('lang.XPObject')->sourceFile();
      $this->assertEquals('XPObject.class.php', $file->getFilename());
    }
  }
?>
