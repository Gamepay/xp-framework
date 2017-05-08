<?php
/* This class is part of the XP framework
 *
 * $Id$ 
 */
  
  $package= 'net.xp_framework.unittest.core.extensions';

  /**
   * XPThrowable extension methods
   *
   * @see   https://gist.github.com/2168311
   * @see   xp://net.xp_framework.unittest.core.extensions.ExtensionInvocationTest
   */
  class net·xp_framework·unittest·core·extensions·XPThrowableExtensions extends XPObject {

    static function __import($scope) {
      xp::extensions(__CLASS__, $scope);
    }

    /**
     * Clears stacktrace
     *
     * @param   lang.XPThrowable self
     */
    public static function clearStackTrace(XPThrowable $self) {
      $self->trace= array();
    }
  }
?>
