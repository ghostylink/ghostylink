<?php
namespace App\Model\Behavior;

use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Entity;

class GhostableBehavior extends Behavior
{
    protected $_defaultConfig = [
        'views' => 'views',
        'max_views' => 'max_views',
        'life_percentage' => 'life_percentage'
    ];
    
    /**
     * Increase the view counter
     * @param Entity $entity the entity the view counter has to be incremented
     * @return Return false if the counter view reached is limit
     */
    public function increaseViews(Entity $entity)
    {
        $config = $this->config();
        if ($entity->get($config['max_views']) != null) {
            $views = $entity->get($config['views']);
            $entity->set($config['views'], $views + 1);
        }
        if (!$this->checkLife($entity)) {
            return false;
        }
        return true;
    }
    
    /**
     * Event triggered before data is converted to an entity
     * @param Event $event the event which is triggered
     * @param \ArrayObject $data the request data
     * @param \ArrayObject $options additional options
     */
    public function beforeMarshal(Event $event, \ArrayObject $data, \ArrayObject $options)
    {
        // Compute the death_time according to now and nb days in parameter
        if (array_key_exists('death_time', $data)) {
            if ($data['death_time']) {
                $deathTime = new \DateTime();
                $deathTime->format('Y-m-d H:i:s');
                $data['death_time'] = $deathTime->add(new \DateInterval('P' . $data['death_time'] . 'D'));
            } else { // Create an empty death_time in order to check in validator
                $data['death_time'] = '';
            }
        }
        // Create an empty max_views in order to check in validator
        if (!(array_key_exists('max_views', $data))) {
            $data['max_views'] = '';
        }
        // Create an empty death_time in order to check in validator
        if (!(array_key_exists('death_time', $data))) {
            $data['death_time'] = '';
        }
    }
    
    /**
     * Check the life percentage of the link
     * @param Entity $entity the entity the view counter has to be incremented
     * @return Return false if the link is dead
     */
    private function checkLife(Entity $entity)
    {
        $config = $this->config();
        // The link is dead
        if ($entity->life_percentage == 100) {
            return false;
        }
        return true;
    }
}
