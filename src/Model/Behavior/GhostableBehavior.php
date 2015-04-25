<?php

namespace App\Model\Behavior;

use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Entity;

class GhostableBehavior extends Behavior
{
    protected $_defaultConfig = [
        'views' => 'views'
    ];
    
    /**
     * Increase the view counter
     * @param Entity $entity
     */
    public function increaseViews(Entity $entity) {
        $config = $this->config();
        $views = $entity->get($config['views']);        
        $entity->set($config['views'],$views + 1);
    }
    
}

