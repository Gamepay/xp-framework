<?php
/* This class is part of the XP framework
 *
 * $Id$
 */
 
 
  uses('lang.XPThrowable');
  
  /**
   * An XPError is a subclass of XPThrowable that indicates serious problems 
   * that a reasonable application should not try to catch. Most such 
   * errors are abnormal conditions.
   *
   * @purpose  Base class for all other errors
   * @see      http://java.sun.com/docs/books/tutorial/essential/exceptions/definition.html
   * @see      http://jinx.swiki.net/352
   * @see      http://www.artima.com/designtechniques/exceptions.html
   * @see      http://www.artima.com/designtechniques/desexcept.html
   */
  class XPError extends XPThrowable {
     
  }
?>
