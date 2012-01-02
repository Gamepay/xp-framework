<?php
/* This class is part of the XP framework
 *
 * $Id$
 */

  uses('peer.server.Server', 'lang.RuntimeError');

  /**
   * Forking TCP/IP Server
   *
   * @ext      pcntl
   * @see      xp://peer.server.Server
   * @purpose  TCP/IP Server
   */
  class ForkingServer extends Server {
    
    /**
     * Service
     *
     */
    public function service() {
      if (!$this->socket->isConnected()) return FALSE;
      
      $tcp= getprotobyname('tcp');
      while (!$this->terminate) {
        try {
          $m= $this->socket->accept();
        } catch (IOException $e) {
          $this->shutdown();
          break;
        }
        if (!$m) continue;

        // Have connection, fork child
        $pid= pcntl_fork();
        if (-1 == $pid) { // Could not fork

          // Send client an error and disconnect
          $m->write("500 Server busy\r\n");
          $m->close();

          // Wait until one child terminates, then forking might work again
          pcntl_waitpid(-1, $status);

          // A child has joined, now continue to main loop...
          continue;
        } else if ($pid) {      // Parent

          // Close own copy of message socket
          $m->close();
          delete($m);
          
          // Use waitpid w/ NOHANG to avoid zombies hanging around
          while (pcntl_waitpid(-1, $status, WNOHANG)) { }
        } else {                // Child
          // Handle initialization of protocol. This is called once for 
          // every new child created
          $this->protocol->initialize();

          $this->tcpnodelay && $m->setOption($tcp, TCP_NODELAY, TRUE);
          $this->protocol->handleConnect($m);

          // Loop
          do {
            try {
              $this->protocol->handleData($m);
            } catch (IOException $e) {
              $this->protocol->handleError($m, $e);
              break;
            }

          } while (!$m->eof());

          $this->protocol->handleDisconnect($m);
          $m->close();

          // Exit out of child
          exit();
        }
      }
    }
  }
?>
