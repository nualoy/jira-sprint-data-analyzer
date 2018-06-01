<?php

namespace App\Controller;

use App\Service\JiraApiClient;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ApiController
{
    /**
     * @Route("/sprints")
     */
    public function getSprints(JiraApiClient $apiClient)
    {
        $response = new Response($apiClient->get('/board/2/sprint'));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/issue/{keyOrId}")
     */
    public function getIssue(JiraApiClient $apiClient, string $keyOrId)
    {
        $response = new Response($apiClient->get("/issue/{$keyOrId}?expand=changelog"));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/board/issues")
     */
    public function getBoardIssues(JiraApiClient $apiClient)
    {
        $response = new Response($apiClient->get('/board/2/issue'));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/search")
     */
    public function searchIssues(JiraApiClient $apiClient)
    {
        $response = new Response($apiClient->get('/search'));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function getIssuesForSprint(JiraApiClient $apiClient)
    {
        $response = new Response($apiClient->get('/board/2/sprint/'));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}