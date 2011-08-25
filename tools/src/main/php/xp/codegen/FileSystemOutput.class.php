<?php
/* This class is part of the XP framework
 *
 * $Id$ 
 */

  uses(
    'xp.codegen.AbstractOutput',
    'io.File',
    'io.Folder',
    'io.FileUtil'
  );

  /**
   * Output for generation
   *
   * @purpose  Abstract base class
   */
  class FileSystemOutput extends AbstractOutput {
    protected
      $path = NULL;
      
    /**
     * Constructor
     *
     * @param   string path
     */
    public function __construct($path) {
      $this->path= new Folder($path);
    }

    /**
     * Store data
     *
     * @param   string name
     * @param   string data
     */
    protected function store($name, $data, $overwrite= TRUE) {
      FileUtil::setContents(new File($this->path, $name), $data);
    }
    
    /**
     * Data for the given name already exists
     *
     * @param string $name
     * @return boolean
     */
    protected function exists($name) {
      $file= new File($this->path, $name);
      return $file->exists();
    }
    
    /**
     * Commit output
     *
     */
    public function commit() {
      // NOOP
    }
  }
?>
