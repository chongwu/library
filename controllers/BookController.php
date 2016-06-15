<?php

namespace app\controllers;

use app\helpers\StringHelper;
use Yii;
use app\models\Book;
use app\models\BookSearch;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * BookController implements the CRUD actions for Book model.
 */
class BookController extends Controller
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
                        'actions' => ['index', 'view', 'code', 'search', 'download'],
                        'allow' => true,
                        'roles' => ['?']
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ],
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Отображение списка всех книг.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BookSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Отображение выбранной по id книги.
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
     * Отображение страницы администрирования книгами
     * @return mixed
     */
    public function actionAdmin()
    {
        $searchModel = new BookSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('admin', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Добавление новой книги
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Book(['scenario' => Book::SCENARIO_CREATE]);
        if($model->load(Yii::$app->request->post())){
            $model->photo = UploadedFile::getInstance($model, 'photo');
            $model->file = UploadedFile::getInstance($model, 'file');
            if($model->validate()){
                $model->photo->saveAs('uploads/photos/' . $model->photo->baseName.'.'.$model->photo->extension);
                $model->file->saveAs('uploads/files/' . $model->file->baseName.'.'.$model->file->extension);
                $model->save(false);
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Изменение книги с указанным id.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = Book::SCENARIO_UPDATE;
        if ($model->load(Yii::$app->request->post())) {
            $model->uploadFileOnUpdate('photo', 'uploads/photos/');
            $model->uploadFileOnUpdate('file', 'uploads/files/');
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Удаление книги по id.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Поиск книг, начинающихся с определенной буквы.
     * @param $code integer десятичный код буквы в юникод
     * @return mixed
     */
    public function actionCode($code)
    {
        $char = StringHelper::UnicodeCharFromDecimal($code);
        $dataProvider = new ActiveDataProvider([
           'query' => Book::find()->where(['like', 'title', $char.'%', false])
        ]);
        return $this->render('find', [
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Поиск книг по подстроке $book
     * @param $book string
     * @return string
     */
    public function actionSearch($book)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Book::find()->where(['like', 'title', $book])
        ]);
        return $this->render('find', [
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Скачивание книги. При скачивании происходит событие EVENT_DOWNLOAD, при которм на почту администратора отправляется
     * email с информацией о скачанной книге
     * @param $id integer
     * @return $this
     * @throws NotFoundHttpException
     */
    public function actionDownload($id)
    {
        $model = $this->findModel($id);
        $model->trigger(Book::EVENT_DOWNLOAD);
        return Yii::$app->response->sendFile($model->getFilePath(), null);
    }

    /**
     * Массовое изменине категорий у выбранных книг
     * @return \yii\web\Response
     */
    public function actionChangeCategory()
    {
        if(!empty($category = Yii::$app->request->post('category')) && !empty($selection = Yii::$app->request->post('selection'))){
            foreach ($selection as $id){
                $book = Book::findOne($id);
                $book->category_id = $category;
                $book->save(false);
            }
        }
        return $this->redirect('admin');
    }

    /**
     * Поиск книги по id.
     * @param integer $id
     * @return Book the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Book::find()->joinWith(['category'])->where([Book::tableName().'.id' => $id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
