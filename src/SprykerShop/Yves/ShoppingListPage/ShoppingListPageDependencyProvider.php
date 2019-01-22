<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToCompanyBusinessUnitClientBridge;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToCompanyUserClientBridge;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToCustomerClientBridge;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToMultiCartClientBridge;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToProductStorageClientBridge;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToShoppingListClientBridge;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToZedRequestClientBridge;

class ShoppingListPageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';
    public const CLIENT_SHOPPING_LIST = 'CLIENT_SHOPPING_LIST';
    public const CLIENT_PRODUCT_STORAGE = 'CLIENT_PRODUCT_STORAGE';
    public const CLIENT_COMPANY_BUSINESS_UNIT = 'CLIENT_COMPANY_BUSINESS_UNIT';
    public const CLIENT_COMPANY_USER = 'CLIENT_COMPANY_USER';
    public const PLUGIN_SHOPPING_LIST_ITEM_EXPANDERS = 'PLUGIN_SHOPPING_LIST_ITEM_EXPANDERS';
    public const PLUGIN_SHOPPING_LIST_ITEM_FORM_EXPANDERS = 'PLUGIN_SHOPPING_LIST_ITEM_FORM_EXPANDERS';
    public const PLUGIN_SHOPPING_LIST_FORM_DATA_PROVIDER_MAPPERS = 'PLUGIN_SHOPPING_LIST_FORM_DATA_PROVIDER_MAPPERS';
    public const CLIENT_MULTI_CART = 'CLIENT_MULTI_CART';
    public const CLIENT_ZED_REQUEST = 'CLIENT_ZED_REQUEST';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addCustomerClient($container);
        $container = $this->addShoppingListClient($container);
        $container = $this->addProductStorageClient($container);
        $container = $this->addCompanyBusinessUnitClient($container);
        $container = $this->addCompanyUserClient($container);
        $container = $this->addMultiCartClient($container);
        $container = $this->addShoppingListItemExpanderPlugins($container);
        $container = $this->addShoppingListItemFormExpanderPlugins($container);
        $container = $this->addShoppingListFormDataProviderMapperPlugins($container);
        $container = $this->addZedRequestClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCustomerClient(Container $container): Container
    {
        $container[self::CLIENT_CUSTOMER] = function (Container $container) {
            return new ShoppingListPageToCustomerClientBridge($container->getLocator()->customer()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addZedRequestClient(Container $container): Container
    {
        $container[static::CLIENT_ZED_REQUEST] = function (Container $container) {
            return new ShoppingListPageToZedRequestClientBridge($container->getLocator()->zedRequest()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addShoppingListClient(Container $container): Container
    {
        $container[self::CLIENT_SHOPPING_LIST] = function (Container $container) {
            return new ShoppingListPageToShoppingListClientBridge($container->getLocator()->shoppingList()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductStorageClient(Container $container): Container
    {
        $container[self::CLIENT_PRODUCT_STORAGE] = function (Container $container) {
            return new ShoppingListPageToProductStorageClientBridge($container->getLocator()->productStorage()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCompanyBusinessUnitClient(Container $container): Container
    {
        $container[self::CLIENT_COMPANY_BUSINESS_UNIT] = function (Container $container) {
            return new ShoppingListPageToCompanyBusinessUnitClientBridge($container->getLocator()->companyBusinessUnit()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCompanyUserClient(Container $container): Container
    {
        $container[self::CLIENT_COMPANY_USER] = function (Container $container) {
            return new ShoppingListPageToCompanyUserClientBridge($container->getLocator()->companyUser()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addShoppingListItemExpanderPlugins(Container $container): Container
    {
        $container[self::PLUGIN_SHOPPING_LIST_ITEM_EXPANDERS] = function () {
            return $this->getShoppingListItemExpanderPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Client\ProductStorage\Dependency\Plugin\ProductViewExpanderPluginInterface[]
     */
    protected function getShoppingListItemExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addShoppingListItemFormExpanderPlugins(Container $container): Container
    {
        $container[static::PLUGIN_SHOPPING_LIST_ITEM_FORM_EXPANDERS] = function () {
            return $this->getShoppingListItemFormExpanderPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addMultiCartClient(Container $container): Container
    {
        $container[static::CLIENT_MULTI_CART] = function (Container $container) {
            return new ShoppingListPageToMultiCartClientBridge($container->getLocator()->multiCart()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addShoppingListFormDataProviderMapperPlugins(Container $container): Container
    {
        $container[static::PLUGIN_SHOPPING_LIST_FORM_DATA_PROVIDER_MAPPERS] = function () {
            return $this->getShoppingListFormDataProviderMapperPlugins();
        };

        return $container;
    }

    /**
     * @return \SprykerShop\Yves\ShoppingListPageExtension\Dependency\Plugin\ShoppingListItemFormExpanderPluginInterface[]
     */
    protected function getShoppingListItemFormExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return \SprykerShop\Yves\ShoppingListPageExtension\Dependency\Plugin\ShoppingListFormDataProviderMapperPluginInterface[]
     */
    protected function getShoppingListFormDataProviderMapperPlugins(): array
    {
        return [];
    }
}
