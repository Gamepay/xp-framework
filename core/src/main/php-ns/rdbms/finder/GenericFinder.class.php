<?php
/* This class is part of the XP framework
 *
 * $Id$
 */

  namespace rdbms\finder;
 use rdbms\finder\Finder;

  /**
   * Generic finder that makes rdbms.Peer objects usable as finders.
   *
   * @test     xp://net.xp_framework.unittest.rdbms.FinderTest
   * @see      xp://rdbms.Peer
   */
  class GenericFinder extends Finder {
    protected $peer= NULL;

    /**
     * Creates a new GenericFinder instance with a given Peer object.
     *
     * @param   rdbms.Peer peer
     */
    public function __construct(\rdbms\Peer $peer) {
      $this->peer= $peer;
    }

    /**
     * Retrieve this finder's peer object
     *
     * @return  rdbms.Peer
     */
    public function getPeer() {
      return $this->peer;
    }
  }
?>
