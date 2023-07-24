<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "shipper_categories".
 *
 * @property int $id
 * @property int $franchise_id
 * @property string $title
 *
 * @property Franchises $franchise
 * @property ShipperCategoryToPoint[] $shipperCategoryToPoints
 * @property PointsOfSale[] $points
 * @property ShipperCategoryToPosition[] $shipperCategoryToPositions
 * @property ShipperPositions[] $positions
 */
class ShipperCategories extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shipper_categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['franchise_id'], 'integer'],
            [['title'], 'required'],
            [['title'], 'string', 'max' => 255],
            [['franchise_id'], 'exist', 'skipOnError' => true, 'targetClass' => Franchises::class, 'targetAttribute' => ['franchise_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'franchise_id' => 'Franchise Id',
            'title' => 'Title',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFranchise()
    {
        return $this->hasOne(Franchises::class, ['id' => 'franchise_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShipperCategoryToPoints()
    {
        return $this->hasMany(ShipperCategoryToPoint::class, ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryToPoints()
    {
        return $this->hasMany(ShipperCategoryToPoint::class, ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPoints()
    {
        return $this->hasMany(PointsOfSale::class, ['id' => 'point_id'])->viaTable('shipper_category_to_point', ['category_id' => 'id'])->orderBy('points_of_sale.title');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShipperCategoryToPositions()
    {
        return $this->hasMany(ShipperCategoryToPosition::class, ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPositions()
    {
        return $this->hasMany(ShipperPositions::class, ['id' => 'position_id'])->viaTable('shipper_category_to_position', ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPositionsAndSuppliers()
    {
        return $this->hasMany(ShipperPositions::class, ['id' => 'position_id'])->viaTable('shipper_category_to_position', ['category_id' => 'id'])->with('suppliers')->with('shipperPositionToSuppliers');
    }

    public function getCountPositions() {
        return $this->hasMany(ShipperPositions::class, ['id' => 'position_id'])->viaTable('shipper_category_to_position', ['category_id' => 'id'])->count();
    }

    /**
     * @return bool|int|string|null
     */
    public function getCountCategoryToPoints()
    {
        return $this->hasMany(ShipperCategoryToPoint::class, ['category_id' => 'id'])->count();
    }

    public function getRoleGroup() {
        return $this->hasMany(ShipperRoleGroupToSet::class, ['set_id' => 'id']);
    }
}
