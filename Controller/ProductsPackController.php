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

        try {
            // Form verification
            $form = $this->validateForm($cpsf);

            $productId = $form->get('productId')->getData();

            // Search if the product is/has been a pack or is part of one
            $packQuery = PackQuery::create()->findOneByProductId($productId);

            // IF the product is absent from Pack table
            if ($packQuery === null) {
                // Build event and dispatch Create
                $event = new PackEvent(1, $productId);
                $this->dispatch('action.createPack', $event);
            } else {
                // Build event and dispatch Update
                $event = new PackEvent($form->get('isPack')->getData(), $packQuery->getProductId());
                $this->dispatch('action.updatePack', $event);
            }

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

        try {
            // Form verification
            $form = $this->validateForm($lptpf);

            // Build event and dispatch
            $event = new ProductPackEvent(
                $form->get('packId')->getData(),
                $productId = $form->get('productId')->getData());
            $this->dispatch('action.createProductPackLink', $event);

            return $this->generateRedirectFromRoute(
                'admin.products.update',
                array(
                    'product_id' => $form->get('productId')->getData(),
                    'current_tab' => 'modules'
                )
            );
        } catch (FormValidationException $e) {
            throw new \Exception($this->createStandardFormValidationErrorMessage($e));
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

        try {
            // Form verification
            $form = $this->validateForm($rpplf);

            // Build event and dispatch
            $event = new ProductPackEvent($packId = $form->get('packId')->getData(), $form->get('productId')->getData());
            $this->dispatch('action.removeProductPackLink', $event);

            // Return to the product
            $toProduct = PackQuery::create()
                ->select('ProductId')
                ->findOneById($packId = $form->get('packId')->getData());

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
