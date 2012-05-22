<?php
/* This class is part of the XP framework
 *
 * $Id$ 
 */

  namespace remote\server\message;
 use remote\server\message\EascMessage;

  /**
   * EASC exception message
   *
   * @purpose  Exception message
   */
  class EascExceptionMessage extends EascMessage {

    /**
     * Get type of message
     *
     * @return  int
     */
    public function getType() {
      return REMOTE_MSG_EXCEPTION;
    }
  }
?>
