<?php

namespace App\Controller;


use App\Entity\Product;
use App\Entity\Vote;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class VoteController extends FOSRestController
{

    /**
     * @Rest\Post("/vote")
     */
    public function makeVote(Request $request)
    {
        $data = $request->request->all();

        $vote = new Vote();

        $vote->setVote($data['vote']);
        $vote->setComment($data['comment']);

        $server = $request->server->all();

        if (!empty($server['HTTP_CLIENT_IP'])) {
            $ip = $server['HTTP_CLIENT_IP'];
        } elseif (!empty($server['HTTP_X_FORWARDED_FOR'])) {
            $ip = $server['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $server['REMOTE_ADDR'];
        }

        $vote->setIp($ip);

        $this->getDoctrine()->getManager()->persist($vote);
        $this->getDoctrine()->getManager()->flush();

        return new Response($this->get('jms_serializer')->serialize($vote->getId(), 'json'), Response::HTTP_OK);
    }

    /**
     * @Rest\Put("/vote")
     */
    public function getDiscount(Request $request)
    {
        //$data = $request->request->all();
        $data = json_decode($request->getContent(), true);

        $vote = $this->getDoctrine()->getRepository(Vote::class)->find($data['id']);

        $randomKey = $this->generateRamdomString();
        /** @var Vote $vote */
        $vote->setEmail($data['email']);
        $vote->setKey($randomKey);
        $vote->setUpdatedOn();

        $this->getDoctrine()->getManager()->persist($vote);
        $this->getDoctrine()->getManager()->flush();

        return new Response($this->get('jms_serializer')->serialize($vote->getKey(), 'json'), Response::HTTP_OK);
    }

    private function generateRamdomString($length = 4)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $data = new \DateTime();
        return $randomString . $data->getTimestamp();
    }


    /**
     * @Rest\Post("medications/search")
     */
    public function searchForMedicament(Request $request){

        $data = $request->request->all();

        $string = $data['string'];

        $medicaments = $this->getDoctrine()->getRepository(Product::class)->searchByString($string);

        $responseJson = $this->get('jms_serializer')->serialize(
            $medicaments,
            'json'

        );

        return new Response($responseJson, Response::HTTP_OK);
    }

}