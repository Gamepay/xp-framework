<?php
/* This class is part of the XP framework
 *
 * $Id$ 
 */

  namespace remote\server\message;
 use remote\server\message\EascMessage;

  /**
   * EASC value message
   *
   * @purpose  Value message
   */
  class EascValueMessage extends EascMessage {

    /**
     * Get type of message
     *
     * @return  int
     */
    public function getType() {
      return REMOTE_MSG_VALUE;
    }
  }
?>
