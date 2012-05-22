<?php
/* This class is part of the XP framework
 *
 * $Id$ 
 */

  namespace rdbms\criterion;
 use rdbms\criterion\Criterion;

  /**
   * Between expression
   *
   * @purpose  Criterion
   */
  class BetweenExpression extends \lang\Object implements Criterion {
    public
      $lhs    = '',
      $lo     = NULL,
      $hi     = NULL;

    /**
     * Constructor
     *
     * @param   var lhs either a string or an SQLFragment
     * @param   var lo
     * @param   var hi
     */
    public function __construct($lhs, $lo, $hi) {
      $this->lhs= $lhs;
      $this->lo= $lo;
      $this->hi= $hi;
    }
  
    /**
     * Returns the fragment SQL
     *
     * @param   rdbms.DBConnection conn
     * @param   rdbms.Peer peer
     * @return  string
     * @throws  rdbms.SQLStateException
     */
    public function asSql(\rdbms\DBConnection $conn, \rdbms\Peer $peer) { 
      $lhs= ($this->lhs instanceof SQLFragment) ? $this->lhs : $peer->column($this->lhs);

      return $conn->prepare(
        '%c between '.$lhs->getType().' and '.$lhs->getType(), 
        $lhs,
        $this->lo,
        $this->hi
      );
    }
  } 
?>
