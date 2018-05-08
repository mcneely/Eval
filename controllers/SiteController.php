<?php

namespace app\controllers;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\UploadForm;
use yii\web\UploadedFile;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error'   => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class'           => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \Exception
     */
    public function actionIndex()
    {
        // If user is not logged in, redirect to login.
        if (\Yii::$app->getUser()->isGuest &&
            \Yii::$app->getRequest()->url !== Url::to(\Yii::$app->getUser()->loginUrl)
        ) {
            \Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
        }

        $model = new UploadForm();

        //If form is posted, process
        if (isset($_POST['UploadForm'])) {
            $model->attributes = $_POST['UploadForm'];
            $model->file       = UploadedFile::getInstance($model, 'file');

            if ($model->save()) {
                $spreadsheet = IOFactory::load($model->fullPath);

                // grab the title
                $title = $spreadsheet
                    ->getSheetByName("title")
                    ->getCell("A1")
                    ->getValue();

                if (empty($title)) {
                    throw new \Exception("File error. Can't obtain title.");
                }

                $data = $spreadsheet
                    ->getSheetByName('data')
                    ->toArray();
                array_shift($data);
                $model->clear();

                // Validate that data is formatted properly, otherwise Throw an exception.
                foreach ($data as $element) {
                    if (
                        is_array($element) &&
                        is_numeric($element[0]) &&
                        is_string($element[1]) &&
                        (count($element) == 2)
                    ) {
                        continue;
                    }
                    throw new \Exception("File error. Data not formatted properly");
                }

                //Chart settings to pass to amchart
                $chart = [
                    'type'         => 'pie',
                    'dataProvider' => $data,
                    'valueField'   => 0,
                    'titleField'   => 1,
                    "balloon"      => [
                        "fixedPosition" => true
                    ],
                ];

                // Render Chart Display
                return $this->render("result", [
                    'title' => $title,
                    'chart' => $chart,
                ]);
            }
            throw new \Exception("File was not successfully saved");
        }

        // Render upload Form
        return $this->render('index', [
            'model' => $model,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
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
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
