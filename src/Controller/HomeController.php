<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"GET"})
     */
    public function index()
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://www.mamytwink.com/feed.xml',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => 'UTF-8',
        ]);

        $data = curl_exec($curl);
        curl_close($curl);
        $xml = simplexml_load_string($data);
        $item = $xml->channel->item;

        return $this->render('home/index.html.twig', [
            'news' => $xml,
            'item' => $item,
        ]);
    }
}
