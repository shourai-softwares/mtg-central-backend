<?php

namespace App\Controller;

use App\Entity\Card;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CardsController extends AbstractController
{
    /**
     * @FOSRest\Get("/cards", name="cards")
     */
    public function index(Request $request)
    {
        $page = $request->get('page', '1');
        $pageSize = $request->get('pageSize', 10);

        if ($pageSize > 100) {
            $pageSize = 100;
        }

        $cards = $this->getDoctrine()->getRepository(Card::class)->findPage($page, $pageSize);

        return View::create($cards, Response::HTTP_CREATED, []);
    }
}
