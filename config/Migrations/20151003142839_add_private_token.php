<?php
use Migrations\AbstractMigration;

class AddPrivateToken extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function up()
    {
        $table = $this->table('links');
        $table->addColumn('private_token', 'string', [
            'default' => '',
            'null' => false,
            'limit' => 255,
        ]);
        $table->update();
        // FIXME : uggly update procedure. MD5 is deterministic. need a random salt
        $this->execute("update links set private_token = md5(UUID()) where private_token = ''");
        $table->addIndex('private_token', array('unique' => true));
        $table->update();
    }

    public function down()
    {
        $table = $this->table('links');
        $table->removeColumn('private_token');
    }
}
