<?php
namespace App\Model\Behavior;

use Cake\Event\Event;
use Cake\I18n\Time;
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
    public function increaseLife(Entity $entity)
    {
        $config = $this->config();
        if (!$this->checkLife($entity)) {
            return false;
        }
        if ($entity->get($config['max_views']) != null) {
            $views = $entity->get($config['views']);
            $entity->set($config['views'], $views + 1);
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
        if (array_key_exists('death_date', $data) && isset($data['death_date'])) {
            $parsedDate =  Time::parseDateTime($data['death_date'], 'Y/M/d H:mm');
            if ($parsedDate) {
                $offset = isset($data['timezone-offset']) ? floor($data['timezone-offset']) : 0;
                $parsedDate->addMinutes($offset); // also Ok if offset is < 0
                $data['death_time'] =$parsedDate;
            }
        }
        // Compute the death_time according to now and nb days in parameter
        else if (array_key_exists('death_time', $data)) {
            if ($data['death_time']) {
                 $deathTime = new Time();
                 $data['death_time'] = $deathTime->addDays($data['death_time']);
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
    public function checkLife(Entity $entity)
    {
        // The link is dead
        if ($entity->life_percentage >= 100) {
            return false;
        }
        return true;
    }
}
