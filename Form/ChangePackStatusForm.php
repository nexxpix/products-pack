<?php

namespace ProductsPack\Form;

use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\ExecutionContextInterface;
use Thelia\Form\BaseForm;
use ProductsPack\Model\ProductPackQuery;

/**
 * Class ChangePackStatusForm
 * Build form to change a pack status
 * 
 * @package ProductsPack\Form
 * @author Nexxpix - Etienne PERRIERE <eperriere@nexxpix.fr>
 */
class ChangePackStatusForm extends BaseForm
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
                                "verifyExistingLink")
                            )
                        ))*/
                    )
                )
            )
            ->add("isPack", "checkbox", array(
                "label" => "Is this product a pack")
        );
    }
    
    // Check if the product - pack combination already exists
    public function verifyExistingLink($value, ExecutionContextInterface $context)
    {
        $productPackQuery = ProductPackQuery::create()->findByProductId($value);
        
        if ($productPackQuery) {
            $context->addViolation("This product is linked to a pack. You can't define it as a pack.");
        }
    }

    public function getName()
    {
        return "productspack_pack_changestatus";
    }
}
