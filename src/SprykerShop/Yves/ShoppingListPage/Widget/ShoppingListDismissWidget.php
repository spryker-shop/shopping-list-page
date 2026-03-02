<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Widget;

use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ShoppingListPage\ShoppingListPageFactory getFactory()
 */
class ShoppingListDismissWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_SHOPPING_LIST = 'shoppingList';

    /**
     * @var string
     */
    protected const PARAMETER_IS_OWNER = 'isOwner';

    public function __construct(ShoppingListTransfer $shoppingListTransfer)
    {
        $this->addShoppingListParam($shoppingListTransfer);
        $this->addIsCustomerShoppingListOwnerParam($shoppingListTransfer);
    }

    public static function getName(): string
    {
        return 'ShoppingListDismissWidget';
    }

    public static function getTemplate(): string
    {
        return '@ShoppingListPage/views/shopping-list-dismiss-link/shopping-list-dismiss-link.twig';
    }

    protected function addShoppingListParam(ShoppingListTransfer $shoppingListTransfer): void
    {
        $this->addParameter(static::PARAMETER_SHOPPING_LIST, $shoppingListTransfer);
    }

    protected function addIsCustomerShoppingListOwnerParam(ShoppingListTransfer $shoppingListTransfer): void
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        $this->addParameter(static::PARAMETER_IS_OWNER, $shoppingListTransfer->getCustomerReference() === $customerTransfer->getCustomerReference());
    }
}
