<?php
/* This file is part of the XP framework's experiments
 *
 * $Id$
 */

  namespace io\streams;
 use lang\Closeable;

  /**
   * An OuputStream can be written to
   *
   */
  interface OutputStream extends Closeable {

    /**
     * Write a string
     *
     * @param   var arg
     */
    public function write($arg);

    /**
     * Flush this buffer
     *
     */
    public function flush();
  }
?>
