<?php

namespace AppBundle\Controller;

use AppBundle\Entity\AddressBook;
use AppBundle\Service\FileUploader;
use PhpParser\Node\Scalar\MagicConst\File;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Addressbook controller.
 *
 */
class AddressBookController extends Controller
{
    /**
     * Lists all addressBook entities.
     * @Route("/addressbook/")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $addressBooks = $em->getRepository('AppBundle:AddressBook')->findAll();

        return $this->render('addressbook/index.html.twig', array(
            'addressBooks' => $addressBooks,
        ));
    }

    /**
     * Creates a new addressBook entity.
     * @Route("/addressbook/add")
     */
    public function addAction(Request $request, FileUploader $fileUploader)
    {
        $addressBook = new Addressbook();
        $form = $this->createForm('AppBundle\Form\AddressBookType', $addressBook);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pictureUrl = $form->get('pictureUrl')->getData();
            if ($pictureUrl) {
                $pictureFileName = $fileUploader->upload($pictureUrl);
                $addressBook->setPictureUrl($pictureFileName);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($addressBook);
            $em->flush();

            return $this->redirectToRoute('app_addressbook_show', array('id' => $addressBook->getId()));
        }

        return $this->render('addressbook/add.html.twig', array(
            'addressBook' => $addressBook,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/addressbook/{id}", name="app_addressbook_show")
     */
    public function showAction(AddressBook $addressBook)
    {
        $deleteForm = $this->createDeleteForm($addressBook);

        return $this->render('addressbook/show.html.twig', array(
            'addressBook' => $addressBook,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing addressBook entity.
     * @Route("/addressbook/edit/{id}", name="app_addressbook_edit")
     */
    public function editAction(Request $request, AddressBook $addressBook, FileUploader $fileUploader)
    {
        $deleteForm = $this->createDeleteForm($addressBook);
        $editForm = $this->createForm('AppBundle\Form\AddressBookType', $addressBook);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $pictureUrl = $editForm->get('pictureUrl')->getData();
            if ($pictureUrl) {
                $pictureFileName = $fileUploader->upload($pictureUrl);
                $addressBook->setPictureUrl($pictureFileName);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_addressbook_edit', array('id' => $addressBook->getId()));
        }

        return $this->render('addressbook/edit.html.twig', array(
            'addressBook' => $addressBook,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a addressBook entity.
     * @Route("/addressbook/delete/{id}", name="app_addressbook_delete")
     */
    public function deleteAction(Request $request, AddressBook $addressBook)
    {
        $form = $this->createDeleteForm($addressBook);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($addressBook);
            $em->flush();
        }

        return $this->redirectToRoute('app_addressbook_index');
    }

    /**
     * Creates a form to delete a addressBook entity.
     *
     * @param AddressBook $addressBook The addressBook entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AddressBook $addressBook)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_addressbook_delete', array('id' => $addressBook->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
