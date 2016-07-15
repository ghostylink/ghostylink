<?php
namespace App\View\Helper;

use Cake\View\Helper;
use App\Model\Entity\Link;

/**
 * CakePHP LinkHelper
 * @author kremy
 */
class LinkHelper extends Helper
{
    public $helpers = ["ViewsLimit", 'GoogleCaptcha', 'DeathTime', 'GhostyficationAlert'];
    
    public function displayComponents(Link $model = null)
    {
        if (!$model) {
            return;
        }
        $components = $model->getComponents();
        foreach ($components as $comp) {
            $this->{$comp}->display($model);
        }
    }
    
    public function displayFields(Link $model = null)
    {
        if (!$model) {
            return;
        }
        $components = $model->getComponents();
        foreach ($components as $comp) {
            $this->{$comp}->displayField($model);
        }
    }
}
