<?php

use app\models\PointsOfSale;
use app\models\ShipperCategories;

?>
<div class="d-flex justify-content-between" style="margin-bottom: 10px;">
    <div class="box-decorated d-flex justify-content-center font-weight-semibold" style="width: 49%">
        Наборы
    </div>
    <div class="box-decorated d-flex justify-content-center font-weight-semibold" style="width: 49%">
        Конфигурации
    </div>
</div>

<div class="d-flex justify-content-between">
    <div class="d-flex flex-row flex-wrap" style="width: 49%; max-height: 69vh; overflow-y: scroll;">
        <?php /** @var ShipperCategories $sets */
        /** @var ShipperCategories $set */
        foreach ($sets as $set): ?>
            <div class="box-decorated padding-md sets">
                <div class="d-flex align-items-center justify-content-between" style="margin-bottom: 20px;">
                    <div class="d-flex font-weight-semibold" style="gap: 20px; font-size: 15px; margin-right: 5px">
                        <div class="d-flex align-items-center" style="gap: 5px;">
                            <?= $set->title ?>
                        </div>
                    </div>
                    <div class="d-flex actions" style="gap: 10px;">
                        <button class="btn btn-default btn-sm edit-set" data-set-id="<?= $set->id ?>"
                                style="height: 31.5px">Редактировать
                        </button>
                    </div>
                </div>
                <div style="margin-bottom: 10px;">
                    <div class="d-flex justify-content-between" style="">
                        <div class="d-flex align-items-center"
                             style="gap: 5px; background-color: #d7d7d7; padding: 0 5px 0 5px; border-radius: 5px">
                            <?= $set->franchise->title; ?>
                        </div>
                        <div class="d-flex align-items-center" style="gap: 5px;">
                            Продуктов в наборе: <?= $set->countPositions ?>
                        </div>
                    </div>
                </div>
                <div style="margin-bottom: 10px;">
                    <div class="d-flex justify-content-between" style="">
                        <div class="d-flex align-items-center" style="gap: 5px;">
                        </div>
                        <div class="d-flex align-items-center" style="gap: 5px;">
                            Используется : <?= $set->countCategoryToPoints ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="d-flex flex-row flex-wrap" style="width: 49%; max-height: 69vh; overflow-y: scroll;">
        <?php /** @var PointsOfSale $points */
        foreach ($points as $point): ?>
            <div class="box-decorated padding-md point">
                <div class="d-flex align-items-center justify-content-between" style="margin-bottom: 10px;">
                    <div class="d-flex font-weight-semibold" style="gap: 20px; font-size: 15px;">
                        <div class="d-flex align-items-center" style="gap: 5px;">
                            <?= $point->title ?>
                        </div>
                    </div>
                    <div class="d-flex actions" style="gap: 10px;">
                        <button class="btn btn-default btn-sm edit-configuration" data-point-id="<?= $point->id ?>"
                                style="height: 31.5px">Редактировать
                        </button>
                    </div>
                </div>
                <div style="margin-bottom: 10px;">
                    <div class="d-flex justify-content-between" style="">
                        <div class="d-flex align-items-center"
                             style="gap: 5px; background-color: #d7d7d7; padding: 0 5px 0 5px; border-radius: 5px; max-width: 50%;">
                            <?= $point->franchise->title; ?>
                        </div>
                        <div class="d-flex align-items-center" style="gap: 5px;">
                            Наборов: <?= $point->countCategories ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>


