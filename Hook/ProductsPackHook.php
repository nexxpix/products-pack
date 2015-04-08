<?php

namespace ProductsPack\Hook;

use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

class ProductsPackHook extends BaseHook {

    public function onProductTabContent(HookRenderEvent $event)
    {
        $event->add(
            $this->render('product-tab-content-hook.html')
        );
    }

    public function onProductEditJs(HookRenderEvent $event)
    {
        $event->add(
            $this->addJS('assets/js/product-edit-js-hook.js')
        );
    }
}