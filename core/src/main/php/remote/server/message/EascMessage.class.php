<?php
/* This class is part of the XP framework
 *
 * $Id$ 
 */

  /**
   * EASC server base message
   *
   * @purpose  EASC message
   */
  abstract class EascMessage extends XPObject {
    public
      $value  = NULL;
    
    /**
     * Get type of message
     *
     * @return  int
     */
    public abstract function getType();    

    /**
     * Set Value
     *
     * @param   lang.XPObject value
     */
    public function setValue($value) {
      $this->value= $value;
    }

    /**
     * Get Value
     *
     * @return  lang.XPObject
     */
    public function getValue() {
      return $this->value;
    }
    
    /**
     * Handle message
     *
     * @param   remote.server.EASCProtocol protocol
     * @return  var data
     */
    public function handle($protocol, $data) { }
  }
?>
