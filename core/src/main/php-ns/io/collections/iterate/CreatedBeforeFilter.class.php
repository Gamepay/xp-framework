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
  class CreatedBeforeFilter extends AbstractDateComparisonFilter {

    /**
     * Accepts an element
     *
     * @param   io.collections.IOElement element
     * @return  bool
     */
    public function accept($element) { 
      return ($cmp= $element->createdAt()) && $cmp->isBefore($this->date);
    }
  }
?>
