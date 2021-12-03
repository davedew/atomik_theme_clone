<?php
defined('C5_EXECUTE') or die(_("Access Denied."));

use Concrete\Core\Support\Facade\Application;
$app = Application::getFacadeApplication();
$form = $app->make('helper/form');
$u = $app->make(Concrete\Core\User\User::class);
?>

<div class="alert alert-danger">
    <?php echo t('<strong>Attention!</strong> Clearing your site\'s content prior to installing this theme is highly recommended.')?>
</div>

<h4><?=t('Clear this Site?')?></h4>
<p><?=t('Atomik Clone can fully clear your website of all existing content and install its own custom content in its place. If you\'re installing a theme for the first time you may want to do this. Clear all site content?') ?></p>
<?php

if ($u->isSuperUser()) {
    $disabled = [];
    ?>
    <div class="alert-message warning"><p><?=t('This will clear your home page, uploaded files and any content pages out of your site completely. It will completely reset your site and any content you have added will be lost.')?></p></div>
    <?php
} else {
    $disabled = ['disabled'=>true];
    ?>
    <div class="alert-message info"><p><?=t('Only the %s user may reset the site\'s content.', USER_SUPER)?></p></div>
    <?php
}
?>
<div class="form-group">
    <label class="control-label form-label"><?=t("Swap Site Contents")?></label>
    <div class="form-check">
        <?=$form->radio('pkgDoFullContentSwap',0, true, $disabled)?>
        <?=$form->label('pkgDoFullContentSwap1',t('No. Do <strong>not</strong> remove any content or files from this website.'))?>
    </div>
    <div class="form-check">
        <?=$form->radio('pkgDoFullContentSwap',1, false, $disabled)?>
        <?=$form->label('pkgDoFullContentSwap2',t('Yes. Reset site content with the content found in this package'))?>
    </div>

</div>