<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Controller;

use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Generated\Shared\Transfer\ShoppingListShareRequestTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Yves\Kernel\Controller\AbstractController;
use SprykerShop\Yves\ShoppingListPage\Plugin\Provider\ShoppingListPageControllerProvider;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ShoppingListPage\ShoppingListPageFactory getFactory()
 */
class ShoppingListOverviewController extends AbstractController
{
    protected const PARAM_SHOPPING_LISTS = 'shoppingLists';
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $shoppingListForm = $this->getFactory()
            ->getShoppingListForm()
            ->handleRequest($request);

        if ($shoppingListForm->isSubmitted() && $shoppingListForm->isValid()) {
            $shoppingListResponseTransfer = $this->getFactory()
                ->getShoppingListClient()
                ->createShoppingList($this->getShoppingListTransfer($shoppingListForm));

            if ($shoppingListResponseTransfer->getIsSuccess()) {
                $this->addSuccessMessage('customer.account.shopping_list.created');

                return $this->redirectResponseInternal(ShoppingListPageControllerProvider::ROUTE_SHOPPING_LIST_OVERVIEW);
            }

            $this->handleResponseErrors($shoppingListResponseTransfer, $shoppingListForm);
        }

        $data = [
            'shoppingListCollection' => $this->getCustomerShoppingListCollection(),
            'shoppingListForm' => $shoppingListForm->createView(),
        ];

        return $this->view($data, [], '@ShoppingListPage/views/shopping-list-overview/shopping-list-overview.twig');
    }

    /**
     * @param string $shoppingListName
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction($shoppingListName, Request $request)
    {
        $shoppingListFormDataProvider = $this->getFactory()->createShoppingListFormDataProvider();
        $shoppingListForm = $this->getFactory()
            ->getShoppingListForm($shoppingListFormDataProvider->getData($shoppingListName))
            ->handleRequest($request);

        if ($shoppingListForm->isSubmitted() && $shoppingListForm->isValid()) {
            $shoppingListResponseTransfer = $this->getFactory()
                ->getShoppingListClient()
                ->updateShoppingList($this->getShoppingListTransfer($shoppingListForm));

            if ($shoppingListResponseTransfer->getIsSuccess()) {
                $this->addSuccessMessage('customer.account.shopping_list.updated');

                return $this->redirectResponseInternal(ShoppingListPageControllerProvider::ROUTE_SHOPPING_LIST_OVERVIEW);
            }

            $this->handleResponseErrors($shoppingListResponseTransfer, $shoppingListForm);
        }

        $shoppingListCollection = $this->getCustomerShoppingListCollection();

        $data = [
            'shoppingListCollection' => $shoppingListCollection,
            'shoppingListForm' => $shoppingListForm->createView(),
            'idShoppingList' => $shoppingListForm->getData()->getIdShoppingList(),
        ];

        return $this->view($data, [], '@ShoppingListPage/views/shopping-list-overview-update/shopping-list-overview-update.twig');
    }

    /**
     * @param string $shoppingListName
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($shoppingListName): RedirectResponse
    {
        $shoppingListTransfer = new ShoppingListTransfer();
        $shoppingListTransfer
            ->setCustomerReference($this->getCustomerReference())
            ->setName($shoppingListName);

        $this->getFactory()
            ->getShoppingListClient()
            ->removeShoppingList($shoppingListTransfer);
        $this->addSuccessMessage('customer.account.shopping_list.deleted');

        return $this->redirectResponseInternal(ShoppingListPageControllerProvider::ROUTE_SHOPPING_LIST_OVERVIEW);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addShoppingListToCartAction(Request $request): RedirectResponse
    {
        $shoppingListItems = $this->getFactory()
            ->getShoppingListClient()
            ->getShoppingListItemCollection($this->getShoppingListCollectionTransfer($request));

        $result = $this->getFactory()
            ->createAddToCartHandler()
            ->addAllAvailableToCart($shoppingListItems->getItems()->getArrayCopy());

        if ($result->getRequests()->count()) {
            $this->addErrorMessage('customer.account.shopping_list.items.added_to_cart.failed');
            return $this->redirectResponseInternal(ShoppingListPageControllerProvider::ROUTE_SHOPPING_LIST_OVERVIEW);
        }

        $this->addSuccessMessage('customer.account.shopping_list.items.added_to_cart');
        return $this->redirectResponseInternal(ShoppingListPageControllerProvider::ROUTE_SHOPPING_LIST_OVERVIEW);
    }

    /**
     * @param string $shoppingListName
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function shareShoppingListAction(string $shoppingListName, Request $request)
    {
        $shareShoppingListForm = $this->getFactory()
            ->getShareShoppingListForm($shoppingListName, $this->getCustomerReference())
            ->handleRequest($request);

        if ($shareShoppingListForm->isSubmitted() && $shareShoppingListForm->isValid()) {
            /** @var ShoppingListShareRequestTransfer $shoppingListShareRequestTransfer */
            $shoppingListShareRequestTransfer = $shareShoppingListForm->getData();
            $shoppingListShareResponseTransfer = $this->getFactory()
                ->getShoppingListClient()
                ->shareShoppingList($shoppingListShareRequestTransfer);

            if($shoppingListShareResponseTransfer->getIsSuccess()) {
                $this->addSuccessMessage('customer.account.shopping_list.share.share_shopping_list_successful');
                return $this->redirectResponseInternal(ShoppingListPageControllerProvider::ROUTE_SHOPPING_LIST_OVERVIEW);
            }

            $this->addErrorMessage('customer.account.shopping_list.share.share_shopping_list_fail');
        }

        $data = [
            'shoppingListName' => $shoppingListName,
            'shareShoppingListForm' => $shareShoppingListForm->createView(),
            'shoppingListCollection' => $this->getCustomerShoppingListCollection(),
        ];

        return $this->view($data, [], '@ShoppingListPage/views/share-shopping-list/share-shopping-list.twig');
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $shoppingListForm
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    protected function getShoppingListTransfer(FormInterface $shoppingListForm): ShoppingListTransfer
    {
        /** @var \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer */
        $shoppingListTransfer = $shoppingListForm->getData();
        $shoppingListTransfer->setCustomerReference($this->getCustomerReference());

        return $shoppingListTransfer;
    }

    /**
     * @return string
     */
    protected function getCustomerReference(): string
    {
        $customerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();

        return $customerTransfer->getCustomerReference();
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListResponseTransfer $shoppingListResponseTransfer
     * @param \Symfony\Component\Form\FormInterface $shoppingListForm
     *
     * @return void
     */
    protected function handleResponseErrors(ShoppingListResponseTransfer $shoppingListResponseTransfer, FormInterface $shoppingListForm): void
    {
        foreach ($shoppingListResponseTransfer->getErrors() as $error) {
            $shoppingListForm->addError(new FormError($error));
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    protected function getShoppingListCollectionTransfer(Request $request): ShoppingListCollectionTransfer
    {
        $shoppingListIds = $request->get(static::PARAM_SHOPPING_LISTS);
        $shoppingListCollectionTransfer = new ShoppingListCollectionTransfer();

        if ($shoppingListIds) {
            foreach ($shoppingListIds as $idShoppingList) {
                $shoppingList = (new ShoppingListTransfer())->setIdShoppingList($idShoppingList);

                $shoppingListCollectionTransfer->addShoppingList($shoppingList);
            }
        }

        return $shoppingListCollectionTransfer;
    }

    /**
     *
     * @return ShoppingListCollectionTransfer
     */
    protected function getCustomerShoppingListCollection(): ShoppingListCollectionTransfer
    {
        return $this->getFactory()
            ->getShoppingListClient()
            ->getCustomerShoppingListCollection();
    }
}
