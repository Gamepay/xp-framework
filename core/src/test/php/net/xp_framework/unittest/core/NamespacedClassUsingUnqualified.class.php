<?php
/* This class is part of the XP framework
 *
 * $Id$ 
 */

  namespace net\xp_framework\unittest\core;
  
  use lang\types\XPString;

  /**
   * Fixture for namespaces tests
   *
   * @see   xp://net.xp_framework.unittest.core.NamespacedClassesTest
   */
  class NamespacedClassUsingUnqualified extends \lang\XPObject {
    
    /**
     * Returns an empty string
     *
     * @return  lang.types.XPString
     */
    public function getEmptyString() {
      return XPString::$EMPTY;
    }
  }
?>
