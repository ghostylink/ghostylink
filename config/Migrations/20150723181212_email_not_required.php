<?php

use Phinx\Migration\AbstractMigration;

class EmailNotRequired extends AbstractMigration
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
        $table = $this->table('users');        
        $table->changeColumn('email', 'string', [
            'null' => true            
        ]);        
        $table->update();
    }
}
