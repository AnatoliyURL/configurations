<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "shipper_role_group_to_role".
 *
 * @property int $id
 * @property int $group_id
 * @property string $role
 *
 * @property ShipperRoleGroup $group
 */
class ShipperRoleGroupToRole extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shipper_role_group_to_role';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['group_id'], 'integer'],
            [['role'], 'string', 'max' => 255],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShipperRoleGroup::className(), 'targetAttribute' => ['group_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'group_id' => 'Group ID',
            'role' => 'Role',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(ShipperRoleGroup::className(), ['id' => 'group_id']);
    }
}
