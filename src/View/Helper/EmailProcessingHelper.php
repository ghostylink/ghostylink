<?php
namespace App\View\Helper;

use Cake\View\Helper;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class EmailProcessingHelper extends Helper
{

    /**
     * Process Email HTML content after rendering of the email
     *
     * @return void
     */
    public function afterLayout()
    {
        $html = $this->_View->Blocks->get('content');
        $cssToInlineStyles = new CssToInlineStyles($html);
        $cssToInlineStyles->setUseInlineStylesBlock(true);
        $html = $cssToInlineStyles->convert();
        $this->_View->Blocks->set('content', $html);
    }
}
