<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\UnauthorizedException;

/**
 * Links Controller
 *
 * @property \App\Model\Table\LinksTable $Links
 */
class LinksController extends AppController
{

    /**
     * BeforeFilter method.
     *
     * Specify actions authorized before authentification for Links controller.
     *
     * @param \App\Controller\Event $event event the filter is associated to
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['edit']);
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('links', $this->paginate($this->Links));
        $this->set('_serialize', ['links']);
    }

    /**
     * View method
     *
     * @param string|null $token Link token.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($token = null)
    {
        $link = $this->Links->findByToken($token)->first();
        if (count($link) == 0 || !$this->Links->isEnabled($link)) {
            throw new NotFoundException();
        }
        $this->set('user_id', $this->Auth->user('id'));
        if ($this->request->is('ajax')) {
            //Check the link has not been seen by an other people
            if (!$this->Links->increaseLife($link)) {
                  throw new NotFoundException();
            }
            if (key_exists('g-recaptcha-response', $this->request->data)) {
                  return $this->_check_robot($link);
            }

            $this->set('link', $link);
            return $this->render('ajax/information', 'ajax');
        } else {
            if ($link->max_views == null) {
                $this->Links->increaseLife($link);
            }
            if (!$this->Links->checkLife($link)) {
                $this->Links->increaseLife($link);
                throw new NotFoundException();
            }
        }
        $this->set('link', $link);
    }

    public function _check_robot($link) {
        $secret = '6LdmCQwTAAAAAPqT9OWI2gHcUOHVrOFoy7WCagFS';
        if ($link->google_captcha) {
            $recaptcha = new \ReCaptcha\ReCaptcha($secret);
            $resp = $recaptcha->verify($this->request->data['g-recaptcha-response'], $this->request->clientIp());
            if ($resp->isSuccess()) {
                $this->set('link', $link);
                return $this->render('ajax/information', 'ajax');
            } else {
                $errors = $resp->getErrorCodes();
                throw new UnauthorizedException();
            }
        }
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $link = $this->Links->newEntity();
        $this->request->allowMethod(['post', 'ajax']);

        $this->request->data['user_id'] = $this->Auth->user('id');

        //A specific check for logged in user
        if ($this->Auth->user('id')) {
            $link = $this->Links->patchEntity($link, $this->request->data, ['validate' => 'logged']);
        } else {
            $link = $this->Links->patchEntity($link, $this->request->data);
        }

        // Initialize empty token to pass the validation
        $link->token = "";
        if ($this->Links->save($link)) {
            //Redirect to the link view page
            $this->set('url', $link->token);
            return $this->render('ajax/url', 'ajax');
        } else {
            $this->layout = 'ajax';
            $this->set(compact('link'));
            $this->set('_serialize', ['link']);
            return $this->render('add', 'ajax');
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Link id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $link = $this->Links->get($id, [
            'contain' => []
        ]);

        if ($link->user_id === null || $link->user_id !== $this->Auth->user('id')) {
            throw new UnauthorizedException();
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $link = $this->Links->patchEntity($link, $this->request->data);
            if ($this->Links->save($link)) {
                $this->Flash->success('The link has been saved.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('The link could not be saved. Please, try again.');
                return $this->redirect(['action' => 'index']);
            }
        }
        $this->set(compact('link'));
        $this->set('_serialize', ['link']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Link id.
     * @return void Redirects to history.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $link = $this->Links->get($id);

        if ($link->user_id === null || $link->user_id !== $this->Auth->user('id')) {
            throw new UnauthorizedException();
        }
        if ($this->Links->delete($link)) {
            $this->Flash->success('The link has been deleted.');
        } else {
            $this->Flash->error('The link could not be deleted. Please, try again.');
        }
        return $this->redirect(['action' => 'history']);
    }

    /**
     * Disable method
     *
     * @param string|null $id Link id.
     * @return void Redirects to history.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function disable($id = null)
    {
        $this->request->allowMethod(['post']);
        $link = $this->Links->get($id);

        if ($link->user_id === null || $link->user_id !== $this->Auth->user('id')) {
            throw new UnauthorizedException();
        }

        if ($this->Links->disable($link)) {
            $this->Flash->success('The link has been disabled.');
        } else {
            $this->Flash->error('The link could not be disabled. Please, try again.');
        }
        return $this->redirect($this->referer());
    }

    /**
     * Enable method
     *
     * @param string|null $id Link id.
     * @return void Redirects to history.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function enable($id = null)
    {
        $this->request->allowMethod(['post']);
        $link = $this->Links->get($id);

        if ($link->user_id === null || $link->user_id !== $this->Auth->user('id')) {
            throw new UnauthorizedException();
        }

        if ($this->Links->enable($link)) {
            $this->Flash->success('The link has been enabled.');
        } else {
            $this->Flash->error('The link could not be enabled. Please, try again.');
        }
        return $this->redirect($this->referer());
    }

    /**
     * History method
     *
     * @return Renders list of links.
     */
    public function history()
    {
        $min_life = !isset($this->request->query['min_life']) ? 0 : $this->request->query['min_life'];
        $max_life = !isset($this->request->query['max_life']) ? 100 : $this->request->query['max_life'];
        $status = !isset($this->request->query['status']) ? null : $this->request->query['status'];
        $title = !isset($this->request->query['title']) ? null : $this->request->query['title'];

        // Using a query
        $this->paginate = [
            'maxLimit' => 15,
            'limit' => 5,
            'finder' => [
                'history' => ['min_life' => $min_life,
                                     'max_life' => $max_life,
                                     'status' => $status,
                                     'title' => $title,
                                     'user_id' => $this->Auth->user('id')]
            ],
            'conditions' => [
                'Links.user_id' => $this->Auth->user('id'),
            ]
        ];
        $this->set('history', $this->paginate($this->Links));
        $this->set('_serialize', ['bookmarks']);
    }
}
