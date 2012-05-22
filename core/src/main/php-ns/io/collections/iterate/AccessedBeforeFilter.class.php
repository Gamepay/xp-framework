<?php
/* This class is part of the XP framework
 *
 * $Id$
 */

  namespace io\collections\iterate;
 use io\collections\iterate\AbstractDateComparisonFilter;

  /**
   * Date comparison filter
   *
   * @purpose  Iteration Filter
   */
  class AccessedBeforeFilter extends AbstractDateComparisonFilter {

    /**
     * Accepts an element
     *
     * @param   io.collections.IOElement element
     * @return  bool
     */
    public function accept($element) { 
      return ($cmp= $element->lastAccessed()) && $cmp->isBefore($this->date);
    }
  }
?>
