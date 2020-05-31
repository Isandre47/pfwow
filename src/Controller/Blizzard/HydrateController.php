<?php

namespace App\Controller\Blizzard;

use App\Entity\Blizzard\Classe;
use App\Entity\Blizzard\Realm;
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
        $em->getRepository(Realm::class)->truncate('realm');
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

    /**
     * @Route("/class", name="hydrate_class")
     */
    public function class(Blizzard $blizzard)
    {
        $em = $this->getDoctrine()->getManager();
        $em->getRepository(Realm::class)->truncate('classe');
        $url = Blizzard::HOST_EU.'/data/wow/playable-class/index?namespace=static-eu&locale=fr_FR';
        $response = $blizzard->connection()->get($url)->getBody()->getContents();
        $result = json_decode($response);
        foreach ($result->classes as $item) {
            $classe = new Classe();
            $classe->setName($item->name);
            $em->persist($classe);
            $em->flush();
        }
        return $this->redirectToRoute('classe_index');
    }
}