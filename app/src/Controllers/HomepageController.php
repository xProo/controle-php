<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Database\DbConnexion;

class HomepageController extends AbstractController
{
    public function process(Request $request): Response
    {
        return $this->homepage();
    }

    private function homepage()
    {
        $db = (new DbConnexion())->execute();

        function getPage(): int
        {
            return $_GET['page'] ?? 1;
        }

        function getLimit(): int
        {
            return $_GET['limit'] ?? 10;
        }


        function getPostsCount(): int
        {
            $sql = "SELECT COUNT(*) FROM posts";
            $stmt = getDbConnexion()->query($sql);
            $count = $stmt->fetchColumn();

            return $count;
        }

        function getPagination(): array
        {
            $postsCount = getPostsCount();
            $postsPerPage = getLimit();
            $pagesCount = ceil($postsCount / $postsPerPage);

            return [
                'pagesCount' => $pagesCount,
                'currentPage' => getPage(),
            ];
        }

        function getPosts(): array
        {
            $db = (new DbConnexion())->execute();

            $currentPage = getPage();
            $postsPerPage = getLimit();
            $offset = ($currentPage - 1) * $postsPerPage;

            $sql = "SELECT posts.id, posts.title, posts.created_at, users.name, users.id as user_id
            FROM posts 
            INNER JOIN users ON posts.user_id = users.id
            ORDER BY posts.created_at DESC
            LIMIT 10
            OFFSET $offset;
            ";
            $stmt = $db->query($sql);
            $posts = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            return $posts;
        }

        $getPage = getPage();
        $getPosts = getPosts();

        return new Response(json_encode($getPosts), 200, ['Content-Type' => 'application/json']);
    }
}
