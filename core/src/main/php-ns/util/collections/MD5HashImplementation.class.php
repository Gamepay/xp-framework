<?php
/* This class is part of the XP framework
 *
 * $Id$
 */

  namespace util\collections;
 use util\collections\HashImplementation;

  /**
   * MD5
   *
   * @see      php://md5
   * @see      xp://util.collections.HashProvider
   * @purpose  Hashing
   */
  class MD5HashImplementation extends \lang\Object implements HashImplementation {

    /**
     * Retrieve hash code for a given string
     *
     * @param   string str
     * @return  int hashcode
     */
    public function hashOf($str) {
      return '0x'.md5($str);
    }
  } 
?>
