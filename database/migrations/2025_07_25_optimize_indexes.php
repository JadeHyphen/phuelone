<?php

use Core\Database\Migration;

class OptimizeIndexes extends Migration
{
    public function up(): void
    {
        $this->execute('CREATE INDEX idx_users_email ON users (email);');
        $this->execute('CREATE INDEX idx_posts_thread_id ON posts (thread_id);');
        $this->execute('CREATE INDEX idx_threads_title ON threads (title);');
    }

    public function down(): void
    {
        $this->execute('DROP INDEX idx_users_email ON users;');
        $this->execute('DROP INDEX idx_posts_thread_id ON posts;');
        $this->execute('DROP INDEX idx_threads_title ON threads;');
    }
}
