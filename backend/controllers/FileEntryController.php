<?php

namespace backend\controllers;

use Yii;
use common\models\FileEntry;
use common\models\FileEntrySearch;
use common\models\FileEntryCategory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;


/**
 * FileEntryController implements the CRUD actions for FileEntry model.
 */
class FileEntryController extends Controller
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
										'actions' => ['site/login', 'site/error'],
										'allow' => true,
								],
								[
										'actions' => ['index','view','update','delete','gajax'],
										'allow' => true,
										'roles' => ['@'],
								],
						],
				],
				
				'verbs' => [
						'class' => VerbFilter::className(),
						'actions' => [
								'delete' => ['POST'],
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
	 * Lists all FileEntry models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new FileEntrySearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single FileEntry model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		return $this->render('view', [
				'model' => $this->findModel($id),
		]);
	}

	/**
	 * Updates an existing FileEntry model.
	 * Adds new entries in FileEntryCategory if needed.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * deletes existing image under gifURL
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		if ($model->load(Yii::$app->request->post())) {
			$fileID = $model->fileEntryId;
			$oldFE = FileEntry::find()->where(["fileEntryID"=>$fileID])->one();
			if ($oldFE->gifURL != $model->gifURL && $oldFE->gifURL != ""){
				$path = Yii::getAlias('@frontend') . '/web/images/uploads/' . $oldFE->gifURL;
				unlink($path);
			}
			
			
			if($model->save()){
				Yii::info($model->categories);
				$cateogriesArray = \preg_split("/[\s,]+/",$model->categories);
				$fileID = $model->fileEntryId;
				$fileEntries = FileEntryCategory::find()->where(["fileEntryID"=>$fileID])->all();
				foreach ($fileEntries as &$fe){
					$fe->delete();
				}
				
				foreach ($cateogriesArray as &$catId){
					if ($catId !== " " && $catId !== "" && $catId != 0){
						$fileEntryFileCategory = new FileEntryCategory();
						$fileEntryFileCategory->categoryID = $catId;
						$fileEntryFileCategory->fileEntryID = $fileID;
						$fileEntryFileCategory->save();
					}
				}
			}
			return $this->redirect(['view', 'id' => $model->fileEntryId]);
		} else {
			return $this->render('update', [
					'model' => $model,
			]);
		}
	}

	/**
	 * Deletes an existing FileEntry model.
	 * Deletes entries of FileEntryCategory if needed.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	
	{
		$model = $this->findModel($id);
		 
		$fileEntryCatModel = FileEntryCategory::find()->where(['fileEntryID'=>$id])->all();
		foreach ($fileEntryCatModel as &$fecM){
			$fecM->delete();
		}
		
		$path = Yii::getAlias('@frontend') . '/web/images/uploads/' . $model->fileURL;
		unlink($path);
		if ($model->gifURL != '' && $model->gifURL != null){
			$path = Yii::getAlias('@frontend') . '/web/images/uploads/' . $model->gifURL;
			unlink($path);
		}

		
		$this->findModel($id)->delete();
	

		return $this->redirect(['index']);
	}

	/**
	 * Finds the FileEntry model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return FileEntry the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = FileEntry::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
	
	public function actionGajax()
	{
		if (Yii::$app->request->isPost){
			if (isset($_POST['imgBase64'])){
				$base64Data = $_POST['imgBase64'];
				$filePath =  $_POST['filePath'];
				 
				$data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Data));
				$path = Yii::getAlias('@frontend') . '/web/images/uploads/' . $filePath;
				 
				file_put_contents($path, $data);
				$test = "Ajax succedded";
			}
			else{
				$test = "Ajax failed";
			}
			return \yii\helpers\Json::encode($test);
		}
	}
}
?>
