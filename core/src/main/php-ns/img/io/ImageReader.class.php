<?php
/* This class is part of the XP framework
 *
 * $Id$ 
 */

  namespace img\io;
 use img\ImagingException;

  /**
   * Read images
   *
   * @see      xp://img.Image#loadFrom
   * @purpose  Interface
   */
  interface ImageReader {
  
    /**
     * Retrieve an image resource
     *
     * @return  resource
     * @throws  img.ImagingException
     */
    public function getResource();
  }
?>
