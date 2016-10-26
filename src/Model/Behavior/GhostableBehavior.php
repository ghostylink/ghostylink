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
        if (array_key_exists('death_date', $data)) {
            $parsedDate =  Time::parseDateTime($data['death_date'], 'Y/M/d H:mm');
            if ($parsedDate) {
                $offset = isset($data['timezone-offset']) ? floor($data['timezone-offset']) : 0;
                $parsedDate->addMinutes($offset); // also Ok if offset is < 0
                $data['death_time'] = $parsedDate;
            } elseif ($data["death_date"] == "") {
                $data['death_time'] = new Time('0000-01-01 00:00');
            }
        } elseif (array_key_exists('death_time', $data)) {
            if ($data['death_time']) {
                $deathTime = new Time();
                switch ($data['death_time']) {
                    case 1:
                        $data['death_time'] = $deathTime->addDay(1);
                        break;
                    case 7:
                        $data['death_time'] = $deathTime->addWeek(1);
                        break;
                    case 30:
                        $data['death_time'] = $deathTime->addMonth(1);
                        break;
                    default:
                        $data['death_time'] = $deathTime->addDays($data['death_time']);
                        break;
                }
            } else { // Create an empty death_time in order to check in validator
                 $data['death_time'] = '';
            }
        }
    }


    public function afterRules(Event $event, \App\Model\Entity\Link $entity, $options, $result, $operation)
    {
        $dummyEmptyDate = '0000-01-01T00:00:00+00:00';
        if ($entity->death_time && $entity->death_time->toIso8601String() == $dummyEmptyDate) {
            $entity->errors("death_date", "Death date cannot be left empty", true);
            $result = false;
        }
        return $result;
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
