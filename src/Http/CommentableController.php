<?php
namespace Keggermont\Commentable\Http;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class CommentableController extends Controller {


    /*
     * Récupération des commentaires
     */

    public function get($type,$id) {

        if(!isset(config('laravel-commentable.allowType')[$type])) {
            throw new \Exception($type." was not in the allowType configuration (laravel-commentable.php)");
        }
        $className = config('laravel-commentable.allowType')[$type];
        $className::where("id",$id)->firstOrFail();

        if(strtolower(config('laravel-commentable.defaultOrderDate')) == "desc" || strtolower(config('laravel-commentable.defaultOrderDate')) == "asc"){
            $order = config('laravel-commentable.defaultOrderDate');
        } else {
            $order = "ASC";
        }

        $comments = (config('laravel-commentable.model'))::where("commentable_type",$className)->where("commentable_id",$id)->orderBy("created_at",$order)->with(["childrens","author"])->paginate(config('laravel-commentable.paginate'));

        return array("success" => true, "comments" => $comments);

    }


    /*
     * Création d'un commentaire
     */

    public function post($type,$id, Request $request) {
        if(!\Auth::check()) {
            return response()->setStatusCode(401, 'You are not connected!');
        }

        $this->validate($request, ["title" => "string|required", "body" => "string|required"]);

        if(!isset(config('laravel-commentable.allowType')[$type])) {
            throw new \Exception($type." was not in the allowType configuration (laravel-commentable.php)");
        }
        $className = config('laravel-commentable.allowType')[$type];

        // Verification de l'existance de l'objet concerné
        $obj = $className::where("id",$id)->firstOrFail();

        $comment = $obj->createComment(["title" => request("title"),"body" => request("body")], \Auth::user());

        if($comment) {
            return array("success" => true, "comment" => $comment);
        } else {
            throw new \Exception("An error occured");
        }

    }

    /*
     * Suppression d'un commentaire
     */
    public function delete($id) {
        if(!\Auth::check()) {
            return response()->setStatusCode(401, 'You are not connected!');
        }

        // Verification de l'existance de l'objet concerné
        $comment = (config('laravel-commentable.model'))::where("id",$id)->with("author")->firstOrFail();

        if(($comment->author->id == \Auth::user()->id) OR (isset(\Auth::user()->is_admin) && \Auth::user()->is_admin === true)) {
            $comment->delete();
        } else {
            return response()->setStatusCode(403, 'You are not authorized !');
        }

        return array("success" => true);

    }



}