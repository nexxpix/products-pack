<?php

namespace ProductsPack\Form;

use Symfony\Component\Validator\Constraints;
use Thelia\Form\BaseForm;
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
    protected function buildForm()
    {
        $this->formBuilder
        ->add("productId", "number",
            array(
                "constraints" => array(
                    new Constraints\NotBlank(),
                    /*new Constraints\Callback(array(
                        "methods" => array(
                            array($this,
                            "verifyNotActivePack")
                        )
                    ))*/
                )
            )
        )
        ->add("packId", "number",
            array(
                "constraints" => array(
                    new Constraints\NotBlank(),
                    /*new Constraints\Callback(array(
                        "methods" => array(
                            array($this,
                            "verifyExistingLink")
                        )
                    ))*/
                ),
                "label" => "Select a pack for this product"
            )
        );
    }
    
    // Check if the product is an active pack before trying to put it in a pack
    public function verifyNotActivePack($value, ExecutionContextInterface $context)
    {
        $packQuery = PackQuery::create()
                    ->filterByIsActive(1)
                    ->findOneByProductId($value);
        
        if ($packQuery) {
            $context->addViolation("This product is already a pack and can't be part of a pack.");
        }
    }
    
    // Check if the product - pack combination already exists
    public function verifyExistingLink($value, ExecutionContextInterface $context)
    {
        $data = $context->getRoot()->getData();

        $productPackQuery = ProductPackQuery::create()
                        ->filterByPackId($data["packId"])
                        ->findByProductId($data["productId"]);
        
        if ($productPackQuery) {
            $context->addViolation("This product is already linked to the selected pack.");
        }
    }

    public function getName()
    {
        return "productspack_productpack_create";
    }
}
