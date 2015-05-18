<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Exception\NotFoundException;

/**
 * Links Controller
 *
 * @property \App\Model\Table\LinksTable $Links
 */
class LinksController extends AppController
{
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
        if (count($link) == 0) {
            throw new NotFoundException();
        }
        // The link has not been deleted
        if ($this->Links->increaseViews($link)) {
            $this->Links->save($link);
            $this->set('link', $link);
            $this->set('_serialize', ['link']);
        } else { // The link has been deleted
            throw new NotFoundException();
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
        $link = $this->Links->patchEntity($link, $this->request->data);
        // Initialize empty token to pass the validation
        $link->token = "";
        if ($this->Links->save($link)) {
            $this->Flash->success('The link has been saved.');
            //Redirect to the link view page
            $this->set('url', $link->token);
            return $this->render('ajax/url', 'ajax');
        } else {
            $this->layout = 'ajax';
            $this->Flash->error('The link could not be saved. Please, try again.');
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

        if ($this->request->is(['patch', 'post', 'put'])) {
            $link = $this->Links->patchEntity($link, $this->request->data);
            if ($this->Links->save($link)) {
                $this->Flash->success('The link has been saved.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('The link could not be saved. Please, try again.');
            }
        }
        $this->set(compact('link'));
        $this->set('_serialize', ['link']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Link id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $link = $this->Links->get($id);
        if ($this->Links->delete($link)) {
            $this->Flash->success('The link has been deleted.');
        } else {
            $this->Flash->error('The link could not be deleted. Please, try again.');
        }
        return $this->redirect(['action' => 'index']);
    }
}
