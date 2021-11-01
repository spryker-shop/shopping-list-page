<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Form;

use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerShop\Yves\ShoppingListPage\ShoppingListPageFactory getFactory()
 * @method \SprykerShop\Yves\ShoppingListPage\ShoppingListPageConfig getConfig()
 */
class ShareShoppingListForm extends AbstractType
{
    /**
     * @var string
     */
    public const OPTION_PERMISSION_GROUPS = 'permissionGroups';

    /**
     * @var string
     */
    public const FIELD_COMPANY_BUSINESS_UNITS = 'sharedCompanyBusinessUnits';

    /**
     * @var string
     */
    public const FIELD_COMPANY_USERS = 'sharedCompanyUsers';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined(static::OPTION_PERMISSION_GROUPS);
        $resolver->setDefaults([
            'data_class' => ShoppingListTransfer::class,
            'constraints' => [
                $this->getFactory()->createShareShoppingListRequiredIdConstraint(),
            ],
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addCompanyBusinessUnits($builder, $options);
        $this->addCompanyUsers($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addCompanyBusinessUnits(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_COMPANY_BUSINESS_UNITS, CollectionType::class, [
            'entry_type' => ShoppingListBusinessUnitShareEditForm::class,
            'entry_options' => [
                static::OPTION_PERMISSION_GROUPS => $options[static::OPTION_PERMISSION_GROUPS],
            ],
            'label' => false,
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addCompanyUsers(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_COMPANY_USERS, CollectionType::class, [
            'entry_type' => ShoppingListCompanyUserShareEditForm::class,
            'entry_options' => [
                static::OPTION_PERMISSION_GROUPS => $options[static::OPTION_PERMISSION_GROUPS],
            ],
            'label' => false,
            'required' => false,
        ]);

        return $this;
    }
}
