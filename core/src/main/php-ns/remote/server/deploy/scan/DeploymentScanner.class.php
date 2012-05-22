<?php
/* This class is part of the XP framework
 *
 * $Id$ 
 */

  namespace remote\server\deploy\scan;
 use remote\server\deploy\Deployment;

  /**
   * Deployment scanner
   *
   * @purpose  Interface
   */
  interface DeploymentScanner {
  
    /**
     * Scan if deployments changed
     *
     * @return  bool 
     */
    public function scanDeployments();

    /**
     * Get a list of deployments
     *
     * @return  remote.server.deploy.Deployable[]
     */
    public function getDeployments();

  }
?>
