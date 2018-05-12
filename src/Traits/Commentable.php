<?php
namespace Keggermont\Commentable\Traits;


trait Commentable {

    public function commentableModel()
    {
        return config('laravel-commentable.model');
    }

    public function comments()
    {
        return $this->morphMany($this->commentableModel(), 'commentable');
    }


    public function comment($data, $author)
    {
        $commentableModel = $this->commentableModel();
        $comment = (new $commentableModel())->createComment($this, $data, $author);
        return $comment;
    }
    /**
     * @param $id
     * @param $data
     *
     * @return mixed
     */
    public function updateComment($id, $data)
    {
        $commentableModel = $this->commentableModel();
        $comment = (new $commentableModel())->updateComment($id, $data);

        return $comment;
    }
    /**
     * @param $id
     *
     * @return mixed
     */
    public function deleteComment($id)
    {
        $commentableModel = $this->commentableModel();
        return (bool) (new $commentableModel())->deleteComment($id);
    }
    /**
     * @return mixed
     */
    public function commentCount()
    {
        return $this->comments->count();
    }




}