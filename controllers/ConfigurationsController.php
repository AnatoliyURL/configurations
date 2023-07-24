<?php

namespace app\controllers\shipper;

use app\controllers\SuperController;
use app\models\AuthItem;
use app\models\Franchises;
use app\models\PointsOfSale;
use app\models\ShipperCategories;
use app\models\ShipperCategoryToPoint;
use app\models\ShipperCategoryToPosition;
use app\models\ShipperPositions;
use app\models\ShipperRoleGroup;
use app\models\ShipperRoleGroupToRole;
use app\models\ShipperRoleGroupToSet;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Response;

class ConfigurationsController extends SuperController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'sets',
                            'get-content',
                            'get-inf-set',
                            'add-position-to-set',
                            'delete-position-to-set',
                            'change-franchise-to-set',
                            'change-group-role-to-set',
                            'change-title-to-set',
                            'get-inf-configuration',
                            'delete-categories-to-point',
                            'add-categories-to-point',
                            'create-set',
                            'add-points-to-categories'
                        ],
                        'permissions' => ['shipper_admin'],
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'settings',
                            'sets',
                            'get-role-to-group',
                            'get-sets-to-group',
                            'create-group-role',
                            'delete-group-role',
                            'add-roles-to-group',
                            'add-sets-to-group'
                        ],
                        'roles' => ['super_admin'],
                    ]
                ],
            ],
        ];
    }

    public function actionSets()
    {
        $setsToRole = ShipperRoleGroupToRole::find()
            ->joinWith(['group'])
            ->where(['role' => $this->role, 'is_deleted' => false])
            ->all();

        $sets = ShipperCategories::find()
            ->joinWith('roleGroup')
            ->where(['shipper_categories.franchise_id' => $this->allowFids])
            ->andWhere(['shipper_role_group_to_set.group_role_id' => ArrayHelper::getColumn($setsToRole, 'group_id')])
            ->orderBy(['title' => SORT_ASC])
            ->all();

        $groups = ShipperRoleGroup::find()->where(['is_deleted' => false])->all();
        $points = PointsOfSale::find()
            ->where(['fid' => $this->allowFids])
            ->orderBy(['active' => SORT_DESC, 'title' => SORT_ASC])
            ->all();
        $fids = Franchises::find()->where(['id' => $this->allowFids])->orderBy(['title' => SORT_ASC])->all();
        $positions = ShipperPositions::find()
            ->with(['shipperPositionToSuppliers'])
            ->where([
                'active' => '1',
                'city_id' => (User::current())->franchisesManufactoryCitiesIds,
            ])
            ->orderBy(['title' => SORT_ASC])
            ->all();

        return $this->render('sets', compact('sets', 'groups', 'points', 'fids', 'positions'));
    }

    public function actionGetContent()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $configurations_id = Yii::$app->request->post("configurations");
        $points_id = Yii::$app->request->post("points");

        $setsToRole = ShipperRoleGroupToRole::find()
            ->joinWith(['group'])
            ->where(['role' => $this->role, 'is_deleted' => false])
            ->all();

        $sets = ShipperCategories::find()
            ->joinWith('roleGroup')
            ->where(['shipper_categories.franchise_id' => $this->allowFids])
            ->andWhere(['shipper_role_group_to_set.group_role_id' => ArrayHelper::getColumn($setsToRole, 'group_id')]);

        $points = PointsOfSale::find()->where(['fid' => $this->allowFids]);

        if ($configurations_id) $sets->andWhere(['shipper_categories.id' => $configurations_id]);
        if ($points_id) $points->andWhere(['points_of_sale.id' => $points_id]);

        $points = $points->orderBy(['active' => SORT_DESC, 'title' => SORT_ASC])->all();
        $sets = $sets->orderBy(['title' => SORT_ASC])->all();

        $content = $this->renderPartial("_sets", compact('sets', 'points'));

        return ['result' => 1, 'content' => $content];
    }

    public function actionGetInfSet()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $set_id = Yii::$app->request->post('set_id', null);
        $user = User::current();

        $set = ShipperCategories::findOne($set_id);
        $franchises = Franchises::find()->where(['id' => $this->allowFids])->orderBy(['title' => SORT_ASC])->all();
        $groups = ShipperRoleGroup::find()->where(['is_deleted' => false])->all();
        $groupsSet = ArrayHelper::getColumn($set->roleGroup, 'group_role_id');
        $positions = ShipperPositions::find()
            ->with(['shipperPositionToSuppliers'])
            ->where([
                'active' => '1',
                'city_id' => $user->franchisesManufactoryCitiesIds,
            ])
            ->orderBy(['title' => SORT_ASC])
            ->all();

        $points = PointsOfSale::find()
            ->where(['fid' => $this->allowFids])
            ->orderBy(['active' => SORT_DESC, 'title' => SORT_ASC])
            ->all();

        $content = $this->renderPartial("_set", compact('set', 'franchises', 'groups', 'positions', 'groupsSet', 'points'));

        return ['result' => 1, 'content' => $content];
    }

    public function actionAddPositionToSet()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $set_id = Yii::$app->request->post('set_id', null);
        $positions_id = Yii::$app->request->post('positions_ids', null);

        $set = ShipperCategories::findOne($set_id);
        $positions = ShipperPositions::findAll(['id' => $positions_id]);

        if ($set && $positions) {

            foreach ($positions as $position) {
                $add = new ShipperCategoryToPosition(['category_id' => $set->id, 'position_id' => $position->id]);
                $add->save();
            }

            return ['result' => 1, 'message' => 'Продукты добавлены'];
        }

        return ['result' => 0, 'message' => 'Продукты не найдены'];
    }

    public function actionDeletePositionToSet()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $set_id = Yii::$app->request->post('set_id', null);
        $position_id = Yii::$app->request->post('position_ids', null);

        $position = ShipperCategoryToPosition::findOne(['category_id' => $set_id, 'position_id' => $position_id]);

        if ($position) {
            if ($position->delete()) {
                return ['result' => 1, 'message' => 'Продукт удален'];
            }

            return ['result' => 0, 'message' => 'Ошибка удаления'];
        }

        return ['result' => 0, 'message' => 'Возникла ошибка'];
    }

    public function actionChangeFranchiseToSet()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $set_id = Yii::$app->request->post('set_id', null);
        $fid = Yii::$app->request->post('fid', null);

        $set = ShipperCategories::findOne($set_id);
        $franchises = Franchises::findOne($fid);

        if ($set && $franchises) {
            $set->franchise_id = $franchises->id;
            if ($set->save()) {
                return ['result' => 1, 'message' => 'Сменили франшизу'];
            }
        }

        return ['result' => 0, 'message' => 'Не указана франшиза'];
    }

    public function actionChangeGroupRoleToSet()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $set_id = Yii::$app->request->post('set_id', null);
        $groups_id = Yii::$app->request->post('groups_id', null);

        $groups = ShipperRoleGroup::findAll(['id' => $groups_id]);
        $set = ShipperCategories::findOne($set_id);

        if ($set) {
            ShipperRoleGroupToSet::deleteAll(['set_id' => $set->id]);
            foreach ($groups as $group) {
                $relation = new ShipperRoleGroupToSet(['group_role_id' => $group->id, 'set_id' => $set->id]);
                $relation->save();
            }
            return ['result' => 1, 'message' => 'Группы добавлены'];
        }

        return ['result' => 0, 'message' => 'Возникла ошибка'];
    }

    public function actionChangeTitleToSet()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $set_id = Yii::$app->request->post('set_id', null);
        $title = Yii::$app->request->post('title', null);

        $set = ShipperCategories::findOne($set_id);

        if ($set && $title) {
            $set->title = $title;
            if ($set->save()) {
                return ['result' => 1, 'message' => 'Сохранено'];
            }

            return ['result' => 0, 'message' => $set->errors];
        }

        return ['result' => 0, 'message' => 'Заголовок обязан быть'];
    }

    public function actionGetInfConfiguration()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $point_id = Yii::$app->request->post('point_id', null);
        $sets = ShipperCategories::find()->orderBy(['title' => SORT_ASC])->all();

        $point = PointsOfSale::findOne($point_id);
        $setsPoint = ArrayHelper::getColumn($point->categories, 'id');

        $content = $this->renderPartial("_configuration", compact('sets', 'point', 'setsPoint'));

        return ['result' => 1, 'content' => $content];
    }

    public function actionDeleteCategoriesToPoint()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $point_id = Yii::$app->request->post('point_id', null);
        $categories_id = Yii::$app->request->post('categories_id', null);

        $relation = ShipperCategoryToPoint::findOne(['point_id' => $point_id, 'category_id' => $categories_id]);

        if ($relation) {
            if ($relation->delete()) {
                return ['result' => 1, 'message' => 'Удалено'];
            }

            return ['result' => 0, 'message' => $relation->errors];
        }

        return ['result' => 0, 'message' => 'Не удалось удалить'];
    }

    public function actionAddCategoriesToPoint()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $point_id = Yii::$app->request->post('point_id', null);
        $categories_id = Yii::$app->request->post('categories_id', null);

        $point = PointsOfSale::findOne($point_id);

        if ($point) {
            foreach ($categories_id as $category) {
                $relation = new ShipperCategoryToPoint(['point_id' => $point_id, 'category_id' => $category]);
                $relation->save();
            }

            return ['result' => 1, 'message' => 'Добавлено'];
        }

        return ['result' => 0, 'message' => 'Не было найдено точки'];
    }

    public function actionAddPointsToCategories()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $points = Yii::$app->request->post('points', null);
        $categories_id = Yii::$app->request->post('categories_id', null);

        if (!ShipperCategories::findOne($categories_id))  return ['result' => 0, 'message' => 'Не было найдено конфигурации'];

        if ($points && $categories_id) {
            foreach ($points as $point_id) {
                $point = PointsOfSale::findOne($point_id);
                if (!$point) continue;
                $relation = new ShipperCategoryToPoint(['point_id' => $point->id, 'category_id' => $categories_id]);
                $relation->save();
            }

            return ['result' => 1, 'message' => 'Добавлено'];
        }

        return ['result' => 0, 'message' => 'Не было найдено точки'];
    }

    public function actionCreateSet()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $groupRole = Yii::$app->request->post('groupRole');
        $sets = Yii::$app->request->post('sets');

        $category = new ShipperCategories();
        $category->load(Yii::$app->request->post(), '');

        if ($category->save()) {
            if ($groupRole) {
                foreach ($groupRole as $group) {
                    $relate = new ShipperRoleGroupToSet();
                    $relate->group_role_id = $group;
                    $relate->set_id = $category->id;
                    $relate->save();
                }
            }

            if ($sets) {
                foreach ($sets as $set) {
                    $relate = new ShipperCategoryToPosition();
                    $relate->category_id = $category->id;
                    $relate->position_id = $set;
                    $relate->save();
                }
            }

            return ['result' => 1, 'message' => 'Добавлено'];
        }

        return ['result' => 0, 'message' => $category->errors];
    }

}
