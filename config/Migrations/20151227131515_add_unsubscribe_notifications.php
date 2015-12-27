<?php
use Migrations\AbstractMigration;

class AddUnsubscribeNotifications extends AbstractMigration
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
        $userTable = $this->table("users");
        $alertParametersTable = $this->table("alert_parameters");

        $userTable->addColumn("subscribe_notifications", 'boolean', [
            'default' => true,
            'null' => false
        ]);
        $alertParametersTable->addColumn("subscribe_notifications", 'boolean', [
            'default' => true,
            'null' => false
        ]);
        
        $userTable->update();
        $alertParametersTable->update();
    }
}
