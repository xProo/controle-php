<?php

namespace App\Comment;

use App\Models\Comment;
use App\Services\Session;

class AddComment {
    private string $content;
    private int $postId;
    private int $userId;

    public function __construct(string $content, int $postId, int $userId) {
        $this->content = $content;
        $this->postId = $postId;
        $this->userId = $userId;
    }

    public function execute(): bool {
        if ($this->validateInput()) {
            $comment = new Comment($this->content, $this->postId, $this->userId);
            return $comment->save(); 
        }
        return false;
    }

    private function validateInput(): bool {
        if (empty($this->content)) {
            throw new \Exception('Le contenu du commentaire est requis');
        }
        return true;
    }
}
