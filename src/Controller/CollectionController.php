<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\UserCard;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CollectionController extends AbstractController
{
    /**
     * @FOSRest\Get("/collection/cards/", name="get_collection_cards")
     */
    public function getCollectionAction()
    {
        $d = $this->getDoctrine();

        $collection = $d->getRepository(UserCard::class)->findBy(['user' => $this->getUser()]);

        return View::create($collection);
    }
    /**
     * @FOSRest\Post("/collection/cards/add", name="add_cards")
     */
    public function addToCollectionAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $cardRepo = $em->getRepository(Card::class);
        $userCardRepo = $em->getRepository(UserCard::class);

        $user = $this->getUser();
        $cards = json_decode($request->getContent());

        foreach ($cards as $cardId => $quantity) {
            $card =  $cardRepo->find($cardId);
            $userCard = $userCardRepo->findInUserCollection($user, $card);

            if (is_null($userCard)) {
                $userCard = (new UserCard())
                    ->setUser($user)
                    ->setCard($card)
                ;
                $em->persist($userCard);
            }

            $userCard->addToQuantity($quantity);
        }

        $em->flush();

        return View::create('Ok');
    }
}
