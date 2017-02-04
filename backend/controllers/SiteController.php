<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\AdminLoginForm;
use backend\models\FileForm;
use backend\models\MultipleFileForm;
use common\models\FileEntry;
use common\models\FileEntryCategory;
use yii\web\UploadedFile;
use yii\db\Expression;

use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Request;
use backend\models\PasswordResetRequestForm;
use backend\models\ResetPasswordForm;
use backend\models\SignupForm;



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
                        'actions' => ['logout','uploader','multiple-uploader','signup','request-password-reset','action-reset-password','ajax'],
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
        	'corsFilter' => [
        		'class' => \yii\filters\Cors::className(),
        		'cors' => [
        			'Access-Control-Allow-Origin' => ['http://backend.dev/, http://frontend.dev/'],
        				
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
     * Adds new file entries to the database
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
    			$fileEntry->content = $model->content;
    			$fileEntry->isPrivate = $model->isPrivate;
       			$fileEntry->save();

       			$cateogriesArray = \preg_split("/[\s,]+/",$model->categories);
    			$fileID = $fileEntry->fileEntryId;
    			$isNifti = ($fileEntry->fileExtension == 'nii' || $fileEntry->fileExtension == 'gz');
    			foreach ($cateogriesArray as &$catId){
    				if ($catId !== " " && $catId !== "" && $catId != 0){
	    				$fileEntryFileCategory = new FileEntryCategory();
	    				$fileEntryFileCategory->categoryID = $catId;
	    				$fileEntryFileCategory->fileEntryID = $fileID;
	    				$fileEntryFileCategory->save();
	    			}
    			}
    			return $this->render('success', [
    					'model' => $model,
    					'fileEntries' => $fileEntry,
    					'isNifti' => $isNifti,
    			]);
    		}
    	}
    	return $this->render('uploader', [
    			'model' => $model,
    	]);
    
    }
    

    /**
     * Displays multiuploader page.
     * Adds new file entries to the database
     * 
     * @return mixed
     */
    public function actionMultipleUploader()
    {
    	$model = new MultipleFileForm();
    
    	if (Yii::$app->request->isPost) {
    		$model->load(Yii::$app->request->post());
    		
    		$fileEntries = [];
    		$extensions = [];
    		$model->files = UploadedFile::getInstances($model, 'files');
    		if ($model->upload()) {
    			// file is uploaded successfully
    			for ($i = 0; $i < count($model->titles); $i++){
    				$fileEntry = new FileEntry();
    				$fileEntry->title = $model->titles[$i];
    				$fileEntry->createDate = new Expression('NOW()');
    				$fileEntry->fileURL = $model->remoteUrls[$i];
    				$fileEntry->fileExtension = $model->extensions[$i];
    				$fileEntry->fileSize = $model->sizes[$i];
    				$fileEntry->isPrivate = $model->isPrivate;
    				$fileEntry->gifURL = $model->remoteGifsUrls[$i];
    				$fileEntry->save();
    				$fileEntries[$i] = $fileEntry;
    				$extensions[$i] = $fileEntry->fileExtension;
    			}
    			
    			$isNifti = false;
    			foreach ($extensions as $extension ){
    				if ($extension == 'nii' || $extension == 'gz'){
    					$isNifti = true;
    				}
    			}
    			return $this->render('success', [
    					'model' => $model,
    					'fileEntries' => $fileEntries,
    					'isNifti' => $isNifti,
    			]);
    		}
    	}
    	return $this->render('multipleUploader', [
    			'model' => $model,
    	]);
   
    }
    
    public function actionAjax()
    {
    	Yii::info("ajax");
    	if (Yii::$app->request->isPost){
    		if (isset($_POST['imgBase64'])){
    			$base64Data = $_POST['imgBase64'];
    			$imageIndex = $_POST['imageIndex'];		
    			$uniqueID = $_POST['uniqueID'];
    			
    			$data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Data));
    			$fileName = $imageIndex . '_image.png';
    			$pathToDirectory = Yii::getAlias('@frontend') . '/web/images/uploads/' . $uniqueID;
    			if (!file_exists($pathToDirectory)) {
    				mkdir($pathToDirectory, 0777, true);
    			}
    			
    			$path = $pathToDirectory . '/'. $fileName;
    			file_put_contents($path, $data);
    			Yii::info("file uploaded");
    			$test = "Ajax succedded";
    		}
    		else{
    			$test = "Ajax failed";
    		}
    		return \yii\helpers\Json::encode($test);
    	}	
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
    
    	$model = new AdminLoginForm();
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
    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
    	$model = new SignupForm();
    	if ($model->load(Yii::$app->request->post())) {
    		if ($user = $model->signup()) {
    			if (Yii::$app->getUser()->login($user)) {
    				return $this->goHome();
    			}
    		}
    	}
    
    	return $this->render('signup', [
    			'model' => $model,
    	]);
    }
    
    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
    	$model = new PasswordResetRequestForm();
    	if ($model->load(Yii::$app->request->post()) && $model->validate()) {
    		if ($model->sendEmail()) {
    			Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
    
    			return $this->goHome();
    		} else {
    			Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
    		}
    	}
    
    	return $this->render('requestPasswordResetToken', [
    			'model' => $model,
    	]);
    }
    
    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
    	try {
    		$model = new ResetPasswordForm($token);
    	} catch (InvalidParamException $e) {
    		throw new BadRequestHttpException($e->getMessage());
    	}
    
    	if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
    		Yii::$app->session->setFlash('success', 'New password was saved.');
    
    		return $this->goHome();
    	}
    
    	return $this->render('resetPassword', [
    			'model' => $model,
    	]);
    }
}
