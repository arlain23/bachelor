<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use backend\models\FileForm;
use common\models\FileEntry;
use yii\web\UploadedFile;
use yii\db\Expression;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
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
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
    	$model = new FileForm();   
    	
    	if (Yii::$app->request->isPost) {
    			$model->load(Yii::$app->request->post());
    			
    			
    			$model->file = UploadedFile::getInstance($model, 'file');
    			$model->preview = UploadedFile::getInstance($model, 'preview');
    			if ($model->upload()) {
    				// file is uploaded successfully
    				
    				$fileEntry = new FileEntry();
    				$fileEntry->title = $model->title;
    				$fileEntry->description = $model->description;
    				$fileEntry->createDate = new Expression('NOW()');
    				/*TODO author to patient 
    				 * add file size 
    				 * add patient data 
    				 * add field for ckeditor
    				 * */
    				$fileEntry->patient = $model->patient;
    				$fileEntry->fileURL = $model->remoteUrl;
    				$fileEntry->gifURL = $model->remotePreviewUrl;
    				$fileEntry->fileExtension = $model->extension;
    				$fileEntry->save();  
    				
    				return $this->render('success', [
	        		'model' => $model,
	        		'message' => "first",
        ]);
    		}
    	}
    	return $this->render('index', [
	        		'model' => $model,
	        		'message' => "first",
        ]);
        
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
