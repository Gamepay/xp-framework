<?php
/* This class is part of the XP framework
 *
 * $Id$
 */

  namespace scriptlet\xml\workflow;
 use scriptlet\xml\workflow\AbstractHandler;

  /**
   * BC class for handlers which rely on old form value behavior
   * 
   * @see    https://github.com/xp-framework/xp-framework/issues/55
   * @deprecated
   */
  class Handler extends AbstractHandler {
    public $requestOverride= TRUE;
  }
?>
