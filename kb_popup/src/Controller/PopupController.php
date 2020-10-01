<?php

namespace PrestaShop\Module\Kb_Popup\Controller;

use DateTime;
use PrestaShop\Module\Kb_Popup\Entity\Popup;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use PrestaShop\Module\Kb_Popup\Form\PopupFormType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class PopupController extends FrameworkBundleAdminController
{
    /**
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $records = $em
            ->getRepository(Popup::class)
            ->findAll();

        foreach ($records as $record) {
            if($record->getImage() != null){
                $record->setImage(_MODULE_DIR_.'kb_popup/views/img/'.$record->getImage());
            }
        }

        return $this->render(
            '@Modules/kb_popup/views/templates/admin/index/index.html.twig',
            ['records' => $records]
        );
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        //variables
        $legacyContext = $this->get('prestashop.adapter.legacy.context')->getContext();
        $id_lang = $legacyContext->language->id;
        $id_shop = $legacyContext->shop->id;
        $em = $this->getDoctrine()->getManager();
        $popup = new Popup();
        $form = $this->createForm(PopupFormType::class, $popup);

        // action
        if ($request->isMethod('post')) {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                // simple time validation 
                $startDate = new DateTime($form->get('startsAt')->getData());
                $endDate = new DateTime($form->get('endsAt')->getData());

                if ( $startDate >= $endDate){
                    $this->addFlash("error", "Popup has not been added, sum thing wong, Start date cannot be earlier than end date");
                    return $this->redirectToRoute("kb_popup_add");
                }
                
                //Check for if there is any other popup for certain products
                $pickedProductId = $form->get('id_product')->getData();
                $popups = $em
                    ->getRepository(Popup::class)
                    ->findBy(['idProduct' => $pickedProductId]);

                if (count($popups) >= 1){
                    $this->addFlash("error", "Popup has not been added, there is 1 popups for this product already");
                    return $this->redirectToRoute("kb_popup_index");
                }

                // set lang and shop context
                $popup->setId_shop($id_lang);
                $popup->setId_lang($id_shop);

                $file = $form->get('image')->getData();

                if ($file) {
                    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                    // Move the file to the directory where brochures are stored
                    try {
                        $file->move(
                            _PS_MODULE_DIR_.'kb_popup/views/img',
                            $newFilename
                        );
                    } catch (FileException $e) {
                        $this->addFlash("notice", "{$e->getMessage()}");
                        return $this->redirectToRoute("kb_popup_index");
                    }

                    $popup->setImage($newFilename);
                } 
                
                $em->persist($popup);
                $em->flush();
    
                $this->addFlash("notice", "Popup added");
    
                return $this->redirectToRoute("kb_popup_index");
                
            }

            $this->addFlash("error", "Popup not added");
        }

        return $this->render('@Modules/kb_popup/views/templates/admin/index/add.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param Popup $popup
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Popup $popup)
    {
        $form = $this->createForm(PopupFormType::class, $popup);
        $em = $this->getDoctrine()->getManager();

        if ($request->isMethod('post')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $file = $form->get('image')->getData();

                // simple time validation 
                $startDate = new DateTime($form->get('startsAt')->getData());
                $endDate = new DateTime($form->get('endsAt')->getData());

                if ( $startDate >= $endDate){
                    $this->addFlash("error", "Popup has not been added, sum thing wong, Start date cannot be earlier than end date");
                    return $this->redirectToRoute("kb_popup_add");
                }
                
                //Check for if there is any other popup for certain products
                $pickedProductId = $form->get('id_product')->getData();
                $popups = $em
                    ->getRepository(Popup::class)
                    ->findBy(['idProduct' => $pickedProductId]);

                if ($file) {
                    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                    // Move the file to the directory where brochures are stored
                    try {
                        $file->move(
                            _PS_MODULE_DIR_.'kb_popup/views/img',
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }

                    $popup->setImage($newFilename);
                }

                $em->persist($popup);
                $em->flush();
                
                $this->addFlash("notice", "Popup has been edit");

                return $this->redirectToRoute('kb_popup_index');
            }

            $this->addFlash("error", "Popup has not been edit");
        }


        return $this->render('@Modules/kb_popup/views/templates/admin/index/edit.html.twig', ["form" => $form->createView()]);
    }

    /*
    * @param Popup $popup
    *
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function deleteAction(Popup $popup)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($popup);
        $em->flush();

        $this->addFlash("success", "Popup has been removed");

        return $this->redirectToRoute("kb_popup_index");
    }
}
