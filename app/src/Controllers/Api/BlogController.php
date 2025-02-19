<?php

namespace App\Controllers\Api;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Models\Post;

class BlogController
{
    public function index(Request $request): Response
    {
        $pdo = Post::getDbConnexion();
        $stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
        $posts = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return new Response(
            json_encode($posts),
            200,
            ['Content-Type' => 'application/json']
        );
    }

    public function show(Request $request): Response
    {
        $id = $request->getSlug('id');
        $post = Post::getPostById($id);

        if (!$post) {
            return new Response(
                json_encode(['error' => 'Post not found']),
                404,
                ['Content-Type' => 'application/json']
            );
        }

        return new Response(
            json_encode($post),
            200,
            ['Content-Type' => 'application/json']
        );
    }

    public function create(Request $request): Response
    {
        $data = json_decode($request->getPayload(), true);

        if (!isset($data['title']) || !isset($data['content'])) {
            return new Response(
                json_encode(['error' => 'Title and content are required']),
                400,
                ['Content-Type' => 'application/json']
            );
        }

        $post = new Post($data['title'], $data['content'], $_SESSION['user_id'] ?? 0);
        
        if ($post->save()) {
            return new Response(
                json_encode(['message' => 'Post created successfully']),
                201,
                ['Content-Type' => 'application/json']
            );
        }

        return new Response(
            json_encode(['error' => 'Could not create post']),
            500,
            ['Content-Type' => 'application/json']
        );
    }
}