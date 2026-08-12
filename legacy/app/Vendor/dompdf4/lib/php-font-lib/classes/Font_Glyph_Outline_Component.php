<?php
/**
 * @package php-font-lib
 * @link    https://github.com/PhenX/php-font-lib
 * @author  Fabien Ménager <fabien.menager@gmail.com>
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * @version $Id: Font_Glyph_Outline_Component.php,v 1.1 2017/12/22 16:15:22 tisandler Exp $
 */

/**
 * Glyph outline component
 * 
 * @package php-font-lib
 */
class Font_Glyph_Outline_Component {
  public $flags;
  public $glyphIndex;
  public $a, $b, $c, $d, $e, $f;
  public $point_compound;
  public $point_component;
  public $instructions;

  function getMatrix(){
    return array(
      $this->a, $this->b,
      $this->c, $this->d,
      $this->e, $this->f,
    );
  }
}