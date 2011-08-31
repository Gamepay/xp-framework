<?php
/* This class is part of the XP framework
 *
 * $Id$ 
 */

  uses('net.xp_framework.unittest.rdbms.integration.RdbmsIntegrationTest');

  /**
   * MySQL integration test
   *
   * @ext       mysql
   */
  class MySQLIntegrationTest extends RdbmsIntegrationTest {

    /**
     * Set up testcase
     *
     */
    public function setUp() {
      parent::setUp();
      if ('real' !== ($q= $this->db()->query('select 0.5 as value')->fields['value'])) {
        throw new PrerequisitesNotMetError('Broken mysql library - select 0.5 yields '.$q.' as type');
      }
    }
    
    /**
     * Retrieve dsn
     *
     * @return  string
     */
    public function _dsn() {
      return 'mysql';
    }
    
    /**
     * Helper method to create table
     *
     */
    protected function createTable() {
      $this->removeTable('unittest');
      $this->db()->query('create table unittest (pk int, username varchar(30)) engine=innodb default charset=utf8');
      $this->db()->insert('into unittest values (1, "kiesel")');
      $this->db()->insert('into unittest values (2, "kiesel")');
    }
    
    /**
     * Create autoincrement table
     *
     * @param   string name
     */
    protected function createAutoIncrementTable($name) {
      $this->removeTable($name);
      $this->db()->query('create table %c (pk int primary key auto_increment, username varchar(30))', $name);
    }
  }
?>
