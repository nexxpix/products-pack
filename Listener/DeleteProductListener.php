<?php

namespace ProductsPack\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
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
class DeleteProductListener implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return array(TheliaEvents::BEFORE_DELETEPRODUCT => array("beforeRemovePackLinks", 128));
    }

    /**
     * Remove pack or product_pack linked to the deleted product
     *
     * @param ProductEvent $product
     */
    public function beforeRemovePackLinks(ProductEvent $product)
    {
        $productId = $product->getProduct()->getId();

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

}
