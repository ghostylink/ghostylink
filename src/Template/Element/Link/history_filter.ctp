<section id="filters" class="col-lg-12 panel panel-default">
    <?= $this->Form->create(null, ['type' => 'get', 'action' => '', 'class' => 'panel-body']); ?>
    <div class="col-lg-4">
        <p>
            <label for="amount">Life range:</label>
            <span>
                <?php
                    $this->Form->templates(['inputContainer' => '{{content}}']);
                    $value = isset($_GET['min_life']) ? $_GET['min_life'] : 25;
                    echo $this->Form->input('min_life', ['id' => 'min_life', 'readonly' => 'readonly',
                                                                        'label' => false, 'value' => $value]);
                ?>%
            </span>-
            <span>
                <?php
                    $value = isset($_GET['max_life']) ? $_GET['max_life'] : 75;
                    echo $this->Form->input('max_life', ['id' => 'max_life', 'readonly' => 'readonly',
                                                                        'label' => false, 'value' => $value]);
                ?>%
            </span>
        </p>
        <div id="slider-range" class="col-lg-12 "></div>
    </div>
    <div id="radio" class="col-lg-4">
        <label>Status :</label><br/>
        <?php
            $options = [
                                    ['value' => '*', 'text' => 'Any', 'id' => 'status-any'],
                                    ['value' => '1', 'text' => ' Enable', 'id' => 'status-enabled'],
                                    ['value' => '0', 'text' => ' Disable', 'id' => 'status-disabled'],
                               ];
            if (isset($_GET['status'])) {
                  for ($i = 0; $i < count($options); ++$i) {
                      if ($options[$i]['value'] == $_GET['status']) {
                          $options[$i]['checked'] = 'checked';
                      }
                  }
            }
            else {
                $options[0]['checked'] = 'checked';
            }
            echo $this->Form->radio('status', $options,['hiddenField'=> false]);
        ?>
    </div>
    <div class="col-lg-2">
        <?php
                $value = isset($_GET['title'])?$_GET['title']:'';
                echo $this->Form->input('title', ['class' => 'form-control', 'value' => $value, 'placeholder' => 'Title like']);
        ?>
    </div><!-- /input-group -->
    <div id="div-buttons" class="col-lg-2">
        <button class="btn-default btn" >Apply filters</button>
        <button id="almost-ghostified" class="btn-warning btn" >Almost ghostified</button>
    </div>
    <?= $this->Form->end() ?>
</section>