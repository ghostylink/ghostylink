<h1>Welcome <?= $user->username ?></h1>
<p>You have received this email because your email address has been used to register to
    <?= $this->Html->link("the ghostylink service", ["controller" => "Links",
                                                                            "action" =>  "index",
                                                                            "_full" => true]); ?> hosted at
    <?=
     $this->Url->build([
        "controller" => "Links",
        "action" => "index",
        "_full"=> true
]);?>. <br/>
You can validate this email address with this
<?= $this->Html->link(
    "link",
    ["controller" => "Users", "action" => "validateEmail",
    $user->email_validation_link,
    '_full' => true]
)
?>. <br/>
If you have not an account for ghostylink you can ignore this email.
</p>