<?php
namespace App\View\Helper;

use Cake\View\Helper;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;
use Cake\Filesystem\File;

class EmailProcessingHelper extends Helper
{

    /**
     * Process Email HTML content after rendering of the email
     *
     * @param string $layoutFile The layout file that was rendered.
     * @return void
     */
    public function afterLayout()
    {
        $html = $this->_View->Blocks->get('content');
        $cssToInlineStyles = new CssToInlineStyles($html);
        //TODO dynamicaly transform css files specified in the template
        $file = new File(WWW_ROOT . 'css/libs/bootstrap/bootstrap.min.css');
        $css = $file->read();
        $file->close();
        $cssToInlineStyles->setCSS($css);
        //$cssToInlineStyles->setUseInlineStylesBlock(true);
        $html = $cssToInlineStyles->convert();
        $this->_View->Blocks->set('content', $html);
    }
}
