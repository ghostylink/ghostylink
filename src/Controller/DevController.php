<?php

namespace App\Controller;

use Cake\Event\Event;
/**
 * Development Controller
 *
 * @property \App\Model\Table\LinksTable $Links
 */
class DevController extends AppController
{

    /**
     * BeforeFilter method.
     *
     * Specify actions authorized before authentification for Dev controller.
     *
     * @param \App\Controller\Event $event event the filter is associated to
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['checkConfig']);
    }

    
    /**
     * Index method
     *
     * @return
     */
    public function checkConfig()
    {
        return $this->render('check-config');        
    }
}