<?php
/**
 * Copyright (c) 2020.
 * Created by PhpStorm.
 * User: Isandre47
 * Date: 31/05/2020 19:38
 */

namespace App\Controller\Blizzard;

use App\Repository\Blizzard\ClasseRepository;
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
