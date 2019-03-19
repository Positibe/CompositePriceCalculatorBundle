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


namespace Positibe\Sylius\CompositePriceCalculatorBundle\DependencyInjection\Compiler;

use Positibe\Sylius\CompositePriceCalculatorBundle\Calculator\CompositePriceCalculator;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler\PrioritizedCompositeServicePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;


/**
 * Class PriceCalculatorCompilerPass
 * @package Positibe\Sylius\PriceCalculatorBundle\DependencyInjection\Compiler
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class PriceCalculatorCompilerPass extends PrioritizedCompositeServicePass
{
    public const CALCULATOR_SERVICE_TAG = 'sylius.price_calculator';

    public function __construct()
    {
        parent::__construct(
            'sylius.calculator.product_variant_price',
            CompositePriceCalculator::class,
            self::CALCULATOR_SERVICE_TAG,
            'addCalculator'
        );
    }

    public function process(ContainerBuilder $container): void
    {
        parent::process($container);
    }
}