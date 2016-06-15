<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "book".
 *
 * @property integer $id
 * @property string $title
 * @property string $author
 * @property integer $category_id
 * @property string $photo
 * @property string $file
 * @property string $description
 *
 * @property Category $category
 */
class Book extends \yii\db\ActiveRecord
{

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    const EVENT_DOWNLOAD = 'download';

    public function init()
    {
        $this->on(self::EVENT_DOWNLOAD, function ($event){
            Yii::$app->mailer->compose()
                ->setFrom('library@test.ru')
                ->setTo(Yii::$app->params['adminEmail'])
                ->setSubject('Скачана книга - '. $event->sender->title)
                ->send();
        });
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'book';
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['title', 'author', 'category_id', 'description', 'photo', 'file'];
        $scenarios[self::SCENARIO_UPDATE] = ['title', 'author', 'category_id', 'description'];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'author', 'category_id', 'description'], 'required'],
            [['category_id'], 'integer'],
            [['description'], 'string'],
            [['title', 'author'], 'string', 'max' => 255],
            [['title'], 'unique'],
            [['photo'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, gif, tiff'],
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'txt, doc, docx, pdf'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'author' => 'Автор',
            'category_id' => 'Категория',
            'photo' => 'Фотография',
            'file' => 'Файл',
            'description' => 'Описание',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @inheritdoc
     * @return BookQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BookQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        $this->deleteFiles();
    }

    /**
     * Получение пути к электронному образу книги.
     * @return string
     */
    public function getFilePath()
    {
        return 'uploads/files/'.$this->file;
    }

    /**
     * Получение пути к фотографии книги.
     * @return string
     */
    public function getPhotoPath()
    {
        return 'uploads/photos/'.$this->photo;
    }

    /**
     * Удаление электронного образа книги и фотографии.
     */
    public function deleteFiles()
    {
        if(file_exists($this->getFilePath())){
            unlink($this->getFilePath());
        }
        if(file_exists($this)){
            unlink($this->getPhotoPath());
        }
    }

    /**
     * Функция загрузки файлов книги при изменении. Если файл загружен и он не пуст,
     * то происходит удаление старого файла и происходит сохранение нового.
     *
     * @param $attribute string атрибут модели Book
     * @param $uploadPath string путь загрузки нового файла
     */
    public function uploadFileOnUpdate($attribute, $uploadPath)
    {
        $newUploadedFile = UploadedFile::getInstance($this, $attribute);
        if($newUploadedFile && $newUploadedFile->tempName){
            if(file_exists($oldFile = $uploadPath.$this->{$attribute})){
                unlink($oldFile);
            }
            $newUploadedFile->saveAs($uploadPath . $newUploadedFile->baseName. '.' . $newUploadedFile->extension);
            $this->{$attribute} = $newUploadedFile->baseName. '.' . $newUploadedFile->extension;
        }
    }
}
