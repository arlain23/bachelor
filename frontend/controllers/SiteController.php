<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\web\Request;
use yii\web\Response;
use common\models\LoginForm;
use common\models\FileEntry;
use common\models\FileEntryCategory;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\db\Query;

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
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
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
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
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
     * Logs in a user.
     *
     * @return mixed
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
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
    
    /**
     * Displays database page.
     *
     * @return mixed
     */
    public function actionDatabase()
    {
    	//needed for sorting  	
    	$selectCategories = '';
    	$dateFrom = '';
    	$dateTill = '';
    	$searchField = '';
    	//sorting
    	if (isset($_POST['sort-radio'])){
    		$sortType = $_POST['sort-radio'];
    		$asc = $_POST['sort-direction'];
    	}
    	else{
    		$sortType = "createDate";
    		$asc = 1;
    	}
    	
    	
    	if (Yii::$app->user->isGuest){
    		$query = FileEntry::find()->where(["isPrivate"=>0]);
    	}
    	else{
    		$query = FileEntry::find();
    	}
    	
    	
    	if (isset($_POST['search-field'])){
    		$searchField = $_POST['search-field'];	
    		if ($searchField != ''){
    			$query->andFilterWhere(['like', 'LOWER(title)', strtolower($searchField)]);  
    			$query->orFilterWhere(['like', 'LOWER(description)', strtolower($searchField)]);
    		}
    	}
    	
    	if (isset($_POST['select-categories'])){
			$selectCategories = $_POST['select-categories'];
    		$dateFrom = $_POST['dateFrom'];
    		$dateTill = $_POST['dateTill'];
    		
    		if ($selectCategories != '0' && $selectCategories != ''){
    					$query->innerJoin("fileEntryCategory","fileEntry.fileEntryID = fileEntryCategory.fileEntryID")	
    			->andWhere("fileEntryCategory.categoryID = $selectCategories");

    			
    		}
    		if ($dateFrom != ''){
    			$query->andWhere("createDate >= '$dateFrom'");
    		}
    		if ($dateTill != ''){
    			$query->andWhere("createDate <= '$dateTill'");
    		}
    	}

    	//sorting
    	
    	
    	if ($asc == 1){
    		$query->orderBy(["TRIM($sortType)" => SORT_ASC]);	
    	}
    	else{
    		$query->orderBy(["TRIM($sortType)" => SORT_DESC]);
    	}
    	

    	
    	$countQuery = clone $query;
    	$pagination = new Pagination([
    			'defaultPageSize' => 9,
    			'totalCount' => $countQuery->count(),
    	]);
    	$fileEntries = $query->offset($pagination->offset)->limit($pagination->limit)->all();
    	
    	
    	return $this->render('database', [
    			'fileEntries' => $fileEntries,
    			'pagination' => $pagination,
    			'totalCount' => $countQuery->count(),
    			'sortType' => $sortType,
    			'asc' => $asc,
    			'selectCategories' => $selectCategories,
    			'dateFrom' => $dateFrom,
    			'dateTill' => $dateTill,
    			'searchField' => $searchField,   			
    	]);
    }   
    
    /**
     * Downloads zipped files from database
     *
     * @return zip file
     */

    //TODO: make it quicker
    public function actionDownloadZippedFiles(){
    	$files = [];
    	$index = 0;
    	$rootPath = Yii::getAlias('@frontend') . '/web/images/uploads/' ;
    	if(!empty($_POST['downloadCheck'])) {
    		foreach($_POST['downloadCheck'] as $fileId) {
    			$fileEntry = FileEntry::findOne($fileId);
    			$path = $rootPath . $fileEntry->fileURL;
    			$files[$index] = $path;
				$index = $index + 1;
    		}
    	}
    	Yii::info(count($files));
		if(count($files) === 0){
			return $this->actionDatabase();
		}
    	unset($_POST['downloadCheck']);
    	/*
    	$zip=new \ZipArchive();
    	$timestamp = (new \DateTime())->getTimestamp();
    	
    	$destination=Yii::getAlias('@frontend') . '/web/tmp/' . $timestamp . 'eletelDatabase.zip';
    	if($zip->open($destination, \ZipArchive::CREATE) === true) {
    		foreach ($files as $fl) {
    			$relativePath = substr($fl, strlen($rootPath));
    			$zip->addFromString($relativePath, file_get_contents($fl));
    			//$zip->addFile($fl, $relativePath);
    		}
    		$zip->close();	
    		return Yii::$app->response->sendFile($destination, 'eletelDatabase.zip')->on(\yii\web\Response::EVENT_AFTER_SEND, function($event) {
    			unlink($event->data);
    		}, $destination);
    		
    	}
    	else{
    		$zip-> close();
    	}*/
    	$timestamp = (new \DateTime())->getTimestamp();
    	$destination=Yii::getAlias('@frontend') . '/web/tmp/' . $timestamp . 'eletelDatabase.zip';
    	$zip = new \ZipStream\ZipStream("eletelDatabase.zip");


    	foreach ($files as $fl) {
    		$relativePath = substr($fl, strlen($rootPath));
    		$zip->addFileFromPath($relativePath,$fl);
    		Yii::info("added");
    	}
    	
    	$zip->finish();
    	$this->redirect('actionDatabase');

    }
    
    /**
     * Displays details page.
     *
     * @return mixed
     */
    public function actionDetails()
    {
    	$fileId = $_GET['fileId'];
    	$fileEntry = FileEntry::findOne($fileId);
    	$isPrivate = $fileEntry->isPrivate;
    	$isPapayable = false;
    	$isVTK = false;
    	$extension = $fileEntry->fileExtension;

    	$acceptedFormats = [
    		"nii",
    		"nii.gz",
    		"surf.gii",
    		'surf',
    	];
    	if (in_array($extension, $acceptedFormats)) {
    		$isPapayable = true;
    	};
    	/*VTK */
    	$acceptedFormats2 = [
    			"stl",
				"vtk",
    	];
    	if (in_array($extension, $acceptedFormats2)) {
    		$isVTK = true;
    	};
    	return $this->render('details',[
    			'fileEntry' => $fileEntry,
    			'isPapayable' => $isPapayable,
    			'isVTK' => $isVTK,
    			'isPrivate' => $isPrivate,
    	]);
    }
    /**
     * Downloads one file.
     *
     * @return the file
     */
    public function actionDownloadFile()
    {
    	$fileId = $_GET['fileId'];
    	$fileEntry = FileEntry::findOne($fileId);
    	$path = Yii::getAlias('@frontend') . '/web/images/uploads/' . $fileEntry->fileURL;
    	
    	if (!is_file($path)) {
    		throw new \yii\web\NotFoundHttpException('The file does not exists.');
    	}
    	$title = trim($fileEntry->title) . "." . $fileEntry->fileExtension;
    	return Yii::$app->response->sendFile($path, $title);
    	
    	
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
