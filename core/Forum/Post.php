<?php

namespace Core\Forum;

use Core\Auth\User;

/**
 * Class Post
 *
 * Represents a post in a forum thread.
 */
class Post
{
    protected int $id;
    protected string $content;
    protected User $author;

    public function __construct(array $attributes = [])
    {
        $this->id = $attributes['id'] ?? 0;
        $this->content = $attributes['content'] ?? '';
        $this->author = $attributes['author'] ?? new User([]);
    }

    public function delete(): void
    {
        // Logic to delete the post
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'author' => $this->author->toArray(),
        ];
    }
}

?>
