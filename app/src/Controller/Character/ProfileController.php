<?php
/**
 * Copyright (c) 2020.
 * Created by PhpStorm.
 * User: Isandre47
 * Date: 31/05/2020 20:16
 */

namespace App\Controller\Character;

use App\Entity\Blizzard\Classe;
use App\Entity\Character\Equipment;
use App\Entity\Character\Profile;
use App\Entity\User;
use App\Form\Character\ProfileType;
use App\Repository\Character\ProfileRepository;
use App\Services\Blizzard;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProfileController
 * @package App\Controller\Character
 * @Route("/character/profile")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/index", name="character_index")
     */
    public function index(ProfileRepository $profileRepository)
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->render('user/character/profile/index.html.twig', [
                'profiles' => $profileRepository->findAll(),
            ]);
        }

        return $this->render('user/character/profile/index.html.twig', [
            'profiles' => $profileRepository->findByUser($this->getUser()->getId()),
        ]);
    }

    /**
     * @Route("/new", name="character_new")
     */
    public function new(Request $request, Blizzard $blizzard)
    {
        $profile = new Profile();
        $form = $this->createForm(ProfileType::class, $profile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $equipment = new Equipment();
            $realm = $profile->getRealm()->getSlug();
            $pseudo = strtolower($profile->getName());
            $em = $this->getDoctrine()->getManager();
            $url = Blizzard::HOST_EU.'/profile/wow/character/'. $realm .'/'. $pseudo . '?namespace=profile-eu&locale=fr_FR';
            $response = $blizzard->connection()->get($url)->getBody()->getContents();
            $response = json_decode($response);
            $profile->setProfile($response);
            $profile->setBlizzardCharacterId($response->id);
            $profile->setUser($this->getUser());
            $classe = $em->getRepository(Classe::class)->find($response->character_class->id);
            $profile->setCharacterClass($classe);
            $date = new \DateTime();
            $date->setTimestamp($response->last_login_timestamp / 1000 );
            $profile->setLastLoginTimestamp($date);
            $profile->setMedia($response->media->href);
            $em->persist($profile);
            $em->flush();
            $idCharacter =$profile->getId();
            $profileId = $em->getRepository(Profile::class)->find($idCharacter);

            $url = $response->equipment->href . '&locale=fr_FR';
            $response = $blizzard->connection()->get($url)->getBody()->getContents();
            $response = json_decode($response);
            $equipment->setEquipment($response->{'equipped_items'});
            $equipment->setProfil($profileId);
            $em->persist($equipment);
            $em->flush();

            return $this->redirectToRoute('character_index');
        }

        return $this->render('user/character/profile/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show/{id}", name="profile_show")
     */
    public function show(Profile $profile)
    {
        return $this->render('user/character/profile/show.html.twig', [
            'profile' =>    $profile,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="profile_delete")
     */
    public function delete(Request $request, Profile $profile)
    {
        if ($this->isCsrfTokenValid('delete'.$profile->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($profile);
            $entityManager->flush();
        }
        return $this->redirectToRoute('character_index');
    }

    /**
     * @Route("/update/{id}", name="profile_update")
     */
    public function update(Profile $profile, Blizzard $blizzard)
    {
        $em = $this->getDoctrine()->getManager();
        $url = Blizzard::HOST_EU.'/profile/wow/character/'. $profile->getRealm()->getSlug() .'/'. strtolower($profile->getName()) . '?namespace=profile-eu&locale=fr_FR';
        $response = $blizzard->connection()->get($url)->getBody()->getContents();
        $response = json_decode($response);
        $equipment = $profile->getEquipment();
        $profile->setProfile($response);

        $classe = $em->getRepository(Classe::class)->find($response->character_class->id);
        $profile->setCharacterClass($classe);
        $profile->setBlizzardCharacterId($response->id);
        $date = new \DateTime();
        $date->setTimestamp($response->last_login_timestamp / 1000 );
        $profile->setLastLoginTimestamp($date);
        $profile->setMedia($response->media->href);

        $url = $response->equipment->href . '&locale=fr_FR';
        $response = $blizzard->connection()->get($url)->getBody()->getContents();
        $response = json_decode($response);
        $equipment->setEquipment($response->{'equipped_items'});
        $profile->setEquipment($equipment);
        $em->persist($equipment);
        $em->persist($profile);
        $em->flush();

        return $this->redirectToRoute('character_index');
    }
}
