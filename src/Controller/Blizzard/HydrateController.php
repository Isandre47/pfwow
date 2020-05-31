<?php

namespace App\Controller\Blizzard;

use App\Entity\Realm;
use App\Services\Blizzard;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HydrateController
 * @package App\Controller\Blizzard
 * @Route("/hydrate")
 */
class HydrateController extends AbstractController
{
    /**
     * @Route("/realm", name="hydrate_realm")
     */
    public function realm(Blizzard $blizzard)
    {
        $em = $this->getDoctrine()->getManager();
        $em->getRepository(Realm::class)->truncate();
        $url = Blizzard::HOST_EU.'/data/wow/realm/index?namespace=dynamic-eu&locale=fr_FR';
        $response = $blizzard->connection()->get($url)->getBody()->getContents();
        $result = json_decode($response);
        foreach ($result->realms as $item) {
            $realm = new Realm();
            $realm->setHref($item->key->href);
            $realm->setName($item->name);
            $realm->setSlug($item->slug);
            $realm->setRealmId($item->id);
            $em->persist($realm);
            $em->flush();
        }
        return $this->redirectToRoute('realm_index');
    }
}