<?php

namespace App\Controller;

use App\Entity\Card;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class CardsController extends AbstractController
{
    /**
     * @FOSRest\Get("/cards", name="cards")
     */
    public function index()
    {
        $card = $this->getDoctrine()->getRepository(Card::class)->findBy(['type'=>'enchantment'], null, 4);

        return View::create($card, Response::HTTP_CREATED, []);
    }
}
