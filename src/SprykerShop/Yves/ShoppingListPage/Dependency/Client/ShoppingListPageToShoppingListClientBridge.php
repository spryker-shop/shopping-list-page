<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Dependency\Client;

use Generated\Shared\Transfer\ShoppingListAddToCartRequestCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListDismissRequestTransfer;
use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemResponseTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListOverviewRequestTransfer;
use Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer;
use Generated\Shared\Transfer\ShoppingListPermissionGroupCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Generated\Shared\Transfer\ShoppingListShareResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;

class ShoppingListPageToShoppingListClientBridge implements ShoppingListPageToShoppingListClientInterface
{
    /**
     * @var \Spryker\Client\ShoppingList\ShoppingListClientInterface
     */
    protected $shoppingListClient;

    /**
     * @param \Spryker\Client\ShoppingList\ShoppingListClientInterface $shoppingListClient
     */
    public function __construct($shoppingListClient)
    {
        $this->shoppingListClient = $shoppingListClient;
    }

    public function removeShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        return $this->shoppingListClient->removeShoppingList($shoppingListTransfer);
    }

    public function clearShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        return $this->shoppingListClient->clearShoppingList($shoppingListTransfer);
    }

    public function updateShoppingListSharedEntities(ShoppingListTransfer $shoppingListTransfer): ShoppingListShareResponseTransfer
    {
        return $this->shoppingListClient->updateShoppingListSharedEntities($shoppingListTransfer);
    }

    public function getShoppingListPermissionGroups(): ShoppingListPermissionGroupCollectionTransfer
    {
        return $this->shoppingListClient->getShoppingListPermissionGroups();
    }

    public function getShoppingListItemCollectionTransfer(
        ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
    ): ShoppingListItemCollectionTransfer {
        return $this->shoppingListClient->getShoppingListItemCollectionTransfer($shoppingListItemCollectionTransfer);
    }

    public function getShoppingListItemCollection(ShoppingListCollectionTransfer $shoppingListCollectionTransfer): ShoppingListItemCollectionTransfer
    {
        return $this->shoppingListClient->getShoppingListItemCollection($shoppingListCollectionTransfer);
    }

    public function createShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        return $this->shoppingListClient->createShoppingList($shoppingListTransfer);
    }

    public function updateShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        return $this->shoppingListClient->updateShoppingList($shoppingListTransfer);
    }

    public function removeItemById(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemResponseTransfer
    {
        return $this->shoppingListClient->removeItemById($shoppingListItemTransfer);
    }

    public function getShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListTransfer
    {
        return $this->shoppingListClient->getShoppingList($shoppingListTransfer);
    }

    public function getShoppingListOverviewWithoutProductDetails(
        ShoppingListOverviewRequestTransfer $shoppingListOverviewRequestTransfer
    ): ShoppingListOverviewResponseTransfer {
        return $this->shoppingListClient->getShoppingListOverviewWithoutProductDetails($shoppingListOverviewRequestTransfer);
    }

    public function getCustomerShoppingListCollection(): ShoppingListCollectionTransfer
    {
        return $this->shoppingListClient->getCustomerShoppingListCollection();
    }

    public function addItemCollectionToCart(
        ShoppingListAddToCartRequestCollectionTransfer $shoppingListAddToCartRequestCollectionTransfer
    ): ShoppingListAddToCartRequestCollectionTransfer {
        return $this->shoppingListClient->addItemCollectionToCart($shoppingListAddToCartRequestCollectionTransfer);
    }

    public function dismissShoppingListSharing(ShoppingListDismissRequestTransfer $shoppingListDismissRequest): ShoppingListShareResponseTransfer
    {
        return $this->shoppingListClient->dismissShoppingListSharing($shoppingListDismissRequest);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param array<string, mixed> $params
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function addItem(ShoppingListItemTransfer $shoppingListItemTransfer, array $params = []): ShoppingListItemTransfer
    {
        return $this->shoppingListClient->addItem($shoppingListItemTransfer, $params);
    }
}
