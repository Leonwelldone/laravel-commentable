# Laravel Commentable Trait

## Installation

Use [Composer](https://getcomposer.org/) :

``` bash
$ composer require k-eggermont/laravel-commentable
```

Publish the vendor assets:

```
php artisan vendor:publish --provider="Keggermont\Commentable\CommentableServiceProvider" 
php artisan migrate
```

## Configuration

You can configure the package on /config/laravel-commentable.php

## Usage

### Api

By default, the api is accessible at /api/comments/. You have 3 routes :
* GET /api/comments/{type}/{id} : Get comments
* POST /api/comments/{type}/{id} : Create a new comment (data required : title and body)
* DELETE /api/comments/{comment_id} : Delete a comment (if you are the owner, OR if you are an admin (User->is_admin = true))


### Include trait for your model
``` php
<?php

namespace App;

use Keggermont\Commentable\Traits\Commentable;
use Illuminate\Database\Eloquent\Model;

class MyModel extends Model
{
    use Commentable;
}
```

### Create a comment from controller / model
``` php
$object = MyModel::first();

$comment = $post->createComment([
    'title' => 'Some title',
    'body' => 'Some body',
], Auth::user());

dd($comment);
```

### Update a comment
``` php
$idComment = 1;
$comment = $post->updateComment($idComment, [
    'title' => 'new title',
    'body' => 'new body',
]);
```

### Delete a comment
``` php
// From trait :
$post->deleteComment(1);

// From object 'Comment'
$comment->delete();
```


## License

[MIT](LICENSE)