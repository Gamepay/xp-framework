<?php
/* This class is part of the XP framework
 *
 * $Id$
 */

  
  /**
   * Error formatter interface
   *
   * @purpose Caster
   */
  interface RestErrorFormatter {
    
    /**
     * Format a throwable int oa specific object
     * 
     * @param lang.XPThrowable e
     * @return webservices.rest.server.RestFormattedError
     */
    public function format(XPThrowable $e);
  }
?>
