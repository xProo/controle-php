<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Database\DbConnexion;

class HomepageController extends AbstractController
{
    private const DEFAULT_PAGE_SIZE = 10;
    private const DEFAULT_PAGE = 1;

    public function process(Request $request): Response
    {
        return $this->handleHomepage();
    }

    private function handleHomepage(): Response
    {
        $articles = $this->fetchArticles();
        return new Response(
            json_encode($articles), 
            200, 
            ['Content-Type' => 'application/json']
        );
    }

    private function fetchArticles(): array
    {
        $pagination = $this->calculatePagination();
        $offset = ($pagination['currentPage'] - 1) * $this->getPageSize();

        $connection = $this->getDatabaseConnection();
        $query = "
            SELECT 
                p.id,
                p.title,
                p.created_at,
                u.name,
                u.id as user_id
            FROM posts p
            INNER JOIN users u ON p.user_id = u.id
            ORDER BY p.created_at DESC
            LIMIT :limit
            OFFSET :offset
        ";

        $stmt = $connection->prepare($query);
        $stmt->bindValue(':limit', $this->getPageSize(), \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function calculatePagination(): array
    {
        $totalArticles = $this->getTotalArticlesCount();
        $pageSize = $this->getPageSize();

        return [
            'pagesCount' => ceil($totalArticles / $pageSize),
            'currentPage' => $this->getCurrentPage(),
        ];
    }

    private function getTotalArticlesCount(): int
    {
        $connection = $this->getDatabaseConnection();
        $query = "SELECT COUNT(*) FROM posts";
        return (int) $connection->query($query)->fetchColumn();
    }

    private function getCurrentPage(): int
    {
        return isset($_GET['page']) ? (int) $_GET['page'] : self::DEFAULT_PAGE;
    }

    private function getPageSize(): int
    {
        return isset($_GET['limit']) ? (int) $_GET['limit'] : self::DEFAULT_PAGE_SIZE;
    }

    private function getDatabaseConnection(): \PDO
    {
        return (new DbConnexion())->execute();
    }
}
