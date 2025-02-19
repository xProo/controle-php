<?php

namespace App\Entities;

use App\Entities\AbstractEntity;
use App\Database\DbConnexion;

class PostsEntity extends AbstractEntity
{
    private int $id;
    private string $title;
    private string $content;
    private int $user_id;
    private string $created_at;

    public function __construct()
    {
        $this->created_at = date('Y-m-d H:i:s');
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $this->RemoveSpecialChar($title);
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function getPage(): int
    {
        return $_GET['page'] ?? 1;
    }

    public function getLimit(): int
    {
        return $_GET['limit'] ?? 10;
    }


    public function getPostsCount(): int
    {
        $connexion = getDbConnexion();
        $query = "SELECT COUNT(id) as total FROM posts";
        $result = $connexion->query($query);
        return (int) $result->fetchColumn();
    }

    public function getPagination(): array
    {
        $total = $this->getPostsCount();
        $items_per_page = $this->getLimit();
        $total_pages = ceil($total / $items_per_page);

        return [
            'pagesCount' => $total_pages,
            'currentPage' => $this->getPage(),
        ];
    }

    public function getPosts(): array
    {
        $connexion = (new DbConnexion())->execute();
        $page = $this->getPage();
        $items = $this->getLimit();
        $skip = ($page - 1) * $items;

        $query = "SELECT p.id, p.title, p.created_at, u.name, u.id as user_id
            FROM posts p
            INNER JOIN users u ON p.user_id = u.id
            ORDER BY p.created_at DESC
            LIMIT 10
            OFFSET $skip;
            ";
        $result = $connexion->query($query);
        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }
}