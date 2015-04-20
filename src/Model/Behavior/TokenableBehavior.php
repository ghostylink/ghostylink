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
     
    public function tokenize(Entity $entity) {
        $config = $this->config();
        $title = $entity->get($config['title']);
        $date = $entity->get($config['date']);
        $entity->set($config['token'], md5(uniqid($title . $date, true)));
    }
    
    public function beforeSave(Event $event, Entity $entity)
    {
        if (!($entity->has('title') &&
            $entity->has('created'))) {
            $event->stopPropagation();
            return;
        }
        $this->tokenize($entity);
    }
}

