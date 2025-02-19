<?php

namespace App\Blog;

use App\Models\Post;
use App\Services\Session;

class CreatePost {
    private string $title;
    private string $content;
    private int $userId;

    public function __construct(string $title, string $content, int $userId) {
        $this->title = $title;
        $this->content = $content;
        $this->userId = $userId;
    }

    public function execute(): bool {
        if ($this->validateInput()) {
            $post = new Post($this->title, $this->content, $this->userId);
            return $post->save(); // Supposons que la méthode save() gère l'insertion dans la base de données
        }
        return false;
    }

    private function validateInput(): bool {
        if (empty($this->title) || empty($this->content)) {
            throw new \Exception('Le titre et le contenu sont requis');
        }
        return true;
    }
}
