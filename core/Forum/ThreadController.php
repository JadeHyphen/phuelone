<?php

namespace Core\Forum;

use Core\Http\Request;
use Core\Http\Response;

/**
 * Class ThreadController
 *
 * Handles HTTP requests for threads.
 */
class ThreadController
{
    public function index(Request $request): Response
    {
        // Logic to list all threads
        return new Response('List of threads', 200);
    }

    public function show(Request $request, int $id): Response
    {
        // Logic to show a specific thread
        return new Response("Thread details for ID: $id", 200);
    }

    public function create(Request $request): Response
    {
        // Logic to create a new thread
        return new Response('Thread created', 201);
    }

    public function update(Request $request, int $id): Response
    {
        // Logic to update a thread
        return new Response("Thread ID $id updated", 200);
    }

    public function delete(Request $request, int $id): Response
    {
        // Logic to delete a thread
        return new Response("Thread ID $id deleted", 200);
    }
}

?>
