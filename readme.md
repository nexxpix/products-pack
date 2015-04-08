# ProductsPack

This module allows you to transform any product into a pack and to add or remove products to it.
Managing packs of products is very simple with this module.

## Installation

### Manually

* Copy the module into ```<thelia_root>/local/modules/``` directory and be sure that the name of the module is ProductsPack.
* Activate it in your Thelia administration panel.

## Usage

This module doesn't need any configuration. Once activated, simply go into the Modules tab of the product you want to change into a pack.
Check the "Is this module a pack" button. Your product is now a pack !
To add some existing products into this pack, just go into the Modules tab of the products you want to be part of the pack.
Then, select the pack it must belong to. You can also remove some products from a pack.

## Hook

This module is only hooked into the Modules tab of the products.
The Hook used is called "product.tab-content".

## Loop

[pack]

List your packs or find a specific pack thanks to this loop. You then have access to all linked products.

### Input arguments

|Argument |Description |
|---      |--- |
|**id**    | The ID of the pack you want to display. Example: "id=3" |
|**is_active**  | Filter if the pack is an active one or just a simple product. Example: "is_active='true'" |
|**product_id** | The ID of the product being a pack you want to display. Example: "product_id=12" |

### Output arguments

|Variable   |Description |
|---        |--- |
|$ID        | The current pack ID |
|$IS_ACTIVE | The pack's status (true = pack / false = product) |
|$PRODUCT_ID    | The product ID of the current pack |

[product_pack]

List or find the products linked to a pack.

### Input arguments

|Argument |Description |
|---      |--- |
|**pack_id**    | The ID of the pack you want to display. Example: "pack_id=3" |
|**product_id** | The ID of the product that belongs to a pack you want to display. Example: "product_id=12" |

### Output arguments

|Variable   |Description |
|---        |--- |
|$PACK_ID   | The current pack ID |
|$PRODUCT_ID    | The current product ID linked to the current pack |

### Example

We are on the page of a product changed into a pack and want to list all the products which belong to the current product (which is a pack).

{loop type="pack" name="productsPack" product_id="{product attr="id"}" is_active="true"}
    {loop type="product" name="productPack" id=$PRODUCT_ID}
        {$TITLE}
        ...
    {/loop}
    {ifloop rel="hasLinkedProducts"}
        {loop type="product_pack" name="hasLinkedProducts" pack_id=$ID}
            {loop type="product" name="linkedProduct" id=$PRODUCT_ID}
                {$TITLE}
                ...
            {/loop}
        {/loop}
    {/ifloop}
{/loop}

## Other ?

Once you have found a pack or a product that belongs to a pack, simply use the PRODUCT loop with $ID or $PRODUCT_ID variable as ID parameter tu access all the product's information.
