<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\data\Pagination;

class SiteController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions() {
        return [
            /* 'error' => [
              'class' => 'yii\web\ErrorAction',
              ], */
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex() {
        $this->layout = 'site_main';
        $banners = \app\models\Banners::find()
                ->where(['is_active' => 1, 'is_deleted' => 0])
                ->all();
        $top8 = \app\models\Deals::find()
                ->where(['is_active' => 1, 'is_deleted' => 0])
                ->offset(0)
                ->limit(8)
                ->orderBy(['deal_id' => SORT_DESC])
                ->all();
        $top2 = \app\models\Deals::find()
                ->where(['is_active' => 1, 'is_deleted' => 0])
                ->offset(8)
                ->limit(2)
                ->orderBy(['deal_id' => SORT_DESC])
                ->all();
        $stores = \app\models\Stores::find()
                ->where(['is_active' => 1, 'is_deleted' => 0])
                ->limit(8)
                ->orderBy(['store_id' => SORT_DESC])
                ->all();
        return $this->render('index', [
                    'banners' => $banners,
                    'top8' => $top8,
                    'top2' => $top2,
                    'stores' => $stores,
        ]);
    }

    public function actionCategories() {
        $this->layout = 'site_main';
        $categories = \app\models\Categories::find()
                ->where(['is_active' => 1, 'is_deleted' => 0])
                ->all();
        return $this->render('categories', [
                    'categories' => $categories
        ]);
    }

    public function actionStores() {
        $this->layout = 'site_main';
        $stores = \app\models\Stores::find()
                ->where(['is_active' => 1, 'is_deleted' => 0])
                ->all();
        return $this->render('stores', [
                    'stores' => $stores
        ]);
    }

    public function actionCouponsDeals() {
        $get = Yii::$app->request->queryParams;
        $this->layout = 'site_main';
        $query = \app\models\Deals::find()
                ->join('LEFT JOIN','deal_categories','deals.deal_id = deal_categories.deal_id')
                ->join('LEFT JOIN','deal_stores','deals.deal_id = deal_stores.deal_id')
                ->where(['is_active' => 1, 'is_deleted' => 0])
                ->orderBy(['deal_id' => SORT_DESC]);
        if(isset($get['type']) && !empty($get['id'])){
            if($get['type']=='c'){
                $query->andWhere(['deal_categories.category_id' => $get['id']]);
            }
            else{
                $query->andWhere(['deal_stores.store_id' => $get['id']]);
            }
        }
        $query->groupBy(['deals.deal_id']);
        $countQuery = clone $query;
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize' => 12
        ]);
        $models = $query->offset($pages->offset)
                ->limit($pages->limit)
                ->all();
        return $this->render('coupons-deals', [
                    'models' => $models,
                    'pages' => $pages,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin() {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['deal/index']);
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['deal/index']);
        }

        $model->password = '';
        return $this->render('login', [
                    'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact() {
        $this->layout = 'site_main';
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
                    'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout() {
        $this->layout = 'site_main';
        return $this->render('about');
    }

}
