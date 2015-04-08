<?php

namespace ProductsPack\Listener;

use ProductsPack\Event\PackEvent;
use ProductsPack\Event\ProductPackEvent;
use ProductsPack\Model\Pack;
use ProductsPack\Model\ProductPack;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Action\BaseAction;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\Event\Product\ProductEvent;
use ProductsPack\Model\PackQuery;
use ProductsPack\Model\ProductPackQuery;

/**
 * Class DeleteProductListener
 * Manage actions to make on packs or products linked to pack when deleting a product
 *
 * @package ProductsPack\Listener
 * @author Nexxpix - Etienne PERRIERE <eperriere@nexxpix.fr>
 */
class ProductsPackListener extends BaseAction implements EventSubscriberInterface
{

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            TheliaEvents::BEFORE_DELETEPRODUCT => array("beforeRemovePackLinks", 128),
            'action.createPack' => array('createPack', 128),
            'action.updatePack' => array('updatePack', 128),
            'action.createProductPackLink' => array('createProductPackLink', 128),
            'action.removeProductPackLink' => array('removeProductPackLink', 128)
        );
    }

    /**
     * Remove pack or product_pack linked to the deleted product
     *
     * @param ProductEvent $event
     */
    public function beforeRemovePackLinks(ProductEvent $event)
    {
        $productId = $event->getProduct()->getId();

        $pack = PackQuery::create()
                ->findByProductId($productId);
        $productLinked = ProductPackQuery::create()
                ->findByProductId($productId);

        // If the product belongs to one or more packs
        if (count($productLinked) != 0) {
            $productLinked->delete();
        }

        // If the product is a pack
        if (count($pack) != 0) {
            $packLinked = ProductPackQuery::create()
                    ->findByPackId($pack[0]->getId());

            // If the pack has some products linked
            if (count($packLinked) != 0) {
                $packLinked->delete();
            }
            $pack->delete();
        }
    }

    /**
     * Create a new pack from the product in parameter
     *
     * @param PackEvent $event
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function createPack(PackEvent $event)
    {
        $pack = new Pack();

        $pack
            ->setIsActive($event->getIsActive())
            ->setProductId($event->getProductId())
            ->save();
    }

    /**
     * Activate or deactivate an existing pack
     *
     * @param PackEvent $event
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function updatePack(PackEvent $event)
    {
        $pack = PackQuery::create()->findOneByProductId($event->getProductId());

        $pack
            ->setIsActive($event->getIsActive())
            ->save();
    }

    /**
     * Link a product to a pack
     *
     * @param ProductPackEvent $event
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function createProductPackLink(ProductPackEvent $event)
    {
        $productPackLink = new ProductPack();

        $productPackLink
            ->setPackId($event->getPackId())
            ->setProductId($event->getProductId())
            ->save();
    }

    /**
     * Remove a product from a pack
     *
     * @param ProductPackEvent $event
     * @return mixed
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function removeProductPackLink(ProductPackEvent $event)
    {
        ProductPackQuery::create()
            ->filterByPackId($event->getPackId())
            ->filterByProductId($event->getProductId())
            ->delete();
    }

}
