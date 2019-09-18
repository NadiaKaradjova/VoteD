<?php

namespace App\Controller;


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
    public function createCampaign(Request $request)
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

        return new Response("OK");
    }

}