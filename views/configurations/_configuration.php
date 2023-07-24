<?php

use app\components\helpers\Html;
use app\models\PointsOfSale;

?>
<div class="form-group-row">
    <h3><?= /** @var PointsOfSale $point */
        $point->title ?></h3>
</div>
<div class="form-group-row">
    <div class="form-group">
        <label>Добавление набора</label>
        <div class="chosen-fill d-flex">
            <select name="categories[]" class="form-control chosen" multiple data-placeholder="Выберите...">
                <?= Html::options($sets) ?>
            </select>
            <button data-point-id="<?= $point->id ?>" class="form-control add-categories-to-point"
                    style="width: 20%; height: 100%">
                Добавить
            </button>
        </div>
    </div>
</div>
<div style="max-height: 60vh; overflow-y: scroll;">
    <table class="table-decorated table-position">
        <tbody>
        <tr class="row-ghost"></tr>
        <?php foreach ($point->categories as $categories): ?>
            <tr>
                <td class="cell-ghost"></td>
                <td><?= $categories->title ?></td>
                <td>
                    <div class="icon-row">
                        <i class="fa fa-trash icon-button delete-categories" data-categories-id="<?= $categories->id ?>"
                           data-point-id="<?= $point->id ?>"></i>
                    </div>
                </td>
                <td class="cell-ghost"></td>
            </tr>
        <?php endforeach; ?>
        <tr class="row-ghost"></tr>
        </tbody>
    </table>
</div>
