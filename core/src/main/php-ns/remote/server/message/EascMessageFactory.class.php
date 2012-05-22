<?php
/* This class is part of the XP framework
 *
 * $Id$ 
 */

  namespace remote\server\message;
 use 
    remote\protocol\XpProtocolConstants,
    remote\server\message\EascInitMessage,
    remote\server\message\EascLookupMessage,
    remote\server\message\EascValueMessage,
    remote\server\message\EascExceptionMessage,
    remote\server\message\EascCallMessage
  ;

  /**
   * Factory class for EASC message classes
   *
   * @purpose  Create EASC message
   */
  abstract class EascMessageFactory extends \lang\Object {
    protected static 
      $handlers= array();
    
    static function __static() {
      self::$handlers[REMOTE_MSG_INIT]= \lang\XPClass::forName('remote.server.message.EascInitMessage');
      self::$handlers[REMOTE_MSG_LOOKUP]= \lang\XPClass::forName('remote.server.message.EascLookupMessage');
      self::$handlers[REMOTE_MSG_CALL]= \lang\XPClass::forName('remote.server.message.EascCallMessage');
      self::$handlers[REMOTE_MSG_VALUE]= \lang\XPClass::forName('remote.server.message.EascValueMessage');
      self::$handlers[REMOTE_MSG_EXCEPTION]= \lang\XPClass::forName('remote.server.message.EascExceptionMessage');
    }
  
    /**
     * Set handler for a given type to 
     *
     * @param   int type
     * @param   lang.XPClass class
     */
    public static function setHandler($type, \lang\XPClass $class) {
      self::$handlers[$type]= $class;
    }
  
    /**
     * Factory method
     *
     * @param   int type
     * @return  remote.server.message.EascMessage
     * @throws  lang.IllegalArgumentException if no message exists for this type.
     */
    public static function forType($type) {
      if (!isset(self::$handlers[$type])) {
        throw new \lang\IllegalArgumentException('Unknown message type '.$type);
      }
      return self::$handlers[$type]->newInstance();
    }
  }
?>
