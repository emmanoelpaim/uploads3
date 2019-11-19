<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
use app\models\Api;

$api = new Api();
$listFiles = $api->listFiles();
$archives = array();

if(isset($listFiles["Contents"])){
    foreach ($listFiles["Contents"] as $key => $file){
        $archives["imagens"][$file["Key"]]["name"]= $file["Key"];
    }

    if(isset($archives["imagens"])) {
        foreach ($archives["imagens"] as $key => $imagem) {
            $archives["imagens"][$key]["url"] = $api->validateFile($key);
        }
    }
}

use yii\widgets\ActiveForm;

?>
<?php if($success): ?>
<div class="row">
    <div class="alert alert-success">
        <h3><?= $success ?></h3>
    </div>
</div>
<?php endif; ?>

<div class="row">
    <div class="col-md-12">
        <div class="center-block" >
            <?php $form = ActiveForm::begin(['options' => ['method'=>'post','enctype' => 'multipart/form-data']]);?>
                <h1>Adicionar imagem?</h1>
                <?= $form->field($model, 'file')->fileInput(['class'=>'btn btn-success'])->label('') ?>
                <input type="submit" class="btn btn-primary" style="margin-top: 30px;" value="Enviar"/>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <?php if(isset($archives["imagens"])): ?>
        <?php foreach ($archives["imagens"] as $imagem): ?>
            <?php $form = ActiveForm::begin(['options' => ['name'=>'delete','method'=>'delete']]);?>
            <div class="col-md-3 text-center" style="margin-top:30px">
                <div style="border: 1px solid #1b6d85; border-radius: 16px; padding: 10px;margin">
                    <img src="<?= $imagem["url"] ?>" class="img-responsive" style="display: inline;max-height: 200px;max-width: 200px">
                    <h5 class="card-title"><?= $imagem["name"]; ?></h5>
                    <?= $form->field($model, 'name')->hiddenInput(['value'=>$imagem["name"]]); ?>
                    <input type="submit" class="btn btn-danger" value="Excluir"/>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        <?php endforeach; ?>
    <?php endif; ?>

</div>