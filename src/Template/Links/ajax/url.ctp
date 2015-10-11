<section class="generated-link col-lg-12">
    <div class="link-wrapper container">
        <span class="col-lg-3">
        Use this url to view your content :
        </span>
    <div id="link-url" class="alert alert-success link-url" title="Share this one !">
        <?= $this->Url->build('/', true) . $url ?></div>
        <button class="glyphicon glyphicon-copy link-copy" value="Select"> Select</button><br>
    </div>
    <div class="link-wrapper container">
        <span class="col-lg-3">
            Use this url to delete your content :
        </span>
        <div class="alert alert-warning link-url" title="Keep this secret !">
             <?= $this->Url->build(["controller" => "Links",
                                                    "action" => "delete",
                                                   $private_token], true) ?></div>
     </div>
</section>
