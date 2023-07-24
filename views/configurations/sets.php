<?php

use app\components\helpers\Html;
use yii\web\View;

$page = Yii::$app->request->get("page", 1);

$this->registerJSFile("/js/view/shipper/configurations/sets.js", ['position' => View::POS_END, 'type' => 'module']);
$this->registerCss("
    .sets {
        margin-bottom: 10px;
        width: 49%;
    }
    
    .sets:nth-child(2n) {
        margin-left: auto;
    }
    
    .point {
        margin-bottom: 10px;
        width: 49%;
    }
    
    .point:nth-child(2n) {
        margin-left: auto;
    }
    
    .roles-block__hidden {
        display: none; 
    }
    
    .roles-block__visible {
        display: block;
    }
    
    .right-modal {
        width: 0;
        height: 100vh;
        position: absolute;
        top: 0;
        right: 2px;
        transition: width 1s cubic-bezier(0.17, 0.82, 0.36, 1) 1ms;
    }
    
    .modal-show {
        width: 45%;
        padding: 90px 20px 0 20px;
    }
    
    .left-modal {
        width: 0;
        height: 100vh;
        position: absolute;
        top: 0;
        left: 2px;
        transition: width 1s cubic-bezier(0.17, 0.82, 0.36, 1) 1ms;
        z-index: 100;
    }
    
    .modal-show {
        width: 45%;
        padding: 90px 20px 0 20px;
    }

");
?>

<div id="main-container">
    <div style="padding: 0 20px 20px 20px;">
        <div class="page-title d-flex justify-content-between align-items-center" style="margin: 10px 0;">
            <h3 class="no-margin">Настройки конфигураций расходки</h3>
        </div>
        <div>
            <div class="box-decorated padding-md" style="margin-bottom: 10px;">
                <div class="form-group-row">
                    <div class="form-group">
                        <label>Конфигурации</label>
                        <div class="d-flex">
                            <div style="width: 300px; margin-right: 20px;" class="chosen-fill">
                                <select name="conf_id[]" class="form-control chosen" multiple
                                        data-placeholder="Выберите...">
                                    <?= Html::options($sets) ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Точки</label>
                        <div class="d-flex">
                            <div style="width: 300px; margin-right: 20px;" class="chosen-fill">
                                <select name="point_id[]" class="form-control chosen" multiple
                                        data-placeholder="Выберите...">
                                    <?= Html::options($points) ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;"></div>
                    <div class="form-group" style="margin-bottom: 0;"></div>
                    <div class="form-group" style="margin-bottom: 0;"></div>
                    <div class="form-group d-flex align-items-end">
                        <button class="btn btn-default" id="apply-filter">Поиск</button>
                        <button class="btn btn-default" id="clear-filter">Сбросить</button>
                        <button class="btn btn-info" id="open-add-set-modal">Добавить</button>
                    </div>
                </div>
            </div>

        </div>
        <div id="content"></div>
    </div>
</div>
<div class="right-modal box-decorated" id="right-modal">
    <div id="content-set">
    </div>
</div>
<div class="left-modal box-decorated" id="left-modal">
    <div id="content-configuration">
    </div>
</div>
<div class="modal fade" id="add-set-modal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form class="modal-form">
                <div class="modal-body">
                    <div style="margin-bottom: 20px;">
                        <button type="button" class="close modal-close">×</button>
                        <h4 class="no-margin">Добавление набора</h4>
                        <span class="modal-subtitle text-muted"></span>
                    </div>
                    <div class="form-group-row">
                        <div class="form-group">
                            <label>Наименование</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="form-group chosen-fill">
                            <label>Франшизы</label>
                            <select name="franchise_id" class="form-control chosen">
                                <?= Html::options($fids) ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group-row">
                        <div class="form-group chosen-fill">
                            <label>Группы ролей</label>
                            <select name="groupRole[]" class="form-control chosen" multiple data-placeholder="Выберите...">
                                <?= Html::options($groups) ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group-row">
                        <div class="form-group chosen-fill">
                            <label>Продукты</label>
                            <select name="sets[]" class="form-control chosen" multiple data-placeholder="Выберите...">
                                <?= Html::options($positions) ?>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end" style="margin-top: 20px;">
                        <button class="btn btn-success btn-sm">Сохранить</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>