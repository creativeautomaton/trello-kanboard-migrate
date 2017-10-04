<div class="page-header">
    <h2><?= t('Trello Migrate Settings') ?></h2>
</div>

<form method="post" action="<?= $this->url->href('ConfigController', 'save', array('plugin' => 'TrelloMigrate')) ?>" autocomplete="off">

    <?= $this->form->csrf() ?>

    <fieldset>
        <legend><?= t('Trello API Key ') ?></legend>
        <?= $this->form->input('text', 'trello-api-key', $values) ?>
    </fieldset>

    <fieldset>
        <legend><?= t('Trello API Token ') ?></legend>
        <?= $this->form->input('text', 'trello-api-token', $values) ?>
    </fieldset>

    <fieldset>
        <legend><?= t('Kanboard API token') ?></legend>
        <?= $this->form->input('text', 'kanboard-api-token', $values  ) ?>
    </fieldset>

    <fieldset>
        <legend><?= t('Kanboard API endpoint') ?></legend>
        <?= $this->form->input('text', 'kanboard-api-endpoint', $values ) ?>
    </fieldset>



    <div class="form-actions">
        <button type="submit" class="btn btn-blue"><?= t('Save Trello Migrate Settings') ?></button>
    </div>
</form>

<div class="page-header">
    <h2><?= t('Add A Trello Board') ?></h2>
</div>

<form method="post"  action="../../kanboard/plugins/TrelloMigrate/Assets/kanboard-import-trello/import.php"  autocomplete="on">

    <?= $this->form->csrf() ?>

        <?= $this->form->input('hidden', 'trello-api-key', $values) ?>
        <?= $this->form->input('hidden', 'trello-api-token', $values) ?>
        <?= $this->form->input('hidden', 'kanboard-api-token', $values  ) ?>
        <?= $this->form->input('hidden', 'kanboard-api-endpoint', $values ) ?>


    <fieldset>
        <legend><?= t('Trello Board ID ') ?></legend>
        <?= $this->form->input('text', 'trello-board-id') ?>
    </fieldset>


    <div class="form-actions">
        <button type="submit" class="btn btn-blue add-board" data-trello="add-board" ><?= t('Add Trello Board') ?></button>
    </div>
</form>

<?php
$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
if(isset($_GET['status'])){
    $status = $_GET['status'];
    if($status == 1){
       echo 'Trello Project Added! <a href="' . $actual_link . '/kanboard/projects"> View Your Board </a>';
    }else if($status == 0){
       echo "<p> We could not create the Board, perhaps it already exists? </p> <p> At this time this beta only imports and does not update the trello boards. Sorry";
    }
}
?>

<!-- <script src="../../kanboard/plugins/TrelloMigrate/Assets/app.js" ></script> -->
