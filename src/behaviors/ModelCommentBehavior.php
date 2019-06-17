<?php

namespace itimum\modelComment\behaviors;

use itimum\modelComment\models\ModelComment;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class ModelCommentBehavior extends Behavior {

    protected $_commentClass = ModelComment::class;

    protected $_commentItems = [];

    /**
     * TODO
     * @var int
     */
    public $lastCommentId;

    /**
     * @param \yii\base\Component $owner
     */
    public function attach($owner) {

        parent::attach($owner);

        /*
         * TODO
         * В методе on добавлен четвертый параметр false чтобы данный обработчик не был последник в порядке срабатывания
         */
        if ($this->owner instanceof ActiveRecord) {
            $this->owner->on(ActiveRecord::EVENT_AFTER_UPDATE, [$this, 'addCommentRecord'], null, false);
            $this->owner->on(ActiveRecord::EVENT_AFTER_INSERT, [$this, 'addCommentRecord'], null, false);
            $this->owner->on(ActiveRecord::EVENT_AFTER_DELETE, [$this, 'addCommentRecord'], null, false);
        }

    }

    public function addCommentRecord() {
        $this->_saveCommentItems();
    }

    /**
     * Создает экземпляр класса модели комментария и добавляет его в массив комментариев
     *
     * @param $comment
     */
    public function addComment($comment){
        $model = new $this->_commentClass;
        $model->created_by = \Yii::$app->user->id;
        $model->entity = get_class($this->owner);
        $model->entity_id = $this->owner->id;
        $model->comment = $comment;
        $this->_commentItems[] = $model;
    }

    /**
     * Возвращает массив комментариев
     *
     * @return array
     */
    public function getCommentItems() {
        return $this->_commentItems;
    }

    /**
     * Сохраняет комментарии в базе данных
     */
    protected function _saveCommentItems() {
        foreach ($this->_commentItems as $comment) {
            $comment->save();
        }
    }



    public function getComments(){
        return $this->owner->hasMany($this->_commentClass, [
            'entity_id' => 'id',
        ])->where(['entity' => get_class($this->owner)]);
    }

    public function getLastComment(){
        return $this->getComments()->orderBy('created_at DESC')->one();
    }
}