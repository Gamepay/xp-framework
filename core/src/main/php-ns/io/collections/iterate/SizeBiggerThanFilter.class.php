<?php
/* This class is part of the XP framework
 *
 * $Id$
 */

  namespace io\collections\iterate;
 use io\collections\iterate\AbstractSizeComparisonFilter;

  /**
   * Size comparison filter
   *
   * @purpose  Iteration Filter
   */
  class SizeBiggerThanFilter extends AbstractSizeComparisonFilter {

    /**
     * Accepts an element
     *
     * @param   io.collections.IOElement element
     * @return  bool
     */
    public function accept($element) {
      return $element->getSize() > $this->size;
    }
  }
?>
