Products Pack Module v1.0
author: <eperriere@nexxpix.fr>

Summary
=======

### fr_FR
1. Installation
2. Utilisation
	2.1. Créer un pack
	2.2. Ajouter un produit à un pack
	2.3. Retirer un produit d'un pack
3. Boucles
	3.1. pack
	3.2. product_pack
4. Intégration

### en_US
1. Install notes
2. How to use
	2.1. Create a pack
	2.2. Add a product to a pack
	2.3. Remove a produit from a pack
3. Loops
	3.1. pack
	3.2. product_pack
4. Integration


=====
fr_FR
=====

1. Installation
---------------
Pour installer le module Products Pack, téléchargez l'archive et extrayez la dans le dossier projetThelia/local/modules

2. Utilisation
--------------
Tout d'abord, allez dans votre back-office, onglet Modules, et activez le module Products Pack.
Le module se chargera de créer les tables nécessaires en base de données.
Vous pouvez dès à présent vous rendre sur la page de modification d'un produit, onglet Modules, et commencer à l'utiliser.

	2.1. Créer un pack
	------------------
Rendez-vous dans l'onglet Modules du produit que vous souhaitez déclarer en tant que pack.
Cliquez sur le bouton sous "Ce produit est-il un pack ?". C'est tout :)

	2.2. Ajouter un produit à un pack
	---------------------------------
Rendez-vous dans l'onglet Modules du produit que vous souhaitez intégrer à un pack.
Si le produit n'est pas déjà un pack, la liste des packs existants apparait sous "Sélectionnez un pack pour ce produit".
Sélectionnez le pack désiré et validez.

	2.3. Retirer un produit d'un pack
	---------------------------------
Rendez-vous dans l'onglet Modules du produit-pack duquel vous souhaitez retirer des produits.
Les produits intégrés au pack sont listés avec un bouton permettant de les retirer du pack en face de leur nom.
Cliquez simplement dessus pour retirer le produit du pack.

3. Boucles
----------
	3.1. pack
	---------
- Arguments:
	id			| intList | optionnel | Liste des id des packs à retourner
	is_active	| boolean | optionnel | Statut du pack-produit à rechercher (vrai -> c'est un pack / faux -> c'est un produit normal)
	product_id	| intList | optionnel | Liste des id des produits déclarés en tant que packs
- Sorties:
	$ID			Id du pack
	$IS_ACTIVE	Statut du produit (vrai -> pack actif / faux -> produit normal)
	$PRODUCT_ID Id du produit déclaré en tant que pack
- Utilisation:
	{loop type="pack" name="votreLoop"}<!-- Votre template -->{/loop}

	3.2. product_pack
	-----------------
- Arguments:
	pack_id		| intList | optionnel | Liste des id des packs à retourner
	product_id	| intList | optionnel | Liste des id des produits intégrés aux packs
- Sorties:
	$PACK_ID	Id du pack
	$PRODUCT_ID Id du produit déclaré en tant que pack
- Utilisation:
	{loop type="product_pack" name="votreLoop"}<!-- Votre template -->{/loop}

4. Intégration
--------------
Le module ProductsPack vous permet de déclarer un produit en tant que pack : vos packs apparaîtront et se comporteront donc de la même façon qu'un produit.
- Si vous souhaitez créer une page ne proposant que des packs de produits, utilisez la boucle de cette façon : 
	{loop type="pack" name="votreLoop" is_active='1'}
		{loop type="product" name="produitPack" id=$PRODUCT_ID}
			<!-- Votre template de pack -->
		{/loop}
	{/loop}
- Si vous souhaitez lister les produits d'un pack sur sa page :
	{loop type="pack" name="votrePack" product_id={product attr="id"}}
		{loop type="product_pack" name="produitsIntégrés" pack_id=$ID}
			{loop type="product" name="produitDuPack" id=$PRODUCT_ID}
				<!-- Votre template produit -->
			{/loop}
		{/loop}
	{/loop}


=====
en_US
=====

1. Install notes
----------------
To install Products Pack plugin, download the zip & extract it into your theliaProject/local/modules folder.

2. How to use
--------------
First, go to your back-office, Modules tab, and activate the Products Pack module.
Tables will be create in the database.
You can now go on a product edit page, Modules tab and start using it.

	2.1. Create a pack
	------------------
Go in the Modules tab of the product you wish to declare as a pack.
Click on the button under "Is this product a pack?". That's all :)

	2.2. Add a product to a pack
	----------------------------
Go in the Modules tab of the product you wish to add to a pack.
If the product is not a pack, packs list will appear under "Select a pack for this product".
Select the one you want and validate.

	2.3. Remove a produit from a pack
	---------------------------------
Go in the Modules tab of the product declared as a pack you wish to remove products from.
Integrated products are listed with a remove button in front of their name.
Simply click on this button to remove a product from the pack.

3. Loops
----------
	3.1. pack
	---------
- Arguments:
	id			| intList | optional | Id list of packs to return
	is_active	| boolean | optional | Product pack status to search for (true -> active pack / false -> normal product)
	product_id	| intList | optional | Id list of products declared as packs
- Outputs:
	$ID			Pack id
	$IS_ACTIVE	Product pack status (true -> active pack / false -> normal product)
	$PRODUCT_ID ID of the produit declared as a pack
- Usage:
	{loop type="pack" name="yourLoop"}<!-- Your template -->{/loop}

	3.2. product_pack
	-----------------
- Arguments:
	pack_id		| intList | optional | Id list of packs to return
	product_id	| intList | optional | Id list of products declared as packs
- Outputs:
	$ID			Pack id
	$PRODUCT_ID ID of the produit declared as a pack
- Usage:
	{loop type="product_pack" name="yourLoop"}<!-- Your template -->{/loop}

4. Integration
--------------
Products Pack module allows you to declare products as packs : packs will appeare and behave the same way as products.
- If you'd like to create a page with only packs on it, use the loop this way :
	{loop type="pack" name="yourLoop" is_active='1'}
		{loop type="product" name="productPack" id=$PRODUCT_ID}
			<!-- Your pack template -->
		{/loop}
	{/loop}
- If you'd like to list products from a pack on a page :
	{loop type="pack" name="thePack" product_id={product attr="id"}}
		{loop type="product_pack" name="integratedProducts" pack_id=$ID}
			{loop type="product" name="productFromPack" id=$PRODUCT_ID}
				<!-- Your product template -->
			{/loop}
		{/loop}
	{/loop}

