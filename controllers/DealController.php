<?php

namespace app\controllers;

use Yii;
use app\models\Deals;
use app\models\DealSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\components\UserIdentity;
use app\components\AccessRule;

/**
 * DealController implements the CRUD actions for Deals model.
 */
class DealController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'only' => ['index', 'view', 'create', 'update', 'delete', 'activate'],
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'activate'],
                        'allow' => true,
                        'roles' => [
                            UserIdentity::ROLE_ADMIN
                        ]
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Deals models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new DealSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Deals model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate() {
        $model = new Deals();
        $model->sys_user_ip = $_SERVER['REMOTE_ADDR'];
        $model->coupon_id = time();
        if ($model->load(Yii::$app->request->post())) {
            $request = Yii::$app->request->bodyParams;
            //debugPrint($request);exit;
            $model->is_active = 0;
            $model->is_deleted = 0;
            if ($model->save()) {
                if(!empty($model->categories_id)){
                    foreach ($model->categories_id as $cid)
                    {
                        $dealCategory = new \app\models\DealCategories();
                        $dealCategory->category_id = $cid;
                        $dealCategory->deal_id = $model->deal_id;
                        $dealCategory->created_at = date("Y-m-d H:i:s");
                        $dealCategory->save();
                    }
                }
                if(!empty($model->stores_id)){
                    foreach ($model->stores_id as $sid)
                    {
                        $dealStore = new \app\models\DealStores();
                        $dealStore->store_id = $sid;
                        $dealStore->deal_id = $model->deal_id;
                        $dealStore->created_at = date("Y-m-d H:i:s");
                        $dealStore->save();
                    }
                }
                Yii::$app->session->setFlash('success', 'Deal successfully added');
                return $this->redirect(['index']);
            } else {
                echo json_encode($model->errors);
                return $this->render('create', [
                            'model' => $model,
                ]);
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing Deals model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                \app\models\DealCategories::deleteAll('deal_id = '.$model->deal_id);
                if(!empty($model->categories_id)){
                    foreach ($model->categories_id as $cid)
                    {
                        $dealCategory = new \app\models\DealCategories();
                        $dealCategory->category_id = $cid;
                        $dealCategory->deal_id = $model->deal_id;
                        $dealCategory->created_at = date("Y-m-d H:i:s");
                        $dealCategory->save();
                    }
                }
                \app\models\DealStores::deleteAll('deal_id = '.$model->deal_id);
                if(!empty($model->stores_id)){
                    foreach ($model->stores_id as $sid)
                    {
                        $dealStore = new \app\models\DealStores();
                        $dealStore->store_id = $sid;
                        $dealStore->deal_id = $model->deal_id;
                        $dealStore->created_at = date("Y-m-d H:i:s");
                        $dealStore->save();
                    }
                }
                Yii::$app->session->setFlash('success', 'Deal successfully updated');
                return $this->redirect(['index']);
            } else {
                return $this->render('update', [
                            'model' => $model,
                ]);
            }
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Deals model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $model = $this->findModel($id);
        $model->is_deleted = 1;
        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Deal successfully deleted');
            return $this->redirect(['index']);
        }
    }

    public function actionActivate($id) {
        $model = $this->findModel($id);

        if ($model->is_active == 0)
            $model->is_active = 1;
        else
            $model->is_active = 0;

        if ($model->validate() && $model->save()) {
            return '1';
        } else {

            return json_encode($model->errors);
        }
    }

    /**
     * Finds the Deals model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Deals the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Deals::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
