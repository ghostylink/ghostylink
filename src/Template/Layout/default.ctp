<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= $this->Html->meta('favicon.ico','/img/logos/favicon/favicon.ico',['type' => 'icon']);?>
    <title>Ghostylink - <?php echo $this->fetch("title");?></title>
    
    <!-- Bootstrap and others css -->    
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->    
    <?= $this->AssetCompress->css('libs');?>            
    <?= $this->AssetCompress->css(strtolower($this->name) . '-' . $this->view); ?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" 
                  data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <?= $this->Html->image("logos/ghostylink-logo-60x50.png", 
                                     array('class' => 'logo', 
                                           'alt' => 'ghostylink logo'));?>  
          <?= $this->Html->link('Ghostylink', "/", array('class' => 'navbar-brand'));?>
          <h1 class="navbar-text hidden-xs">Keep control on data you share !</h1>
        </div>
        <div class="collapse navbar-collapse">
            <?php
            $username = $this->request->session()->read('Auth.User.username');
            if ($username) {                                
                ?>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" 
                           data-toggle="dropdown" role="button" aria-haspopup="true" 
                           aria-expanded="false"><?= 'Welcome ' . $username ?> 
                           <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">My links</a></li>
                            <li><a href="#">My account</a></li>
                            <li role="separator" class="divider"></li>
                            <li>                                
                                <?= $this->Html->link('Log out', ['controller' => 'Users',
                                                                   'action' => 'logout']
                                                      );?>
                            </li>
                        </ul>
                    </li>
                </ul>
                <?php
            }
            else {
                
                echo $this->element("Users/login"); 
                
            }
            ?>            
        </div><!--/.nav-collapse -->
      </div>
      
    </nav>

    <div id="main-content" class="content-wrapper container">
        <?php echo $this->fetch("content"); ?>
    </div>      

    <!--</div> /.container -->    
    <!-- Common librairies -->
    <?= $this->AssetCompress->script('libs');?>
    
    <!-- Include all scripts of the page -->
    <?= $this->AssetCompress->script(strtolower($this->name) . '-' . $this->view);?>
      
  </body>  
</html>
