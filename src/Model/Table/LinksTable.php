<?php

/**
 * Links table file
 */
namespace App\Model\Table;

use App\Model\Entity\Link;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Links Model
 */
class LinksTable extends Table
{

    // MySQL specific code here. TODO: see if it can be build with cakePHP 3 expressions
    private $MYSQL_LIFE_EXP = 'GREATEST(IFNULL(LEAST(100, views * 100.0 / max_views),0),
                                                IFNULL(LEAST(100,(datediff(CURRENT_TIMESTAMP, created) * 100.0 )
                            / datediff(death_time, created)),0)) ';
    /**
     * Initialize method
     *
     *  @param array $config The configuration for the Table.
     *  @return void
     */
    public function initialize(array $config)
    {
        $this->table('links');
        $this->hasOne('AlertParameters');
        $this->displayField('title');
        $this->displayField('max_views');
        $this->displayField('alert_parameter');
        $this->displayField('views');
        $this->displayField('death_time');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        $this->addBehavior('Tokenable');
        $this->addBehavior('Ghostable');
        // Example for association with User in the future
        $this->hasOne('Users', [
            'foreignKey' => 'user_id'
        ]);
        $this->displayField('user_id');
        $this->displayField('google_captcha');
    }

    /**
     * A specific validator for logged in user
     * @param Validator $validator
     * @return Validator
     */
    public function validationLogged(Validator $validator)
    {
        $val = $this->buildCommonValidator($validator);
        $validator->allowEmpty('max_views');
        $validator->allowEmpty('death_time');
        return $val;
    }

    /**
     *  Default validation rules.
     *
     *  @param \Cake\Validation\Validator $validator Validator instance.
     *  @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator = $this->buildCommonValidator($validator);
        
        return $validator;
    }

    private function buildCommonValidator(Validator $validator)
    {
        $validator
                ->add('id', 'valid', ['rule' => 'numeric'])
                ->allowEmpty('id', 'create')
                ->requirePresence('title', 'create')
                ->notEmpty('title')
                ->add('title', ['length' => [
                        'rule' => ['maxLength', 100],
                        'message' => 'Titles need to be at least 10 characters long',
                ]])
                ->requirePresence('content', 'create')
                ->notEmpty('content')
                ->requirePresence('token', 'create')
                ->requirePresence('private_token', 'create')
                ->notEmpty('token')
                ->notEmpty('private_token')
                ->add('max_views', 'valid', ['rule' => 'numeric'])
                ->add('max_views', 'valid', ['rule' => ['range', 1, 1000]]);
        return $validator;
    }

    /**
     * increase the life of the link
     *
     * @param Link $entity the Link entity to increase the view on
     * @return boolean False if the link has been deleted
     */
    public function increaseLife(Link $entity)
    {
        $ghost = $this->behaviors()->get('Ghostable');
        if (!$ghost->increaseLife($entity)) {
            $this->delete($entity);
            $this->save($entity);
            return false;
        }
        $this->save($entity);
        return true;
    }

    /**
     * check the life of the link
     *
     * @param Link $entity the Link entity to increase the view on
     * @return boolean False if the link is dead
     */
    public function checkLife(Link $entity)
    {
        return $this->behaviors()->get('Ghostable')->checkLife($entity);
    }

    /**
     * change the link's status to disabled
     *
     * @param Link $entity the Link entity to change status
     * @return boolean False if the link does not exist
     */
    public function disable(Link $entity)
    {
        if ($entity->get('status') != 1) {
            return false;
        }
        $entity->set('status', 0);
        $this->save($entity);
        return true;
    }

    /**
     * change the link's status to enabled
     *
     * @param Link $entity the Link entity to change status
     * @return boolean False if the link does not exist
     */
    public function enable(Link $entity)
    {
        if ($entity->get('status') != 0) {
            return false;
        }
        $entity->set('status', 1);
        $this->save($entity);
        return true;
    }

    /**
     * check if the link is disabled or not
     *
     * @param Link $entity the Link entity to change status
     * @return boolean False if the link is disabled
     */
    public function isEnabled(Link $entity)
    {
        return $entity->get('status');
    }

    /**
     *  Custom finder to retrieve all link which have their life between two specified values.
     * @param Query $query
     * @param array $options required key : min_life, the minimal life of links to retrieve
     *                                                                 max_life the maximal life of links to retrieve
     */
    public function findRangeLife(Query $query, array $options)
    {
        if (!isset($options['min_life']) || !isset($options['max_life'])) {
            throw new \BadFunctionCallException();
        }

        $query->param['min_life'] = $options['min_life'];
        $query->param['max_life'] = $options['max_life'];

        return $query->find('all')->where(function ($exp, $q) {
                    $filter = $this->MYSQL_LIFE_EXP;
                    return $exp->between($filter, $q->param['min_life'], $q->param['max_life']);
        });
    }

    /**
     *  Find all link in the history matching filters
     * @param Query $query
     * @param array $options required key : min_life, the minimal life of links to retrieve
     *                                                                 max_life the maximal life of links to retrieve
     *                                                                 user_id the id of the user the link belongs to
     *                                                                 status [1|0] keep only links with given status
     *                                                                 title a pattern corresponding to the title
     * @return Query the built query
     * @throws \BadFunctionCallException
     */
    public function findHistory(Query $query, array $options)
    {
        if (!isset($options['user_id'])) {
            throw new \BadFunctionCallException();
        }
        $query = $this->findRangeLife($query, $options)
                                ->contain("AlertParameters")
                                ->where(['Links.user_id' => $options['user_id']]);

        //Filter on status
        if (isset($options['status']) && $options['status'] != '*') {
            $query->andWhere(['Links.status' => $options['status']]);
        }

        //Filter on title
        if (isset($options['title'])) {
            $query->param['title'] = $options['title'];
            $query->andWhere(function ($exp, $q) {
                return $exp->like('Links.title', '%' . $q->param['title'] . '%');
            });
        }
        return $query;
    }

    /**
     *  Find all links needing a mail alert
     * @param Query $query
     * @param array $options
     */
    public function findNeedMailAlert(Query $query, array $options)
    {
        $query->find('all')
                   ->matching('AlertParameters', function ($q) {
                                       $filter = $this->MYSQL_LIFE_EXP;
                                       $expr = $q->newExpr("$filter >= AlertParameters.life_threshold");
                                       return $q->where(
                                           ['sending_status' => 0,
                                            'type' => 'email',
                                            'AlertParameters.subscribe_notifications' => 1,
                                            $expr]
                                       );
                   });
        return $query;
    }
}
