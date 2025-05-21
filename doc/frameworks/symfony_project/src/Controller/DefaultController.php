<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route("/", name:"welcome")]
    public function index(): Response
    {
        return new Response(
            '<html><body><h1>Welcome to Symfony!</h1></body></html>'
        );
    }
    #[Route("/hello/{name}", name:"hello")]
    public function hello_world(string $name = "Please provide a name"): Response
    {
        return new Response(
            "<html><body><h1>Hello '{$name}' from Symfony!</h1></body></html>"
        );
    }
}