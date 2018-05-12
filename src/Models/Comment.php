<?php

namespace Keggermont\Commentable\Models;

use Illuminate\Database\Eloquent\Model;
use Keggermont\Commentable\Traits\Commentable;

class Comment extends Model {

    use Commentable;

    protected $guarded = ['id'];
    protected $with = ["childrens","author"];
    protected $table = "";

    public function __construct(array $attributes = []) {
        $this->table = config("laravel-commentable.table_name");
        parent::__construct($attributes);
    }

    /**
     * Return the model name
     * @return string
     */
    public function commentableModel() {
        return config('laravel-commentable.model');
    }

    /**
     * Return childrens
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function childrens() {
        return $this->morphMany($this->commentableModel(), 'commentable');
    }

    /**
     * @return mixed
     */
    public function commentable() {
        return $this->morphTo();
    }

    /**
     * @return mixed
     */
    public function author() {
        return $this->morphTo('author');
    }

    /**
     * @param Model $commentable
     * @param $data
     * @param Model $author
     *
     * @return static
     */
    public function createComment(Model $commentable, $data, Model $author) {
        return $commentable->comments()->create(array_merge($data, ['author_id' => $author->getAuthIdentifier(), 'author_type' => get_class($author),]));
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function updateComment($id, $data) {

        $obj = static::find($id);
        if($obj->author->id != \Auth::user()->id && (isset(\Auth::user()->is_admin) && !\Auth::user()->is_admin)) {
            // The current logged user was not the author, and it's not an admin !
            return false;
        }

        return (bool)$obj->update($data);
    }

    /**
     * @param $id
     * @return boolean
     */
    public function deleteComment($id) {

        $obj = static::find($id);
        if($obj->author->id != \Auth::user()->id && (isset(\Auth::user()->is_admin) && !\Auth::user()->is_admin)) {
            // The current logged user was not the author, and it's not an admin !
            return false;
        }
        return (bool)$obj->delete();
    }
}
