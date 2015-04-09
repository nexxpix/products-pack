<?php

namespace ProductsPack\Form;

use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\ExecutionContextInterface;
use Thelia\Form\BaseForm;
use ProductsPack\ProductsPack;
use ProductsPack\Model\PackQuery;
use ProductsPack\Model\ProductPackQuery;

/**
 * Class LinkProductToPackForm
 * Build form to link a product to a pack
 * 
 * @package ProductsPack\Form
 * @author Nexxpix - Etienne PERRIERE <eperriere@nexxpix.fr>
 */
class LinkProductToPackForm extends BaseForm
{
    public function getName()
    {
        return "productspack_productpack_create";
    }

    protected function buildForm()
    {
        $this->formBuilder
        ->add("productId", "number",
            array(
                "constraints" => array(
                    new Constraints\NotBlank(),
                    new Constraints\Callback(
                        array(
                            "methods" => array(
                                array($this,
                                    "checkNotPack"
                                )
                            )
                        )
                    )
                ),
                "label" => $this->translator->trans('Select a pack for this product', [], ProductsPack::DOMAIN.'.bo.default')
            )
        )
        ->add("packId", "number",
            array(
                "constraints" => array(
                    new Constraints\NotBlank(),
                    new Constraints\Callback(
                        array(
                            "methods" => array(
                                array($this,
                                    "checkExistingLink"
                                )
                            )
                        )
                    )
                ),
                "label" => $this->translator->trans('Select a pack for this product', [], ProductsPack::DOMAIN.'.bo.default')
            )
        );
    }
    
    // Check if the product is a pack before trying to put it in a pack
    public function checkNotPack($value, ExecutionContextInterface $context)
    {
        $packQuery = PackQuery::create()
            ->findOneByProductId($value);
        
        if ($packQuery !== null) {
            $context->addViolation($this->translator->trans("This product is already / has been a pack and can't be part of a pack", [], ProductsPack::DOMAIN.'.bo.default'));
        }
    }
    
    // Check if the product - pack combination already exists
    public function checkExistingLink($value, ExecutionContextInterface $context)
    {
        $productPackQuery = ProductPackQuery::create()
            ->filterByPackId($value)
            ->findByProductId($this->getForm()->getData()['productId']);

        if (count($productPackQuery) !== 0) {
            $context->addViolation($this->translator->trans('This product is already linked to the selected pack', [], ProductsPack::DOMAIN.'.bo.default'));
        }
    }
}
