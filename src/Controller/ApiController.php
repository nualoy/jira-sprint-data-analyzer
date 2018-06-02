<?php

namespace App\Controller;

use App\Repository\BoardRepository;
use App\Repository\SprintRepository;
use JMS\Serializer\SerializerBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiController
{
    /**
     * @Route("/board/{id}")
     * @Method({"GET"})
     */
    public function getBoard(BoardRepository $boardRepository, int $id)
    {
        if ($board = $boardRepository->findWithSprintsById($id)) {
            $serializer = SerializerBuilder::create()->build();
            $response = new Response($serializer->serialize($board, 'json'));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        throw new NotFoundHttpException('Team board not found');
    }

    /**
     * @Route("/sprint/{id}")
     * @Method({"GET"})
     */
    public function getSprint(SprintRepository $sprintRepository, int $id)
    {
        if ($sprint = $sprintRepository->findById($id)) {
            $serializer = SerializerBuilder::create()->build();
            $response = new Response($serializer->serialize($sprint, 'json'));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        throw new NotFoundHttpException('Sprint not found');
    }
}