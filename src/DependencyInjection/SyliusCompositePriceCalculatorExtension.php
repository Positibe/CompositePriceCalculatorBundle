<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Positibe\Sylius\CompositePriceCalculatorBundle\DependencyInjection;

use Positibe\Sylius\CompositePriceCalculatorBundle\Calculator\CompositePriceCalculator;
use Sylius\Component\Core\Calculator\ProductVariantPriceCalculator;
use Sylius\Component\Core\Calculator\ProductVariantPriceCalculatorInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

final class SyliusCompositePriceCalculatorExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container): void
    {
        $container->register(CompositePriceCalculator::class)
            ->setPublic(true)
            ->setDecoratedService('sylius.calculator.product_variant_price');

        $container->register(ProductVariantPriceCalculator::class)
            ->setAutowired(true)
            ->setPublic(false)
            ->addTag('sylius.price_calculator', ['priority' => 40]);

        $container->registerForAutoconfiguration(ProductVariantPriceCalculatorInterface::class)
            ->addTag('sylius.price_calculator')
            ->setPublic(false)
            ->setAutowired(true)
        ;
    }
}
