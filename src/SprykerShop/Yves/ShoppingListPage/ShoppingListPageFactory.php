<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage;

use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ShoppingListPage\Business\AddToCartHandler;
use SprykerShop\Yves\ShoppingListPage\Business\AddToCartHandlerInterface;
use SprykerShop\Yves\ShoppingListPage\Business\SharedShoppingListReader;
use SprykerShop\Yves\ShoppingListPage\Business\SharedShoppingListReaderInterface;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToCompanyBusinessUnitClientInterface;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToCompanyUserClientInterface;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToCustomerClientInterface;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToLocaleClientInterface;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToMultiCartClientInterface;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToPriceClientInterface;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToProductStorageClientInterface;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToShoppingListClientInterface;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToZedRequestClientInterface;
use SprykerShop\Yves\ShoppingListPage\Dependency\Service\ShoppingListPageToUtilEncodingServiceInterface;
use SprykerShop\Yves\ShoppingListPage\Dependency\Service\ShoppingListPageToUtilNumberServiceInterface;
use SprykerShop\Yves\ShoppingListPage\Form\AddShoppingListToCartForm;
use SprykerShop\Yves\ShoppingListPage\Form\Constraint\ShareShoppingListRequiredIdConstraint;
use SprykerShop\Yves\ShoppingListPage\Form\DataProvider\ShareShoppingListDataProvider;
use SprykerShop\Yves\ShoppingListPage\Form\DataProvider\ShoppingListFormDataProvider;
use SprykerShop\Yves\ShoppingListPage\Form\DataProvider\ShoppingListUpdateFormDataProvider;
use SprykerShop\Yves\ShoppingListPage\Form\Handler\AddToCartFormHandler;
use SprykerShop\Yves\ShoppingListPage\Form\Handler\AddToCartFormHandlerInterface;
use SprykerShop\Yves\ShoppingListPage\Form\ShareShoppingListForm;
use SprykerShop\Yves\ShoppingListPage\Form\ShoppingListAddItemToCartForm;
use SprykerShop\Yves\ShoppingListPage\Form\ShoppingListClearForm;
use SprykerShop\Yves\ShoppingListPage\Form\ShoppingListDeleteForm;
use SprykerShop\Yves\ShoppingListPage\Form\ShoppingListForm;
use SprykerShop\Yves\ShoppingListPage\Form\ShoppingListRemoveItemForm;
use SprykerShop\Yves\ShoppingListPage\Form\ShoppingListUpdateForm;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerShop\Yves\ShoppingListPage\ShoppingListPageConfig getConfig()
 */
class ShoppingListPageFactory extends AbstractFactory
{
    public function getCustomerClient(): ShoppingListPageToCustomerClientInterface
    {
        return $this->getProvidedDependency(ShoppingListPageDependencyProvider::CLIENT_CUSTOMER);
    }

    public function getZedRequestClient(): ShoppingListPageToZedRequestClientInterface
    {
        return $this->getProvidedDependency(ShoppingListPageDependencyProvider::CLIENT_ZED_REQUEST);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer|null $data
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getShoppingListForm(?ShoppingListTransfer $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(ShoppingListForm::class, $data, $options);
    }

    public function getShoppingListUpdateForm(ShoppingListTransfer $data): FormInterface
    {
        return $this->getFormFactory()->create(
            ShoppingListUpdateForm::class,
            $data,
            $this->createShoppingListUpdateFormDataProvider()->getOptions(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $data
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getShoppingListDeleteForm(ShoppingListTransfer $data, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(ShoppingListDeleteForm::class, $data, $options);
    }

    public function createShoppingListFormDataProvider(): ShoppingListFormDataProvider
    {
        return new ShoppingListFormDataProvider(
            $this->getShoppingListClient(),
            $this->getCustomerClient(),
            $this->getShoppingListFormDataProviderMapperPlugins(),
        );
    }

    public function getFormFactory(): FormFactory
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    public function createAddToCartHandler(): AddToCartHandlerInterface
    {
        return new AddToCartHandler($this->getShoppingListClient(), $this->getCustomerClient());
    }

    public function createSharedShoppingListReader(): SharedShoppingListReaderInterface
    {
        return new SharedShoppingListReader($this->getCompanyUserClient(), $this->getCompanyBusinessUnitClient());
    }

    public function createAddToCartFormHandler(): AddToCartFormHandlerInterface
    {
        return new AddToCartFormHandler($this->getShoppingListClient(), $this->getCustomerClient());
    }

    public function getShoppingListClient(): ShoppingListPageToShoppingListClientInterface
    {
        return $this->getProvidedDependency(ShoppingListPageDependencyProvider::CLIENT_SHOPPING_LIST);
    }

    public function getProductStorageClient(): ShoppingListPageToProductStorageClientInterface
    {
        return $this->getProvidedDependency(ShoppingListPageDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return array<\Spryker\Client\ProductStorage\Dependency\Plugin\ProductViewExpanderPluginInterface>
     */
    public function getShoppingListItemExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ShoppingListPageDependencyProvider::PLUGIN_SHOPPING_LIST_ITEM_EXPANDERS);
    }

    /**
     * @return array<\SprykerShop\Yves\ShoppingListPageExtension\Dependency\Plugin\ShoppingListItemFormExpanderPluginInterface>
     */
    public function getShoppingListItemFormExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ShoppingListPageDependencyProvider::PLUGIN_SHOPPING_LIST_ITEM_FORM_EXPANDERS);
    }

    public function getShareShoppingListForm(ShoppingListTransfer $shoppingListTransfer): FormInterface
    {
        $shareShoppingListFormDataProvider = $this->createShareShoppingListFormDataProvider();

        return $this->getFormFactory()->create(
            ShareShoppingListForm::class,
            $shareShoppingListFormDataProvider->getData($shoppingListTransfer),
            $shareShoppingListFormDataProvider->getOptions(),
        );
    }

    public function createShareShoppingListFormDataProvider(): ShareShoppingListDataProvider
    {
        return new ShareShoppingListDataProvider(
            $this->getCompanyBusinessUnitClient(),
            $this->getCompanyUserClient(),
            $this->getCustomerClient(),
            $this->getShoppingListClient(),
        );
    }

    public function createShoppingListUpdateFormDataProvider(): ShoppingListUpdateFormDataProvider
    {
        return new ShoppingListUpdateFormDataProvider(
            $this->getLocaleClient(),
        );
    }

    public function getCompanyBusinessUnitClient(): ShoppingListPageToCompanyBusinessUnitClientInterface
    {
        return $this->getProvidedDependency(ShoppingListPageDependencyProvider::CLIENT_COMPANY_BUSINESS_UNIT);
    }

    public function getCompanyUserClient(): ShoppingListPageToCompanyUserClientInterface
    {
        return $this->getProvidedDependency(ShoppingListPageDependencyProvider::CLIENT_COMPANY_USER);
    }

    public function createShareShoppingListRequiredIdConstraint(): ShareShoppingListRequiredIdConstraint
    {
        return new ShareShoppingListRequiredIdConstraint();
    }

    /**
     * @return array<\SprykerShop\Yves\ShoppingListPageExtension\Dependency\Plugin\ShoppingListFormDataProviderMapperPluginInterface>
     */
    public function getShoppingListFormDataProviderMapperPlugins(): array
    {
        return $this->getProvidedDependency(ShoppingListPageDependencyProvider::PLUGIN_SHOPPING_LIST_FORM_DATA_PROVIDER_MAPPERS);
    }

    public function getMultiCartClient(): ShoppingListPageToMultiCartClientInterface
    {
        return $this->getProvidedDependency(ShoppingListPageDependencyProvider::CLIENT_MULTI_CART);
    }

    public function getUtilEncodingService(): ShoppingListPageToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ShoppingListPageDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    public function getLocaleClient(): ShoppingListPageToLocaleClientInterface
    {
        return $this->getProvidedDependency(ShoppingListPageDependencyProvider::CLIENT_LOCALE);
    }

    public function getUtilNumberService(): ShoppingListPageToUtilNumberServiceInterface
    {
        return $this->getProvidedDependency(ShoppingListPageDependencyProvider::SERVICE_UTIL_NUMBER);
    }

    public function getPriceClient(): ShoppingListPageToPriceClientInterface
    {
        return $this->getProvidedDependency(ShoppingListPageDependencyProvider::CLIENT_PRICE);
    }

    public function getShoppingListAddItemToCartForm(): FormInterface
    {
        return $this->getFormFactory()->create(ShoppingListAddItemToCartForm::class);
    }

    public function getShoppingListRemoveItemForm(): FormInterface
    {
        return $this->getFormFactory()->create(ShoppingListRemoveItemForm::class);
    }

    public function getShoppingListClearForm(): FormInterface
    {
        return $this->getFormFactory()->create(ShoppingListClearForm::class);
    }

    public function getAddShoppingListToCartForm(): FormInterface
    {
        return $this->getFormFactory()->create(AddShoppingListToCartForm::class);
    }
}
