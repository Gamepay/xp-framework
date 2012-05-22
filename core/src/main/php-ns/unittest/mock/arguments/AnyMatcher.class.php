<?php
/* This class is part of the XP framework
 *
 * $Id$ 
 */

  namespace unittest\mock\arguments;
 use unittest\mock\arguments\IArgumentMatcher;

  /**
   * Trivial argument matcher, that just returns true.
   *
   */
  class AnyMatcher extends \lang\Object implements IArgumentMatcher {

    /**
     * Trivial matches implementations.
     * 
     * @param   var value
     * @return  bool
     */
    public function matches($value) {
      return TRUE;
    }
  }
?>
