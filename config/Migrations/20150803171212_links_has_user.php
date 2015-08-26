<?php

use Phinx\Migration\AbstractMigration;

class LinksHasUser extends AbstractMigration
{
    /**
     * Up Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     */
    public function up()
    {    
        $table = $this->table('links');
        $table->addColumn('user_id', 'integer', ['null' => true])
              ->addForeignKey('user_id', 'users', 'id', array('delete'=> 'CASCADE',                                                             
                                                              'update'=> 'NO_ACTION'))
              ->save();
    }
}
