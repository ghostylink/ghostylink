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
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['delete', 'edit']);
    }

    /**
     * Index method
     *
     * @return
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
     * @return
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
            if ($link->google_captcha) {
                $this->checkRobot($link);
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

    /**
     * Check (if needed) if link is accessed by a real human
     * @param Entity $link the link to check
     * @return \Cake\Network\Response the view information as ajax
     * @throws UnauthorizedException if captcha checking failed
     */
    public function checkRobot($link)
    {
        $secret = '6LdmCQwTAAAAAPqT9OWI2gHcUOHVrOFoy7WCagFS';
        if (!key_exists('g-recaptcha-response', $this->request->data)) {
            throw new UnauthorizedException();
        }
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

        // FIXME: why we have to use this ?
        $link->token = "";
        $link->private_token = "";
        if ($this->Links->save($link)) {
            $this->addAlertParams($this->request->data, $link->id);
            //Redirect to the link view page
            $this->set('url', $link->token);
            $this->set('private_token', $link->private_token);
            return $this->render('ajax/url', 'ajax');
        } else {
            $this->viewBuilder()->layout('ajax');
            $this->set(compact('link'));
            $this->set('_serialize', ['link']);
            return $this->render('add', 'ajax');
        }
    }

    /**
     * Add if needed the ghostification alert parameters
     * @param Array $data
     * @param int $linkId the link id
     * @return type
     */
    private function addAlertParams($data, $linkId)
    {
        $alert_component = array_key_exists('ghostification_alert', $data) && $data['ghostification_alert'];
        if ($alert_component&& $this->Auth->user("id")) {
            $data['AlertParameters']['link_id'] = $linkId;
            $parameters = $this->Links->AlertParameters->newEntity($data);
            if (!$this->Links->AlertParameters->save($parameters)) {
                $this->Flash->error('Impossible to store alert component.');
                return $this->redirect(['action' => 'index']);
            }
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
        $link = $this->Links->get($id);
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
        $this->request->allowMethod(['get', 'post', 'delete']);
        $link = $this->Links->findByPrivateToken($id)->first();

        // Allow deletetion for everyone if the link is anonymous,
        // Force to be link's owner for non anonymous link
        if (!$link || $link->user_id !== $this->Auth->user('id')) {
            throw new UnauthorizedException();
        }

        if ($this->Links->delete($link)) {
            $this->Flash->success('The link \'' . $link->title . '\' has been deleted.');
        } else {
            $this->Flash->error('The link could not be deleted. Please, try again.');
        }
        if ($this->Auth->user('id')) {
            return $this->redirect(['action' => 'history']);
        }
        return $this->redirect(['action' => 'index']);
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
     * @return Redirection previous page redirection.
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
            'sortWhitelist' => [
               'title', 'created'
            ],
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
        $this->set('user', $this->Auth->user());
        $this->set('_serialize', ['bookmarks']);
    }

    /**
     * Subscribe or unsubscribe to alert for the given link
     * @param type $privateToken the private token to subscribe or unsubscribe to
     */
    public function alertSubscribe($privateToken = null)
    {
        $this->request->allowMethod(['post']);
        $link = $this->Links->findByPrivateToken($privateToken)->contain('AlertParameters')->first();
        if (!$link) {
            throw new NotFoundException();
        }
        if ($link->user_id != $this->Auth->user("id")) {
            return $this->redirect(["controller" => 'Users', "action" => "login"]);
        }
        $targetStatus = $this->request->data("subscribe-notifications");
        $data = [];
        $data["alert_parameter"] = [];
        $data["alert_parameter"]["subscribe_notifications"] = $targetStatus == 'on' ? true : false;
        $link = $this->Links->patchEntity($link, $data);
        if (!$this->Links->save($link)) {
            $this->Flash->error("Alert parameters cannot be saved. Please try again");
        }
        return $this->redirect(["controller" => "Links", "action" => "history"]);
    }
}
