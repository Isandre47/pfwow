<?php
/**
 * Copyright (c) 2020.
 * Created by PhpStorm.
 * User: Isandre47
 * Date: 31/05/2020 19:33
 */

namespace App\Controller\Character;

use App\Repository\Character\ClasseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ClasseController extends AbstractController
{
    /**
     * @Route("/blizzard/classe", name="classe_index")
     */
    public function index(ClasseRepository $classeRepository)
    {
        return $this->render('admin/blizzard/classe/index.html.twig', [
            'classes' => $classeRepository->findAll(),
        ]);
    }
}
