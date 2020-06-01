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
        return $this->render('character/profile/index.html.twig', [
            'profiles' => $profileRepository->findAll(),
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
            $profile->setName($response->name);
            $profile->setBlizzardCharacterId($response->id);
            $profile->setGender($response->gender->name);
            $profile->setFaction($response->faction->name);
            $profile->setRace($response->race->name);
            $classe = $em->getRepository(Classe::class)->find($response->character_class->id);
            $profile->setCharacterClass($classe);
            $profile->setActiveSpec($response->active_spec->name);
            $profile->setGuild($response->guild->name);
            $profile->setGender($response->gender->name);
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

        return $this->render('character/profile/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
