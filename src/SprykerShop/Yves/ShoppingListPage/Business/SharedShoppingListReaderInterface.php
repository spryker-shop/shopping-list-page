<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Business;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;

interface SharedShoppingListReaderInterface
{
    public function getSharedShoppingListEntities(ShoppingListTransfer $shoppingListTransfer, CustomerTransfer $customerTransfer): array;
}
