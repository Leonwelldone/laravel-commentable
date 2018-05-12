<?php
//declare(strict_types=1);

return [

    // Model class
    'model' => \Keggermont\Commentable\Models\Comment::class,

    // Name of the table
    'table_name' => 'keg_comments',

    // Allow to comment a comment (sub awnser)
    'can_comment_a_comment'  => false,

    // Enable the API
    'enable_api' => true,

    // Route API
    'route_api' => '/api/comments',

    // For API => paginate value
    'paginate' => 15,

    // For API => type for create a comment
    'allowType' => [
        "comment" => \Keggermont\Commentable\Models\Comment::class,
        "user" => \App\User::class
    ],

    // You can add middlewares for API routes (example : auth:api
    'api_middleware_get' => [],
    'api_middleware_create' => [],
    'api_middleware_delete' => [],

];