<?php

namespace Core\Forum;

use Core\Auth\User;
use Core\Forum\Post;

/**
 * Class Thread
 *
 * Represents a forum thread.
 */
class Thread
{
    protected int $id;
    protected string $title;
    protected string $content;
    protected User $author;
    protected array $posts = [];

    public function __construct(array $attributes = [])
    {
        $this->id = $attributes['id'] ?? 0;
        $this->title = $attributes['title'] ?? '';
        $this->content = $attributes['content'] ?? '';
        $this->author = $attributes['author'] ?? new User([]);
        $this->posts = $attributes['posts'] ?? [];
    }

    public function addPost(Post $post): void
    {
        $this->posts[] = $post;
    }

    public function lock(): void
    {
        // Logic to lock the thread
    }

    public function delete(): void
    {
        // Logic to delete the thread
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'author' => $this->author->toArray(),
            'posts' => array_map(fn($post) => $post->toArray(), $this->posts),
        ];
    }
}

?>
