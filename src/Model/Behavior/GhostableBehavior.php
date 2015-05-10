<?php
namespace App\Model\Behavior;

use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Entity;

class GhostableBehavior extends Behavior
{
    protected $_defaultConfig = [
        'views' => 'views',
        'max_views' => 'max_views'
    ];
    
    /**
     * Increase the view counter
     * @param Entity $entity the entity the view counter has to be incremented
     * @return Return if the counter views reached his limit or not
     */
    public function increaseViews(Entity $entity)
    {
        $config = $this->config();
        $views = $entity->get($config['views']);
        $entity->set($config['views'], $views + 1);
        if (!$this->checkNbViews($entity)) {
            return false;
        }
        return true;
    }
    
    /**
     * Check the counter views
     * @param Entity $entity the entity the view counter has to be incremented
     * @return Return if the counter views reached his limit or not
     */
    private function checkNbViews(Entity $entity)
    {
        $config = $this->config();
        if($entity->get($config['views']) >
            $entity->get($config['max_views'])) {
            return false;
        }
        return true;
    }
}
