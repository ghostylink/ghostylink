<?php

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

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('links');
        $this->displayField('title');
        $this->displayField('max_views');
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
        $val = $this->_buildCommonValidator($validator);
        $validator->allowEmpty('max_views');
        $validator->allowEmpty('death_time');
        return $val;
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator = $this->_buildCommonValidator($validator);
        $validator->notEmpty('death_time', 'At least one component is required', function ($context) {
            if (!$context['newRecord']) {
                return false;
            }
            if (array_key_exists('max_views', $context['data'])) {
                return !isset($context['data']['max_views']) || $context['data']['max_views'] == '';
            } else {
                return false;
            }
        });
        $validator->notEmpty('max_views', 'At least one component is required', function ($context) {
            if (!$context['newRecord']) {
                return false;
            }
            if (array_key_exists('death_time', $context['data'])) {
                return !isset($context['data']['death_time']) || $context['data']['death_time'] == '';
            } else {
                return false;
            }
        });
        return $validator;
    }

    function _buildCommonValidator(Validator $validator)
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
                ->notEmpty('token')
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
    function findRangeLife(Query $query, array $options)
    {
        if (!isset($options['min_life']) || !isset($options['max_life'])) {
            throw new \BadFunctionCallException();
        }

        $query->param['min_life'] = $options['min_life'];
        $query->param['max_life'] = $options['max_life'];

        return $query->find('all')->where(function ($exp, $q) {
                    $filter = 'GREATEST(IFNULL(LEAST(100, Links.views * 100.0 / Links.max_views),0),
                                                IFNULL(LEAST(100,(datediff(CURRENT_TIMESTAMP, Links.created) * 100.0 ) ' .
                            '/ datediff(Links.death_time, Links.created)),0)) ';
                    return $exp->between($filter, $q->param['min_life'], $q->param['max_life']);
                });
    }

    /**
     *  Find all link in the history matching filters
     * @param Query $query
     * @param array $options required key : min_life, the minimal life of links to retrieve
     *                                                                 max_life the maximal life of links to retrieve
     *                                                                 user_id the id of the user the link belongs to
     *                                                                 status [1|0] keep only links with the corresponding status
     *                                                                 title a pattern corresponding to the title
     * @return Query the built query
     * @throws \BadFunctionCallException
     */
    function findHistory(Query $query, array $options)
    {
        if (!isset($options['user_id'])) {
            throw new \BadFunctionCallException();
        }
        $query = $this->findRangeLife($query, $options)->where(['Links.user_id' => $options['user_id']]);

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
}
