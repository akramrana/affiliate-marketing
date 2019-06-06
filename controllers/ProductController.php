<?php

namespace app\controllers;

use Yii;
use app\models\Products;
use app\models\ProductSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\components\UserIdentity;
use app\components\AccessRule;

/**
 * ProductController implements the CRUD actions for Products model.
 */
class ProductController extends Controller {

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
                'only' => ['index', 'view', 'create', 'update', 'delete', 'activate', 'import-json-ttc'],
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'activate', 'import-json-ttc'],
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
     * Lists all Products models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Products model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Products model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Products();
        if ($model->load(Yii::$app->request->post())) {
            $request = Yii::$app->request->bodyParams;
//debugPrint($request);exit;
            if ($model->save()) {
                if (!empty($model->categories_id)) {
                    foreach ($model->categories_id as $cid) {
                        $pCategory = new \app\models\ProductCategories();
                        $pCategory->category_id = $cid;
                        $pCategory->product_id = $model->product_id;
                        $pCategory->save();
                    }
                }
                $images = explode('~~', $model->image_url);
                if (!empty($images)) {
                    foreach ($images as $img) {
                        $pCategory = new \app\models\ProductImages();
                        $pCategory->product_id = $model->product_id;
                        $pCategory->image_url = $img;
                        $pCategory->save();
                    }
                }
                return $this->redirect(['view', 'id' => $model->product_id]);
            } else {
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
     * Updates an existing Products model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            $request = Yii::$app->request->bodyParams;
            if ($model->save()) {
                \app\models\ProductCategories::deleteAll('product_id = ' . $model->product_id);
                if (!empty($model->categories_id)) {
                    foreach ($model->categories_id as $cid) {
                        $pCategory = new \app\models\ProductCategories();
                        $pCategory->category_id = $cid;
                        $pCategory->product_id = $model->product_id;
                        $pCategory->save();
                    }
                }
                \app\models\ProductImages::deleteAll('product_id = ' . $model->product_id);
                $images = explode('~~', $model->image_url);
                if (!empty($images)) {
                    foreach ($images as $img) {
                        $pCategory = new \app\models\ProductImages();
                        $pCategory->product_id = $model->product_id;
                        $pCategory->image_url = $img;
                        $pCategory->save();
                    }
                }
                return $this->redirect(['view', 'id' => $model->product_id]);
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
     * Deletes an existing Products model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $model = $this->findModel($id);
        $model->is_deleted = 1;
        $model->save(false);

        return $this->redirect(['index']);
    }

    public function actionActivate($id) {
        $model = $this->findModel($id);

        if ($model->is_active == 0)
            $model->is_active = 1;
        else
            $model->is_active = 0;

        if ($model->save(false)) {
            return '1';
        } else {

            return json_encode($model->errors);
        }
    }

    public function actionFeatured($id) {
        $model = $this->findModel($id);

        if ($model->is_featured == 0)
            $model->is_featured = 1;
        else
            $model->is_featured = 0;

        if ($model->save(false)) {
            return '1';
        } else {

            return json_encode($model->errors);
        }
    }

    public function actionImportTtc() {
        $model = new \app\models\ImportProductForm();
        $model->network_id = 3;
        $model->import_limit = 10;
//
        $api_customer_id = '182121';
        $api_pass_phrase = '29cbe2fa70636a22e98cba80ae58f33aa971ea57';
        $api_site_id = '324798';
//
        if ($model->load(Yii::$app->request->post())) {
            $request = Yii::$app->request->bodyParams;
            $client = new \SoapClient('http://ws.tradetracker.com/soap/affiliate?wsdl', array('compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP));
            $client->authenticate($api_customer_id, $api_pass_phrase);
//
            $options = array(
                'campaignCategoryID' => $request['ImportProductForm']['category_id'],
                'limit' => $request['ImportProductForm']['import_limit'],
                'campaignID' => $request['ImportProductForm']['store_id'],
            );
            $products = $client->getFeedProducts($api_site_id, $options);
            $k = 0;
            $storeModel = \app\models\Stores::find()
                    ->where(['api_store_id' => $request['ImportProductForm']['store_id']])
                    ->one();
            foreach ($products as $obj) {
//debugPrint($obj);
                $product = Products::find()
                        ->where(['feed_id' => $obj->identifier, 'is_deleted' => 0])
                        ->one();
                if (empty($product)) {
                    $product = new Products();
                }
                if ($obj->name == null) {
                    continue;
                }
                $product->network_id = 3;
                $product->feed_id = $obj->identifier;
                $product->name = $obj->name;
                $product->price = $obj->price;
                $product->retail_price = $obj->price;
                $product->sale_price = $obj->price;
                $product->buy_url = $obj->productURL;
                $product->description = $obj->description;
                $product->store_id = !empty($storeModel) ? $storeModel->store_id : "";
                $product->advertiser_name = !empty($storeModel) ? $storeModel->name : "";
                $addtionalInfo = '';
                $currency = 'USD';
                if (!empty($obj->additional)) {
                    foreach ($obj->additional as $addition) {
                        $addtionalInfo .= $addition->name . ": " . $addition->value . '<br/>';
                        if ($addition->name == 'currency') {
                            $currency = $addition->value;
                        }
                    }
                }
                $product->currency = $currency;
                $product->additional_info = $addtionalInfo;
                $product->is_stock = 1;
                $product->is_active = 1;
                $product->save(false);
//
                if (!empty($obj->imageURL)) {
                    $pCategory = new \app\models\ProductImages();
                    $pCategory->product_id = $product->product_id;
                    $pCategory->image_url = $obj->imageURL;
                    $pCategory->save();
                }
                $k++;
            }
            Yii::$app->session->setFlash('success', 'Tradetracker: ' . $k . ' product(s) have been imported.');
            return $this->redirect(['product/import-ttc']);
        }
        return $this->render('import-ttc', [
                    'model' => $model
        ]);
    }

    public function actionImportRakuten() {
        $model = new \app\models\ImportProductForm();
        $model->network_id = 5;
        $model->import_limit = 10;
        if ($model->load(Yii::$app->request->post())) {
            $request = Yii::$app->request->bodyParams;
            $limit = $request['ImportProductForm']['import_limit'];
            $mid = $request['ImportProductForm']['store_id'];

            $storeModel = \app\models\Stores::find()
                    ->where(['api_store_id' => $mid])
                    ->one();

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.rakutenmarketing.com/token",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "grant_type=password&username=akram1991&password=Akram123%24&scope=3628251&client_id=RlotJdtHHAUaOloza3xBSC9W2e8a&client_secret=22_RPM0CzMpRCqX6kMqv9GBOIvAa&undefined=",
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/x-www-form-urlencoded",
                    "Token: 69a27596-1458-4511-b791-8ca5b4bdcb18",
                    "cache-control: no-cache"
                ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                $result = json_decode($response);
                $curl1 = curl_init();
                $url = "https://api.rakutenmarketing.com/productsearch/1.0?max=$limit&mid=$mid";
//echo $url;
                curl_setopt_array($curl1, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_POSTFIELDS => "",
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: Bearer $result->access_token",
                        "Token: 5a5b174e-2d5e-4294-a7eb-4b5622b74aa4",
                        "cache-control: no-cache"
                    ),
                ));
                $response1 = curl_exec($curl1);
//debugPrint($response1);
                $err1 = curl_error($curl1);
                curl_close($curl1);
                if ($err) {
                    echo "cURL Error #:" . $err1;
                } else {
                    libxml_use_internal_errors(true);
                    $cXMLl = simplexml_load_string($response1);
                    if ($cXMLl) {
                        $jsonl = json_encode($cXMLl);
                        $jDatal = json_decode($jsonl, true);
                        $k = 0;
                        if (empty($jDatal['item'][0]) && $jDatal['item']['productname'] != "") {
                            $prd = $jDatal['item'];
//debugPrint($prd);exit;
                            $product = Products::find()
                                    ->where(['feed_id' => $prd['linkid'], 'is_deleted' => 0])
                                    ->one();
                            if (empty($product)) {
                                $product = new Products();
                            }

                            $product->network_id = 5;
                            $product->feed_id = $prd['linkid'];
                            $product->name = $prd['productname'];
                            $product->price = $prd['price'];
                            $product->retail_price = $prd['price'];
                            $product->sale_price = $prd['saleprice'];
                            $product->buy_url = $prd['linkurl'];
                            $product->description = $prd['description']['long'];
                            $product->store_id = !empty($storeModel) ? $storeModel->store_id : "";
                            $product->advertiser_name = !empty($storeModel) ? $storeModel->name : "";
                            $addtionalInfo = '';
                            $currency = 'USD';
                            if (!empty($prd['category'])) {
                                $addtionalInfo .= !empty($prd['category']['primary']) ? "Primary Category: " . $prd['category']['primary'] . '<br/>' : "";
                                $addtionalInfo .= !empty($prd['category']['secondary']) ? "Secondary Category: " . $prd['category']['secondary'] . '<br/>' : "";
                            }
                            $product->currency = $currency;
                            $product->additional_info = $addtionalInfo;
                            $product->is_stock = 1;
                            $product->is_active = 1;
                            $product->save(false);
//
                            if (!empty($prd['imageurl'])) {
                                $pCategory = new \app\models\ProductImages();
                                $pCategory->product_id = $product->product_id;
                                $pCategory->image_url = $prd['imageurl'];
                                $pCategory->save();
                            }

                            $k++;
                        } else {
                            if (!empty($jDatal['item'])) {
//debugPrint($jDatal['item']);exit;
                                foreach ($jDatal['item'] as $prd) {
                                    $product = Products::find()
                                            ->where(['feed_id' => $prd['linkid'], 'is_deleted' => 0])
                                            ->one();
                                    if (empty($product)) {
                                        $product = new Products();
                                    }
                                    if ($prd['productname'] == null) {
                                        continue;
                                    }
                                    $product->network_id = 5;
                                    $product->feed_id = $prd['linkid'];
                                    $product->name = $prd['productname'];
                                    $product->price = $prd['price'];
                                    $product->retail_price = $prd['price'];
                                    $product->sale_price = $prd['saleprice'];
                                    $product->buy_url = $prd['linkurl'];
                                    $product->description = $prd['description']['long'];
                                    $product->store_id = !empty($storeModel) ? $storeModel->store_id : "";
                                    $product->advertiser_name = !empty($storeModel) ? $storeModel->name : "";
                                    $addtionalInfo = '';
                                    $currency = 'USD';
                                    if (!empty($prd['category'])) {
                                        $addtionalInfo .= "Primary Category: " . $prd['category']['primary'] . '<br/>';
                                        $addtionalInfo .= "Secondary Category: " . $prd['category']['secondary'] . '<br/>';
                                    }
                                    $product->currency = $currency;
                                    $product->additional_info = $addtionalInfo;
                                    $product->is_stock = 1;
                                    $product->is_active = 1;
                                    $product->save(false);
//
                                    if (!empty($prd['imageurl'])) {
                                        $pCategory = new \app\models\ProductImages();
                                        $pCategory->product_id = $product->product_id;
                                        $pCategory->image_url = $prd['imageurl'];
                                        $pCategory->save();
                                    }

                                    $k++;
                                }
                            }
                        }
                    }
                }
            }
            Yii::$app->session->setFlash('success', 'Rakuten: ' . $k . ' product(s) have been imported.');
            return $this->redirect(['product/import-rakuten']);
        }
        return $this->render('import-rakuten', [
                    'model' => $model
        ]);
    }

    public function actionImportJsonTtc($store = "") {
        $feed = file_get_contents("productfeed.json");
        $json = json_decode($feed, true);
        if (!empty($json['products'])) {
            foreach ($json['products'] as $products) {
//debugPrint($products);
                $product = new Products();
                $product->network_id = 3;
                $product->feed_id = $products['ID'];
                $product->name = $products['name'];
                $product->price = $products['price']['amount'];
                $product->retail_price = $products['price']['amount'];
                $product->sale_price = $products['price']['amount'];
                $product->currency = $products['price']['currency'];
                $product->buy_url = $products['URL'];
                $product->description = $products['description'];
                $product->advertiser_name = $store;
                $addtionalInfo = '';
                $addtionalInfo .= !empty($products['properties']['categoryPath'][0]) ? 'Category: ' . $products['properties']['categoryPath'][0] . '<br/>' : "";
                $addtionalInfo .= !empty($products['properties']['condition'][0]) ? 'Condition: ' . $products['properties']['condition'][0] . '<br/>' : "";
                $addtionalInfo .= !empty($products['properties']['color'][0]) ? 'Color: ' . $products['properties']['color'][0] . '<br/>' : "";
                $addtionalInfo .= !empty($products['properties']['gender'][0]) ? 'Gender: ' . $products['properties']['gender'][0] . '<br/>' : "";
                $addtionalInfo .= !empty($products['properties']['availability'][0]) ? 'Availability: ' . $products['properties']['availability'][0] . '<br/>' : "";
                $addtionalInfo .= !empty($products['properties']['discount'][0]) ? 'Discount: ' . $products['properties']['discount'][0] . '<br/>' : "";
                $addtionalInfo .= !empty($products['properties']['fromPrice'][0]) ? 'Actual Price: ' . number_format($products['properties']['fromPrice'][0], 2) . ' ' . $products['price']['currency'] . '<br/>' : "";
                $addtionalInfo .= !empty($products['properties']['rating'][0]) ? 'Rating: ' . $products['properties']['rating'][0] . '<br/>' : "";
//$addtionalInfo.= !empty($products['properties']['validTo'][0])?'Valid To: '.$products['properties']['validTo'][0].'<br/>':"";
                $product->additional_info = $addtionalInfo;
                $product->is_stock = 1;
                $product->is_active = 1;
                $product->save(false);
//
                if (!empty($products['images'])) {
                    foreach ($products['images'] as $img) {
                        $pCategory = new \app\models\ProductImages();
                        $pCategory->product_id = $product->product_id;
                        $pCategory->image_url = $img;
                        $pCategory->save();
                    }
                }
            }
        }
        return $this->redirect(['index']);
    }

    public function actionImportEffiliation() {
        $model = new \app\models\ImportProductForm();
        $model->network_id = 5;
        $model->import_limit = 10;
        if ($model->load(Yii::$app->request->post())) {
            $request = Yii::$app->request->bodyParams;
            $limit = $request['ImportProductForm']['import_limit'];
            $mid = $request['ImportProductForm']['store_id'];

            $storeModel = \app\models\Stores::find()
                    ->where(['api_store_id' => $mid])
                    ->one();

            $ch = curl_init();
            $url = "https://apiv2.effiliation.com/apiv2/productfeeds.json?key=yofUp0hyBjdFid85AJUxXdgocgy7FpuU&filter=mines&lg=en";
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);
            $json = json_decode($result, true);
            //debugPrint($json['feeds']);
            if (!empty($json['feeds'])) {
                $i = 0;
                $k = 0;
                foreach ($json['feeds'] as $feed) {
                    $i++;
                    try {
                        $response = @file_get_contents($feed['code']);
                        $content = @simplexml_load_string($response, null, LIBXML_NOCDATA);
                        $j = 0;
                        if (!empty($content->product)) {
                            foreach ($content->product as $p) {
                                if (empty($p->url_image)) {
                                    continue;
                                }
                                $product = Products::find()
                                        ->where(['feed_id' => $p->ean, 'is_deleted' => 0])
                                        ->one();
                                if (empty($product)) {
                                    $product = new Products();
                                }
                                $product->network_id = 4;
                                $product->feed_id = $p->ean;
                                $product->name = $p->name;
                                $product->price = $p->price;
                                $product->retail_price = $p->price;
                                $product->sale_price = $p->price;
                                $product->currency = 'EUR';
                                $product->buy_url = $p->url_product;
                                $product->description = $p->description;
                                $product->advertiser_name = $p->brand;
                                $addtionalInfo = '';
                                $addtionalInfo .= !empty($p->size) ? 'Size: ' . $p->size . '<br/>' : "";
                                $addtionalInfo .= !empty($p->used) ? 'Condition: ' . $p->used . '<br/>' : "";
                                $addtionalInfo .= !empty($p->delivery_time) ? 'Delivery Time: ' . $p->delivery_time . '<br/>' : "";
                                $addtionalInfo .= !empty($p->shipping_cost) ? 'Shipping Cost: ' . $p->shipping_cost . '<br/>' : "";
                                $addtionalInfo .= !empty($p->extras->geschlecht) ? 'Gender: ' . $p->extras->geschlecht . '<br/>' : "";
                                $addtionalInfo .= !empty($p->extras->farbe) ? 'Color: ' . $p->extras->farbe . '<br/>' : "";
                                $addtionalInfo .= !empty($p->extras->altersgruppe) ? 'Age Group: ' . $p->extras->altersgruppe . '<br/>' : "";
                                $addtionalInfo .= !empty($p->extras->produkttyp) ? 'Product Type: ' . $p->extras->produkttyp . '<br/>' : "";
                                $addtionalInfo .= !empty($p->availability) ? 'Availability: ' . $p->availability . '<br/>' : "";
                                $product->additional_info = $addtionalInfo;
                                $product->is_stock = 1;
                                $product->is_active = 1;
                                $product->save(false);
                                if (!empty($p->url_image)) {
                                    $pCategory = new \app\models\ProductImages();
                                    $pCategory->product_id = $product->product_id;
                                    $pCategory->image_url = $p->url_image;
                                    if(!$pCategory->save(false))
                                    {
                                        die(json_encode($pCategory->errors));
                                    }
                                }
                                $k++;
                                $j++;
                                if ($j == $limit) {
                                    break 1;
                                }
                            }
                        }
                    } catch (Exception $e) {
                        
                    }
                }
            }
            Yii::$app->session->setFlash('success', 'Effiliation: ' . $k . ' product(s) have been imported.');
            return $this->redirect(['product/import-effiliation']);
        }

        return $this->render('import-effiliation', [
                    'model' => $model
        ]);
    }

    function parse_xml($xml_str) {
        $items = array();
        $xml_doc = new \SimpleXMLElement($xml_str);
        foreach ($xml_doc->item as $item) {
            $items [] = $item->name;
        }
        return $items;
    }

    function get_xml_from_url($url) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

        $xmlstr = curl_exec($ch);
        curl_close($ch);

        return $xmlstr;
    }

    public function actionTest() {
        for ($i = 0; $i <= 10; $i++) {
            echo "i=$i";
            for ($j = 0; $j <= 10; $j++) {
                echo "j=$j <br/>";
                if ($j == 2) {
                    break 1;
                }
            }
        }
    }

    /**
     * Finds the Products model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Products the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Products::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
