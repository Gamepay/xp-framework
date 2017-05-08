<?php
/* This class is part of the XP framework
 *
 * $Id$ 
 */

  uses('text.doclet.AuthorTag', 'text.doclet.Taglet');

  /**
   * A taglet that represents the author tag. 
   *
   * @test     xp://net.xp_framework.unittest.doclet.AuthorTagletTest
   * @see      xp://text.doclet.TagletManager
   * @purpose  Taglet
   */
  class AuthorTaglet extends XPObject implements Taglet {
     
    /**
     * Create tag from text
     *
     * @param   text.doclet.Doc holder
     * @param   string kind
     * @param   string text
     * @return  text.doclet.Tag
     */ 
    public function tagFrom($holder, $kind, $text) {
      for ($parse= $text.' ', $i= 0, $s= strlen($parse), $brackets= 0; $i < $s; $i++) {
        if (' ' === $parse{$i} && 0 === $brackets) {
          $m= explode(' ', (string)substr($parse, $i+ 1, -1), 2);
          return new AuthorTag(substr($parse, 0, $i), $m[0], isset($m[1]) ? $m[1] : '');
        } else if ('<' === $parse{$i}) {
          $brackets++;
        } else if ('>' === $parse{$i}) {
          $brackets--;
        }
      }
      return new AuthorTag('void', 'arg', $text);
    }
  } 
?>
