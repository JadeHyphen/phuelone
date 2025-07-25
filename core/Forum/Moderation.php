<?php

namespace Core\Forum;

use Core\Forum\Thread;
use Core\Forum\Post;

/**
 * Class Moderation
 *
 * Handles moderation actions for threads and posts.
 */
class Moderation
{
    public function lockThread(Thread $thread): void
    {
        $thread->lock();
    }

    public function deleteThread(Thread $thread): void
    {
        $thread->delete();
    }

    public function deletePost(Post $post): void
    {
        $post->delete();
    }
}

?>
