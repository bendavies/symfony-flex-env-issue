<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnimalController extends AbstractController
{
    /**
     * @var string
     */
    private $animal;

    public function __construct(string $animal)
    {
        $this->animal = $animal;
    }

    /**
     * @Route("/animal", name="animal")
     */
    public function index(): Response
    {
        return new Response($this->animal);
    }
}
