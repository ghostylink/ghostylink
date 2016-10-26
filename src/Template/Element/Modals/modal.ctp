<div class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><?= $title; ?></h4>
      </div>
      <div class="modal-body">
        <p class="alert alert-<?= $alertKind ?>"><?= $body; ?></p>
      </div>
      <div class="modal-footer">
        <?php
        foreach ($buttons as $legend => $options) {
            $style = isset($options["cssStyle"])?$options["cssStyle"]:"default";
            echo $this->Html->tag(
                "button",
                $legend,
                array_merge([
                    "type" => "button",
                    "class" => "btn btn-$style"
                ], isset($options["html"])?$options["html"]:[])
            );
        }
        ?>        
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->