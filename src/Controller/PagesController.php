<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $response = "
            <html>
        <body>
        <div style='text-align: center;'>
        <h1>This site is under construction</h1>
        <img src='/images/under-construction.gif' alt='under construction'>
</div>
</body>    
</html>
        ";

        return new Response($response);
    }
}
