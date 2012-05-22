<?php
/* This class is part of the XP framework
 *
 * $Id$ 
 */

  namespace rdbms;
 use util\Observer;

  /**
   * Generic DB observer interface.
   *
   * @purpose  DB observer interface
   */
  interface DBObserver extends Observer {
  
    /**
     * Retrieves an instance.
     *
     * @param   var argument
     * @return  rdbms.DBObserver
     */
    public static function instanceFor($arg);
  }
?>
