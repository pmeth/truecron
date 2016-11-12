<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Server;
use AppBundle\Form\ServerType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\Request;

class ServerController extends Controller
{
    /**
     * @Route("/server/", name="add_server")
     * @Route("/server/{id}", name="edit_server")
     * @Template()
     */
    public function addAction(Request $request, Server $server = null)
    {
        if (is_null($server)) {
            $server = Server::make();
        }
        $form = $this->createForm(ServerType::class, $server);
        $form->add('save', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $objectManager = $this->getDoctrine()->getManager();
            $objectManager->persist($server);
            $objectManager->flush();

            return $this->redirectToRoute('servers');
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/servers", name="servers")
     * @Template()
     */
    public function listAction(Request $request)
    {
        // replace this example code with whatever you need
        return [
            'servers' => $this->getDoctrine()->getManager()->getRepository('AppBundle:Server')->findAll(),
        ];
    }

}