<?php
use Migrations\AbstractMigration;

class AddEmailValidatedToUser extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('users');
        $table->addColumn('email_validated', 'boolean', [
            'default' => false,
            'null' => false
        ]);
        $table->addColumn('email_validation_link', 'string', [
            'default' => null,
            'null' => true
        ]);
        $table->update();
        // By default existing email are validated
        $this->execute("update users set email_validated = TRUE");
        $table->update();
    }
}
