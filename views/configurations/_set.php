<?php

use app\components\helpers\Html;
use app\models\Franchises;
use app\models\ShipperPositions;
use app\models\ShipperRoleGroup;

?>
<div class="form-group-row">
    <div class="form-group">
        <label>Наименование</label>
        <div class="d-flex">
            <input type="text" name="title" class="form-control input-sm" placeholder="Например: Xerox xxxx"
                   value="<?= $set->title ?>">
        </div>
    </div>
    <div class="form-group">
        <label>Франшиза</label>
        <div class="chosen-fill">
            <select name="franchise" class="form-control chosen"
                    data-placeholder="Выберите...">
                <?= /** @var Franchises $franchises */
                Html::options($franchises, $set->franchise->id) ?>
            </select>
        </div>
    </div>
</div>
<div class="form-group-row">
    <div class="form-group">
        <label>Доступно для групп</label>
        <div class="chosen-fill">
            <select name="role_group[]" class="form-control chosen" multiple data-placeholder="Выберите...">
                <?= /** @var ShipperRoleGroup $groups */
                /** @var array $groupsSet */
                Html::options($groups, $groupsSet) ?>
            </select>
        </div>
    </div>
</div>
<div class="form-group-row">
    <div class="form-group">
        <label>Добавление продукта</label>
        <div class="chosen-fill d-flex">
            <select name="positions[]" class="form-control chosen" multiple data-placeholder="Выберите...">
                <?= /** @var ShipperPositions $positions */
                Html::options($positions) ?>
            </select>
            <button data-set-id="<?= $set->id ?>" class="form-control add-position" style="width: 20%; height: 100%">
                Добавить
            </button>
        </div>
    </div>
</div>
<div class="form-group-row">
    <div class="form-group">
        <label>Добавление точки</label>
        <div class="chosen-fill d-flex">
            <select name="points[]" class="form-control chosen" multiple data-placeholder="Выберите...">
                <?= /** @var \app\models\PointsOfSale $points */
                Html::options($points) ?>
            </select>
            <button data-set-id="<?= $set->id ?>" class="form-control add-point" style="width: 20%; height: 100%">
                Добавить
            </button>
        </div>
    </div>
</div>
<div class="d-flex justify-content-between">
    <div style="max-height: 50vh; overflow-y: scroll; width: 75%">
        <table class="table-decorated table-position">
            <tbody>
            <tr class="row-ghost"></tr>
            <?php foreach ($set->positions as $position): ?>
                <tr>
                    <td class="cell-ghost"></td>
                    <td>#<?= $position->id; ?></td>
                    <td><?= $position->product->id ?? ''; ?></td>
                    <td><?= $position->title; ?></td>
                    <td>
                        <div class="icon-row">
                            <i class="fa fa-trash icon-button delete-position" data-position-id="<?= $position->id ?>"
                               data-set-id="<?= $set->id ?>"></i>
                        </div>
                    </td>
                    <td class="cell-ghost"></td>
                </tr>
            <?php endforeach; ?>
            <tr class="row-ghost"></tr>
            </tbody>
        </table>
    </div>
    <div style="max-height: 50vh; overflow-y: scroll; width: 23%">
        <table class="table-decorated table-position">
            <tbody>
            <tr class="row-ghost"></tr>
            <?php /** @var \app\models\PointsOfSale $point */
            foreach ($set->points as $point): ?>
                <tr>
                    <td class="cell-ghost"></td>
                    <td><?= $point->title; ?></td>
                    <td>
                        <div class="icon-row">
                            <i class="fa fa-trash icon-button delete-point" data-point-id="<?= $point->id ?>"
                               data-set-id="<?= $set->id ?>"></i>
                        </div>
                    </td>
                    <td class="cell-ghost"></td>
                </tr>
            <?php endforeach; ?>
            <tr class="row-ghost"></tr>
            </tbody>
        </table>

    </div>
</div>


