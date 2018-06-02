<?php

namespace App\Controller;

use App\Repository\SprintRepository;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ApiController
{
    /**
     * @Route("/sprints")
     */
    public function getSprints(SprintRepository $sprintRepository)
    {
        $response = new Response($sprintRepository->get('/board/2/sprint'));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/issue/{keyOrId}")
     */
    public function getIssue(SprintRepository $sprintRepository, string $keyOrId)
    {
        $response = new Response($sprintRepository->get("/issue/{$keyOrId}?expand=changelog"));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/board/issues")
     */
    public function getBoardIssues(SprintRepository $sprintRepository)
    {
        $response = new Response($sprintRepository->get('/board/2/issue'));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/search")
     */
    public function searchIssues(SprintRepository $sprintRepository)
    {
        $response = new Response($sprintRepository->get('/search'));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function getIssuesForSprint(SprintRepository $sprintRepository)
    {
        $response = new Response($sprintRepository->get('/board/2/sprint/'));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}