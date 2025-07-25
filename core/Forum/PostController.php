<?php

namespace Core\Forum;

use Core\Http\Request;
use Core\Http\Response;

/**
 * Class PostController
 *
 * Handles HTTP requests for posts.
 */
class PostController
{
    public function create(Request $request): Response
    {
        // Logic to create a new post
        return new Response('Post created', 201);
    }

    public function update(Request $request, int $id): Response
    {
        // Logic to update a post
        return new Response("Post ID $id updated", 200);
    }

    public function delete(Request $request, int $id): Response
    {
        // Logic to delete a post
        return new Response("Post ID $id deleted", 200);
    }
}

?>
