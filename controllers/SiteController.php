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
                ->orderBy(['banner_id' => SORT_DESC])
                ->all();
        $top8 = \app\models\Deals::find()
                ->where(['is_active' => 1, 'is_deleted' => 0, 'featured' => 1])
                ->andWhere(['>=', 'DATE(end_date)', date('Y-m-d')])
                ->offset(0)
                ->limit(16)
                ->orderBy(['deal_id' => SORT_DESC])
                ->all();
        $top2 = \app\models\Deals::find()
                ->where(['is_active' => 1, 'is_deleted' => 0, 'featured' => 1])
                ->andWhere(['>=', 'DATE(end_date)', date('Y-m-d')])
                ->offset(16)
                ->limit(1)
                ->orderBy(['deal_id' => SORT_DESC])
                ->all();
        $stores = \app\models\Stores::find()
                ->select(['stores.*', '(
                    SELECT count(deal_stores.deal_store_id) 
                    FROM deal_stores 
                    LEFT JOIN deals ON deal_stores.deal_id = deals.deal_id
                    WHERE deal_stores.store_id = stores.store_id AND deals.is_active = 1 AND deals.is_deleted = 0 AND DATE(deals.end_date) >= "' . date('Y-m-d') . '"
                ) as no_of_deal'])
                ->where(['is_active' => 1, 'is_deleted' => 0])
                ->having(['>', 'no_of_deal', 0])
                ->orderBy(['no_of_deal' => SORT_DESC])
                ->limit(18)
                ->all();
        
        $products = \app\models\Products::find()
                ->where(['is_active' => 1, 'is_deleted' => 0,'is_featured' => 1])
                ->limit(18)
                ->orderBy('RAND()')
                ->all();
        
        return $this->render('index', [
                    'banners' => $banners,
                    'top8' => $top8,
                    'top2' => $top2,
                    'stores' => $stores,
                    'products' => $products,
        ]);
    }

    public function actionCategories() {
        $this->layout = 'site_main';
        $categories = \app\models\Categories::find()
                ->select(['categories.*', '(
                    SELECT count(deal_categories.deal_category_id)
                    FROM  deal_categories 
                    LEFT JOIN deals ON deal_categories.deal_id = deals.deal_id
                    WHERE deal_categories.category_id = categories.category_id AND deals.is_active = 1 AND deals.is_deleted = 0 AND DATE(deals.end_date) >= "' . date('Y-m-d') . '"
                 ) as no_of_deal'])
                ->where(['is_active' => 1, 'is_deleted' => 0])
                ->having(['>', 'no_of_deal', 0])
                ->orderBy(['no_of_deal' => SORT_DESC])
                ->all();
        return $this->render('categories', [
                    'categories' => $categories
        ]);
    }

    public function actionStores() {
        $this->layout = 'site_main';
        $stores = \app\models\Stores::find()
                ->select(['stores.*', '(
                    SELECT count(deal_stores.deal_store_id) 
                    FROM deal_stores 
                    LEFT JOIN deals ON deal_stores.deal_id = deals.deal_id
                    WHERE deal_stores.store_id = stores.store_id AND deals.is_active = 1 AND deals.is_deleted = 0 AND DATE(deals.end_date) >= "' . date('Y-m-d') . '"
                ) as no_of_deal'])
                ->where(['is_active' => 1, 'is_deleted' => 0])
                ->having(['>', 'no_of_deal', 0])
                ->orderBy(['no_of_deal' => SORT_DESC])
                ->all();
        return $this->render('stores', [
                    'stores' => $stores
        ]);
    }

    public function actionCouponsDeals() {
        $get = Yii::$app->request->queryParams;
        $this->layout = 'site_main';
        $query = \app\models\Deals::find()
                ->join('LEFT JOIN', 'deal_categories', 'deals.deal_id = deal_categories.deal_id')
                ->join('LEFT JOIN', 'deal_stores', 'deals.deal_id = deal_stores.deal_id')
                ->where(['is_active' => 1, 'is_deleted' => 0])
                ->andWhere(['>=', 'DATE(end_date)', date('Y-m-d')]);
        if (isset($get['type']) && !empty($get['id'])) {
            if ($get['type'] == 'c') {
                $query->andWhere(['deal_categories.category_id' => $get['id']]);
            } else {
                $query->andWhere(['deal_stores.store_id' => $get['id']]);
            }
        }
        if (!empty($get['q'])) {
            $terms = explode(' ', $get['q']);
            foreach ($terms as $q) {
                $query->andWhere([
                    'OR',
                    ['LIKE', 'deals.title', $q],
                    ['LIKE', 'deals.content', $q],
                ]);
            }
        }
        $query->groupBy(['deals.deal_id']);
        if (!empty($get['sort_by'])) {
            if ($get['sort_by'] == 'end_date_desc') {
                $query->orderBy(['end_date' => SORT_DESC]);
            } else if ($get['sort_by'] == 'end_date_asc') {
                $query->orderBy(['end_date' => SORT_ASC]);
            } else if ($get['sort_by'] == 'oldest') {
                $query->orderBy(['deal_id' => SORT_ASC]);
            } else if ($get['sort_by'] == 'latest') {
                $query->orderBy(['deal_id' => SORT_DESC]);
            }
        } else {
            $query->orderBy(['deal_id' => SORT_DESC]);
        }
        //echo $query->createCommand()->rawSql;
        $countQuery = clone $query;
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize' => 24
        ]);
        $models = $query->offset($pages->offset)
                ->limit($pages->limit)
                ->all();
        return $this->render('coupons-deals', [
                    'models' => $models,
                    'pages' => $pages,
        ]);
    }

    public function actionCouponDetails() {
        $get = Yii::$app->request->queryParams;
        $this->layout = 'site_main';
        $model = \app\models\Deals::find()
                ->where(['is_active' => 1, 'is_deleted' => 0, 'deal_id' => $get['id']])
                ->andWhere(['>=', 'DATE(end_date)', date('Y-m-d')])
                ->one();
        if (empty($model)) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }
        $store = \app\models\Stores::find()->where(['api_store_id' => $model->program_id])->one();
        $related = \app\models\Deals::find()
                ->where(['is_active' => 1, 'is_deleted' => 0])
                ->andWhere(['!=', 'deal_id', $model->deal_id])
                ->andWhere(['>=', 'DATE(end_date)', date('Y-m-d')])
                ->limit(9)
                ->orderBy('RAND()')
                ->all();
        return $this->render('coupon-details', [
                    'model' => $model,
                    'store' => $store,
                    'related' => $related,
        ]);
    }

    public function actionCms() {
        $get = Yii::$app->request->queryParams;
        $this->layout = 'site_main';
        $model = \app\models\Cms::findOne($get['id']);
        return $this->render('page', [
                    'model' => $model,
        ]);
    }

    public function actionSubscribe() {
        if (Yii::$app->request->isAjax) {
            $request = Yii::$app->request->bodyParams;
            if (!empty($request['NewsletterSubscriber']['email'])) {
                $model = \app\models\NewsletterSubscriber::find()
                        ->where(['email' => $request['NewsletterSubscriber']['email']])
                        ->one();
                if (empty($model)) {
                    $model = new \app\models\NewsletterSubscriber();
                }
                $model->created_at = date('Y-m-d H:i:s');
                if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                    $model->is_active = 1;
                    $model->save();
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return ['success' => 1, 'msg' => Yii::t('app', 'Thank you for subscribing to our newsletter!')];
                } else {
                    $error = \yii\widgets\ActiveForm::validate($model);
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return ['success' => 3, 'msg' => $error];
                }
            } else {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['success' => 2, 'msg' => Yii::t('app', 'There was error processing your request.Please try again')];
            }
        }
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

        return $this->redirect(['site/login']);
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

    public function actionError() {
        $this->layout = 'site_main';
        $exception = Yii::$app->errorHandler->exception;
        return $this->render('error', ['exception' => $exception]);
    }

    public function actionProducts() {
        $get = Yii::$app->request->queryParams;
        $this->layout = 'site_main';
        $query = \app\models\Products::find()
                ->where(['is_active' => 1, 'is_deleted' => 0]);
        $query->orderBy(['product_id' => SORT_DESC]);
        $countQuery = clone $query;
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize' => 24
        ]);
        $models = $query->offset($pages->offset)
                ->limit($pages->limit)
                ->all();
        return $this->render('products', [
                    'models' => $models,
                    'pages' => $pages,
        ]);
    }
    
    public function actionProductDetails() {
        $get = Yii::$app->request->queryParams;
        $this->layout = 'site_main';
        $model = \app\models\Products::find()
                ->where(['is_active' => 1, 'is_deleted' => 0, 'product_id' => $get['id']])
                ->one();
        if (empty($model)) {
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }
        $related = \app\models\Products::find()
                ->where(['is_active' => 1, 'is_deleted' => 0])
                ->andWhere(['!=', 'product_id', $model->product_id])
                ->limit(8)
                ->orderBy('RAND()')
                ->all();
        return $this->render('product-details', [
                    'model' => $model,
                    'related' => $related,
        ]);
    }

}
