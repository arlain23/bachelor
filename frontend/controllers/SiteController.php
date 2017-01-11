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
    	
    	
    	/* Searcher */
    	

    	if (isset($_POST['search-field'])){
    		$searchString = $_POST['search-field'];		
    		$query = FileEntry::find();
    		$query->andFilterWhere(['like', 'title', $searchString])->orderBy('createDate');
    		$countQuery = clone $query;
    		$pagination = new Pagination([
    				'defaultPageSize' => 10,
    				'totalCount' => $countQuery->count(),
    		]);
    		
    	}
    	elseif (isset($_POST['select-categories'])){
    		$categories = $_POST['select-categories'];
    		$dateFrom = $_POST['dateFrom'];
    		$dateTill = $_POST['dateTill'];
    		
    		$query = FileEntry::find();
    		
    		if ($categories != '0'){
    					$query->innerJoin("fileEntryCategory","fileEntry.fileEntryID = fileEntryCategory.fileEntryID")	
    			->where("fileEntryCategory.categoryID = $categories");

    			
    		}
    		if ($dateFrom != ''){
    			$query->where("createDate >= $dateFrom");
    		}
    		if ($dateTill != ''){
    			$query->andFilterWhere('<=','createDate',$dateTill);
    		}
    		
    		$countQuery = clone $query;
    		$pagination = new Pagination([
    				'defaultPageSize' => 10,
    				'totalCount' => $countQuery->count(),
    		]);
    	}
    	else{
    		$query = FileEntry::find()->orderBy('createDate');
    		$countQuery = clone $query;
    		$pagination = new Pagination([
    				'defaultPageSize' => 10,
    				'totalCount' => $countQuery->count(),
    		]);
    	}
    			
    	$fileEntries = $query->offset($pagination->offset)->limit($pagination->limit)->all();
    	
    	
    	return $this->render('database', [
    			'fileEntries' => $fileEntries,
    			'pagination' => $pagination,
    	]);
    }   
    
    /**
     * Downloads zipped files from database
     *
     * @return zip file
     */

    //TODO: make it quickers
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
    	$zip=new \ZipArchive();
    	$timestamp = (new \DateTime())->getTimestamp();
    	
    	$destination=Yii::getAlias('@frontend') . '/web/tmp/' . $timestamp . 'eletelDatabase.zip';
    	if($zip->open($destination, \ZipArchive::CREATE) === true) {
    		foreach ($files as $fl) {
    			$relativePath = substr($fl, strlen($rootPath));
    			$zip->addFile($fl, $relativePath);
    		}
    		$zip->close();	
    		return Yii::$app->response->sendFile($destination, 'eletelDatabase.zip')->on(\yii\web\Response::EVENT_AFTER_SEND, function($event) {
    			unlink($event->data);
    		}, $destination);
    		/*return Yii::$app->response->sendFile($destination, 'eletelDatabase.zip');*/
    		
    	}
    	else{
    		$zip-> close();
    	}
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
    	$path = Yii::getAlias('@frontend') . '/web/images/uploads/' . $fileEntry->fileURL;
    	
    	return $this->render('details',[
    			'fileEntry' => $fileEntry,
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
    	return Yii::$app->response->sendFile($path, $fileEntry->fileURL);
    	
    	
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
