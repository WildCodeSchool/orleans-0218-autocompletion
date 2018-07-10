<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Town;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Town controller.
 *
 * @Route("town")
 */
class TownController extends Controller
{

    /**
     * @param Request $request
     * @param $town
     * @Route("/list/{town}", name="list-town")
     * @Method({"POST"})
     */
    public function autocompleteAction(Request $request, string $town): Response
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Town');
        $data = $repository->getTownLike('fr', $town);

        return $this->json($data);
    }


    /**
     * Lists all town entities.
     *
     * @Route("/", name="town_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $towns = $em->getRepository('AppBundle:Town')->findAll();

        return $this->render('town/index.html.twig', array(
            'towns' => $towns,
        ));
    }

    /**
     * Creates a new town entity.
     *
     * @Route("/new", name="town_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $town = new Town();
        $form = $this->createForm('AppBundle\Form\TownType', $town);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($town);
            $em->flush();

            return $this->redirectToRoute('town_show', array('id' => $town->getId()));
        }

        return $this->render('town/new.html.twig', array(
            'town' => $town,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a town entity.
     *
     * @Route("/{id}", name="town_show")
     * @Method("GET")
     */
    public function showAction(Town $town)
    {
        $deleteForm = $this->createDeleteForm($town);

        return $this->render('town/show.html.twig', array(
            'town'        => $town,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to delete a town entity.
     *
     * @param Town $town The town entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Town $town)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('town_delete', array('id' => $town->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Displays a form to edit an existing town entity.
     *
     * @Route("/{id}/edit", name="town_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Town $town)
    {
        $deleteForm = $this->createDeleteForm($town);
        $editForm = $this->createForm('AppBundle\Form\TownType', $town);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('town_edit', array('id' => $town->getId()));
        }

        return $this->render('town/edit.html.twig', array(
            'town'        => $town,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a town entity.
     *
     * @Route("/{id}", name="town_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Town $town)
    {
        $form = $this->createDeleteForm($town);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($town);
            $em->flush();
        }

        return $this->redirectToRoute('town_index');
    }
}
