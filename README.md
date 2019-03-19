# CompositePriceCalculatorBundle for Sylius Core Price Calculator

This bundle allow to use multiples price calculators on chain by replacing the default calculator for a composite calculator.

## Installation

1. Require plugin with composer:

    ```bash
    composer require positibe/sylius-composite-price-calculator-bundle
    ```
    
2. Add plugin class to your `AppKernel`:

With Symfony Flex:

    ```php
    $bundles = [
        new \FOS\JsRoutingBundle\FOSJsRoutingBundle(),
        new \Sylius\AdminOrderCreationPlugin\SyliusAdminOrderCreationPlugin(),
    ];
    ```

Without Symfony Flex:

    ```php
    # config/bundles.php
    return [
        //...
        Positibe\Sylius\CompositePriceCalculatorBundle\SyliusCompositePriceCalculatorBundle::class => ['all' => true]
    ];
    ```

## How to use

``Nota: The main advantage of this function on Sylius is to provide a way to implement product variant price calculator on plugins without having conflict between them. We recommend don't use this bundle if you dont need to create plugins with a custom calculator.``

This bundle add a new entry to our `$context` passed on the `calculate(ProductVariantInterface $productVariant, array $context): int` method. Now we have a `price` where we have the last price before execute the current calculator.

The job of calculator now is add or modify the price given depend of the custom logic.

Here is a example of a fee calculator plugin:

    ```php
    
    namespace Positibe\Sylius\FeePlugin\Calculator;
    
    use Positibe\Sylius\FeePlugin\Entity\FeeableInterface;
    use Sylius\Component\Core\Calculator\ProductVariantPriceCalculatorInterface;
    use Sylius\Component\Core\Model\ProductVariantInterface;
    
    class ProductVariantFeeablePriceCalculator implements ProductVariantPriceCalculatorInterface
    {
        //... Some stuf to get services
    
        /**
         * @param ProductVariantInterface|FeeableInterface $productVariant
         * @param array $context
         * @return int
         */
        public function calculate(ProductVariantInterface $productVariant, array $context): int
        {
            $price = (int) $context['price'] ?? 0;
            $fees = $productVariant->getFees();
            foreach ($fees as $fee) {
                if ($fee->isIncludedInPrice()) {
                    $price += $this->feeCalculator->calculate($context['price'], $fee);
                }
            }
    
            return (int) $price;
        }
    }
    ```
    
### Default calculator ###

The `Positibe\Sylius\CompositePriceCalculatorBundle\Calculator\CompositePriceCalculator` overrides the default calculator `sylius.calculator.product_variant_price` and the alias `Sylius\Component\Core\Calculator\ProductVariantPriceCalculatorInterface`.

The default one now has the qcnm id `Sylius\Component\Core\Calculator\ProductVariantPriceCalculator` and has `priority: 40`, the higher priority the sooner is executed.

### Order matters ###
 
You can change the order of the chain calculator by providing a `priority`.

``Causion: By default the priority is cero (`0`) so the order without priority is unpredictable.`` 

Here 
    ```yaml
    Positibe\Sylius\FeePlugin\Calculator\ProductVariantFeeablePriceCalculator:
        autoconfigure: false
        tags:
            - { name: 'sylius.price_calculator', priority: 10 }
    ```