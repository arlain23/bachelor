<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use backend\models\FileForm;
use common\models\FileEntry;
use common\models\FileEntryCategory;
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
                        'actions' => ['login', 'error', 'index'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout','uploader'],
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
     * @return mixed
     */
    public function actionIndex()
    {
    	return $this->render('index');
        
    }
    
    /**
     * Displays uploader page.
     *
     * @return mixed
     */
    public function actionUploader()
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
    			$fileEntry->patient = $model->patient;
    			$fileEntry->fileURL = $model->remoteUrl;
    			$fileEntry->gifURL = $model->remotePreviewUrl;
    			$fileEntry->fileExtension = $model->extension;
    			$fileEntry->fileSize = $model->size;
    			Yii::info($model->content);
    			$fileEntry->content = $model->content;
       			$fileEntry->save();

       			$cateogriesArray = \preg_split("/[\s,]+/",$model->categories);
    			$fileID = $fileEntry->fileEntryId;
    			foreach ($cateogriesArray as &$catId){
    				if ($catId !== " " && $catId !== "" && $catId != 0){
	    				$fileEntryFileCategory = new FileEntryCategory();
	    				$fileEntryFileCategory->categoryID = $catId;
	    				Yii::info($catId);
	    				$fileEntryFileCategory->fileEntryID = $fileID;
	    				Yii::info("saved");
	    				Yii::info($fileID);
	    				$fileEntryFileCategory->save();
	    			}
    			}

    
    			return $this->render('success', [
    					'model' => $model,
    					'message' => "first",
    			]);
    		}
    	}
    	return $this->render('uploader', [
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
