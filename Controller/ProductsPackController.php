<?php

namespace ProductsPack\Controller;

use ProductsPack\Event\ProductPackEvent;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Form\Exception\FormValidationException;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Core\Security\AccessManager;
use ProductsPack\Form\ChangePackStatusForm;
use ProductsPack\Form\LinkProductToPackForm;
use ProductsPack\Form\RemoveProductPackLinkForm;
use ProductsPack\Model\PackQuery;
use ProductsPack\Model\ProductPackQuery;
use ProductsPack\Event\PackEvent;

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
     * Change the product status (pack or not)
     * Call the Create action if the product has never been a pack
     * Call the Update action if the product is present into the Pack table
     *
     * @return mixed|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function changePackStatusAction()
    {
        if (null !== $response = $this->checkAuth(AdminResources::MODULE, 'ProductsPack', [AccessManager::UPDATE, AccessManager::CREATE])) {
            return $response;
        }

        // Initialize vars
        $request = $this->getRequest();
        $cpsf = new ChangePackStatusForm($request);

        // Form verification
        try {
            $form = $this->validateForm($cpsf);

            $productId = $form->get('productId')->getData();
            $newStatus = $form->get('isPack')->getData();

            // Search if the product is in Pack table
            $packQuery = PackQuery::create()->findOneByProductId($productId);

            // Search if the product is linked to a pack
            $productPackQuery = ProductPackQuery::create()->findByProductId($productId);

            // IF the product isn't linked to any pack
            if (count($productPackQuery) == 0) {

                // IF the product is absent from Pack table
                if ($packQuery === null) {
                    $event = new PackEvent(1, $productId);
                    $this->dispatch('action.createPack', $event);
                } else {
                    // Deactivation
                    if ($packQuery->getIsActive() == 1 && $newStatus == 0) {
                        $event = new PackEvent($newStatus, $packQuery->getProductId());
                    }
                    // Activation
                    elseif ($packQuery->getIsActive() == 0 && $newStatus == 1) {
                        $event = new PackEvent($newStatus, $packQuery->getProductId());
                    } else {
                        throw new \Exception("The product is already what you want it to be");
                    }

                    // Dispatch
                    $this->dispatch('action.updatePack', $event);
                }
            }
            /* else {
                throw new \Exception("This product is already linked to another pack and can't be converted into a pack.");
            }
            */

            return $this->generateRedirectFromRoute(
                'admin.products.update',
                array(
                    'product_id' => $productId,
                    'current_tab' => 'modules'
                )
            );
        } catch (FormValidationException $e) {
            throw new \Exception($this->createStandardFormValidationErrorMessage($e));
        }
    }

    /**
     * Add a product to an existing pack
     *
     * @return mixed|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function addToPackAction()
    {
        if (null !== $response = $this->checkAuth(AdminResources::MODULE, 'ProductsPack', [AccessManager::UPDATE, AccessManager::CREATE])) {
            return $response;
        }

        // Initialize vars
        $request = $this->getRequest();
        $lptpf = new LinkProductToPackForm($request);

        // Form verification
        try {
            $form = $this->validateForm($lptpf);

            $productId = $form->get('productId')->getData();
            $packId = $form->get('packId')->getData();

            // Search if the product is an active pack
            $packQuery = PackQuery::create()
                    ->filterByIsActive(1)
                    ->findOneByProductId($productId);

            // IF the product is not an active pack
            if ($packQuery === null) {

                // Search if the combination product/pack already exists
                $productPackQuery = ProductPackQuery::create()
                        ->filterByPackId($packId)
                        ->findByProductId($productId);

                // IF the combination doesn't exist, create new link between product & pack
                if (count($productPackQuery) == 0) {

                    // Dispatch
                    $event = new ProductPackEvent($packId, $productId);
                    $this->dispatch('action.createProductPackLink', $event);
                }

                return $this->generateRedirectFromRoute(
                    'admin.products.update',
                    array(
                        'product_id' => $productId,
                        'current_tab' => 'modules'
                    )
                );

                /* else {
                    throw new \Exception("This product is already linked to the selected pack.");
                } */
            } else {
                throw new \Exception("This product is already a pack and can't be part of a pack.");
            }
        } catch (FormValidationException $e) {
            throw new \Exception($this->createStandardFormValidationErrorMessage($e), null, $e);
        }
    }

    /**
     * Remove a product from a pack
     *
     * @return mixed|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function removeProductFromPackAction()
    {
        if (null !== $response = $this->checkAuth(AdminResources::MODULE, 'ProductsPack', AccessManager::DELETE)) {
            return $response;
        }

        // Initialize vars
        $request = $this->getRequest();
        $rpplf = new RemoveProductPackLinkForm($request);

        // Form verification
        try {
            $form = $this->validateForm($rpplf);

            // Get datas
            $packId = $form->get('packId')->getData();
            $productId = $form->get('productId')->getData();

            // Dispatch
            $event = new ProductPackEvent($packId, $productId);
            $this->dispatch('action.removeProductPackLink', $event);

            // Return to the product
            $toProduct = PackQuery::create()
                ->select('ProductId')
                ->findOneById($packId);

            return $this->generateRedirectFromRoute(
                'admin.products.update',
                array(
                    'product_id' => $toProduct,
                    'current_tab' => 'modules'
                )
            );
        } catch (FormValidationException $e) {
            throw new \Exception($this->createStandardFormValidationErrorMessage($e));
        }
    }

}
