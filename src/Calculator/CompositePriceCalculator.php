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


namespace Positibe\Sylius\CompositePriceCalculatorBundle\Calculator;

use Sylius\Component\Core\Calculator\ProductVariantPriceCalculatorInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Zend\Stdlib\PriorityQueue;


/**
 * Class PipePriceCalculator
 * @package Positibe\Sylius\PriceCalculatorBundle\Calculator
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class CompositePriceCalculator implements ProductVariantPriceCalculatorInterface
{
    /** @var  ProductVariantPriceCalculatorInterface[] */
    protected $calculators;

    public function __construct()
    {
        $this->calculators = new PriorityQueue();
    }

    public function addCalculator($calculator, int $priority = 0): void
    {
        $this->calculators->insert($calculator, $priority);
    }

    public function calculate(ProductVariantInterface $productVariant, array $context): int
    {
        $price = 0;
        foreach ($this->calculators as $calculator) {
            $context['price'] = $price;
            $price = $calculator->calculate($productVariant, $context);
        }

        return $price;
    }
}