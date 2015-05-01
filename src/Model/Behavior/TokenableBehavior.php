<?php

namespace App\Model\Behavior;

use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Entity;

class TokenableBehavior extends Behavior
{
    protected $_defaultConfig = [
        'title' => 'title',
        'date' => 'created',
        'token' => 'token',
    ];
    
    /**
     * Tokenize an entity on his title and date concatenation
     * @param Entity $entity the entity to generate token on
     * @return void
     */
    public function tokenize(Entity $entity)
    {
        $config = $this->config();
        $title = $entity->get($config['title']);
        $date = $entity->get($config['date']);
        $entity->set($config['token'], md5(uniqid($title . $date, true)));
    }
    
    /**
     * Behaviour before the entity saving
     * @param Event $event the event which is triggered
     * @param Entity $entity the entity which has to be changed
     * @return void
     */
    public function beforeSave(Event $event, Entity $entity)
    {
        if (!($entity->has('title') &&
            $entity->has('created'))) {
            $event->stopPropagation();
            return;
        }
        if ($entity->isNew()) {
            $this->tokenize($entity);
        }
    }
}
