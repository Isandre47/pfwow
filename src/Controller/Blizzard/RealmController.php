<?php

namespace App\Controller\Blizzard;

use App\Repository\RealmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RealmController extends AbstractController
{
    /**
     * @Route("/blizzard/realm", name="realm_index")
     */
    public function index(RealmRepository $realmRepository)
    {
        return $this->render('admin/blizzard/realm/index.html.twig', [
            'realms' => $realmRepository->findAll(),
        ]);
    }
}
