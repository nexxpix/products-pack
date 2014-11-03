<?php

namespace ProductsPack\Controller;

use Thelia\Controller\Admin\BaseAdminController;
use ProductsPack\Form\ChangePackStatusForm;
use ProductsPack\Form\LinkProductToPackForm;
use ProductsPack\Form\RemoveProductPackLinkForm;
use ProductsPack\Model\Pack;
use ProductsPack\Model\PackQuery;
use ProductsPack\Model\ProductPack;
use ProductsPack\Model\ProductPackQuery;
use Thelia\Tools\URL;
use Thelia\Core\Event\Product\ProductEvent;

/**
 * Class ProductsPackController
 * Manage actions of ProductsPack module
 * 
 * @package ProductsPack\Controller
 * @author Nexxpix - Etienne PERRIERE <eperriere@nexxpix.fr>
 */
class ProductsPackController extends BaseAdminController
{

    /**
     * Create a new pack.
     * Called by changePackStatusAction()
     * 
     * @param type $productId
     */
    public function createPack($productId)
    {
        $pack = new Pack();
        $pack->setProductId($productId);
        $pack->setIsActive(1);
        $pack->save();
    }

    /**
     * Update the pack's status : set the product as a pack or not.
     * Called by changePackStatusAction()
     * 
     * @param type $packQuery
     * @param type $newStatus
     */
    public function updatePackStatus($packQuery, $newStatus)
    {
        // Deactivation
        if ($packQuery->getIsActive() == 1 && $newStatus == 0) {
            $packQuery->setIsActive($newStatus);
            $packQuery->save();

            // TODO (if I remember why I wanted to do this) : Remove all links with products
        }
        // Activation
        elseif ($packQuery->getIsActive() == 0 && $newStatus == 1) {
            $packQuery->setIsActive($newStatus);
            $packQuery->save();
        } else {
            // Error : the product is already what you want it to be
        }
    }

    public function changePackStatusAction()
    {
        // Initialize vars
        $request = $this->getRequest();
        $cpsf = new ChangePackStatusForm($request);
        $form = $cpsf->getForm();

        // Form verification
        $form->bind($request);

        if ($form->isValid()) {

            $productId = $form->get('productId')->getData();
            $newStatus = $form->get('isPack')->getData();

            // Search if the product is in Pack table (active or not)
            $packQuery = PackQuery::create()->findOneByProductId($productId);
            // Search if the product is linked to a pack
            $productPackQuery = ProductPackQuery::create()->findByProductId($productId);

            // IF the product isn't linked to any pack
            if (count($productPackQuery) == 0) {

                // IF the product is absent from Pack table
                if (count($packQuery) == 0) {
                    $this->createPack($productId);
                } else {
                    $this->updatePackStatus($packQuery, $newStatus);
                }
            } else {
                // Return error "This product is already linked to another pack and can't be converted into a pack."
            }
        } else {
            /*
             * Should receive here errors from form validation ... But not.
             */
        }
        $this->redirect(URL::getInstance()->absoluteUrl($this->getRoute(
                                'admin.products.update', array('product_id' => $form->get("productId")->getData()))));
    }

    public function addToPackAction()
    {
        // Initialize vars
        $request = $this->getRequest();
        $lptpf = new LinkProductToPackForm($request);
        $form = $lptpf->getForm();

        // Form verification
        $form->bind($request);

        if ($form->isValid()) {

            $productId = $form->get('productId')->getData();
            $packId = $form->get('packId')->getData();

            // Search if the product is an active pack
            $packQuery = PackQuery::create()
                    ->filterByIsActive(1)
                    ->findOneByProductId($productId);

            // IF the product is not an active pack
            if (count($packQuery) == 0) {

                // Search if the combination product/pack already exists
                $productPackQuery = ProductPackQuery::create()
                        ->filterByPackId($packId)
                        ->findByProductId($productId);

                // IF the combination doesn't exist
                if (count($productPackQuery) == 0) {

                    // Create new link between product & pack
                    $productLinkPack = new ProductPack();
                    $productLinkPack->setProductId($productId);
                    $productLinkPack->setPackId($packId);
                    $productLinkPack->save();
                } else {
                    $this->get('session')->getFlashBag()->add(
                            'message', 'This product is already linked to the selected pack'
                    );
                }
            } else {
                $this->get('session')->getFlashBag()->add(
                        'message', "This product is already a pack and can't be part of a pack."
                );
            }
        }
        $this->redirect(URL::getInstance()->absoluteUrl($this->getRoute(
                                'admin.products.update', array('product_id' => $form->get("productId")->getData()))));
    }

    public function removeProductFromPackAction()
    {
        // Initialize vars
        $request = $this->getRequest();
        $rpplf = new RemoveProductPackLinkForm($request);
        $form = $rpplf->getForm();

        // Form verification
        $form->bind($request);

        if ($form->isValid()) {

            // Get datas
            $packId = $form->get('packId')->getData();
            $productId = $form->get('productId')->getData();

            // Search and remove
            ProductPackQuery::create()
                    ->filterByPackId($packId)
                    ->filterByProductId($productId)
                    ->delete();
        }

        // Find product id for redirection
        $toProductId = PackQuery::create()
                ->select('ProductId')
                ->findOneById($packId);

        $this->redirect(URL::getInstance()->absoluteUrl($this->getRoute(
                                'admin.products.update', array('product_id' => $toProductId))));
    }

}
