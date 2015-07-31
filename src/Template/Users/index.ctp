<aside class="col-lg-3 col-md-5">    
    <article class="user-info panel panel-default">
        <h2 class="panel-heading">My information</h2>
        <div class="panel-body">
            <div class="input-group">
                <span class="input-group-addon glyphicon glyphicon-user"></span>
                <span class="form-control user-info"><?= $user['username'] ?></span>
            </div>
            <div class="input-group">
                <span class="input-group-addon">@</span>
                <span class="form-control user-info"><?= $user['email'] ?></span>
            </div>           
        </div>
        <div class="centered-text">
            <?= $this->Form->postLink('Delete', ['_name' => 'user-delete'], ['confirm' => __("Are you sure you want to delete your account ?"),
                'class' => 'btn btn-danger'])
            ?>            
            <?= $this->Html->link('Edit', ['controller' => 'Users', 'action' => 'edit'],
                                          ['class' => 'btn btn-primary']);?>                
        </div>
    </article>
</aside>
<section class="col-lg-9">
    <aside class="link-stats panel panel-info">
        <h2 class="panel-heading">My created links</h2>
        <div class="panel-body">
            <table>
                <thead>
                    <th>Date</th>
                    <th>Life</th>
                    <th>Views</th>
                    <th></th>
                    <th></th>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        </div>
</section>