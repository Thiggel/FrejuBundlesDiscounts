<?php

namespace FrejuBundlesDiscounts\Bundle\StoreFrontBundle;

use Doctrine\DBAL\Connection;

class DiscountService
{
    /**
     * @var Connection
     */
    private $connection;

    private $freeAddArticlesService;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection, FreeAddArticlesService $freeAddArticlesService)
    {
        $this->connection = $connection;
        $this->freeAddArticlesService = $freeAddArticlesService;
    }

    private function removeDiscount(string $discount, int $price): float
    {
        $discount = str_replace(',', '.', $discount);

        if(strpos($discount, '€')) {
            $price += (float)trim(str_replace('€', '', $discount));
        } else if(strpos($discount, '%')) {
            $value = (float)trim(str_replace('%', '', $discount));
            $price /= (1 - $value / 100);
        }

        return (float)$price;
    }


    private function addDiscount(string $discount, int $price): float
    {
        $discount = str_replace(',', '.', $discount);

        if(strpos($discount, '€')) {
            $price -= (float)trim(str_replace('€', '', $discount));
        } else if(strpos($discount, '%')) {
            $value = (float)trim(str_replace('%', '', $discount));
            $price *= (1 + $value / 100);
        }

        return (float)$price;
    }

    private function calculateDiscount(string $action, $type, string $discount, float $price): float
    {
        // remove €/%-sign and replace comma by point
        $discount = floatval(
            str_replace([$type, ','], ['', '.'],
                str_replace('.', '', $discount)
            )
        );

        if($type == '€')
        {
            if($action == 'applyDiscount')
                return $price - $discount;

            // case 'deApplyDiscount'
            return $price + $discount;
        }

        if($action == 'applyDiscount')
            return $price * (1 - $discount / 100);

        // case 'deApplyDiscount'
        return $price / (1 - $discount / 100);
    }

    private function parseDate(string $date): int
    {
        $parts = explode(".", $date);
        $parsed = new \DateTime();
        $parsed->setDate($parts[2], $parts[1], $parts[0]);

        return $parsed->getTimestamp();
    }

    private function adjustBrightness($hex, $steps)
    {
        // Steps should be between -255 and 255. Negative = darker, positive = lighter
        $steps = max(-255, min(255, $steps));

        // Normalize into a six character long hex string
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 3) {
            $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
        }

        // Split into three parts: R, G and B
        $color_parts = str_split($hex, 2);
        $return = '#';

        foreach ($color_parts as $color) {
            $color   = hexdec($color); // Convert to decimal
            $color   = max(0,min(255,$color + $steps)); // Adjust color
            $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
        }

        return $return;
    }

    private function makeDarker($hex)
    {
        return $this->adjustBrightness($hex, -120);
    }

    public function getDiscounts(): array
    {
        $customerGroup = Shopware()->Shop()->getCustomerGroup()->getKey();

        $sql = "
            SELECT i.id, i.main_product_id, i.discount, i.discountType, i.precalculated, i.cashback, d.active, d.startDate, d.endDate, d.badge, d.color, t.tax, p.price
            FROM s_discounted_item i
            INNER JOIN s_discount d ON d.name = i.campaign
            INNER JOIN s_articles_prices p ON p.articleID = i.main_product_id
            INNER JOIN s_articles a ON a.id = i.main_product_id
            INNER JOIN s_core_tax t ON t.id = a.taxID
            WHERE p.pricegroup = '$customerGroup'
        ";

        $query = $this->connection->query($sql)->fetchAll();

        $products = [];

        foreach($query as $discount)
        {
            if(
                $discount['active']  &&
                empty($products[$discount['main_product_id']]['discounts'][$discount['id']])
                && $this->parseDate($discount['startDate']) <= time()
                && $this->parseDate($discount['endDate']) >= time()
            )
            {

                // save the price to do calculations with later
                $products[$discount['main_product_id']]['price'] = $discount['price'] * (1 + $discount['tax'] / 100);

                // get type (precalc, postcalc, cashback) to sort the discounts
                $type = $discount['precalculated'] ? 'precalculated' : ( $discount['cashback'] ? 'cashback' : 'postcalculated' );

                $products[$discount['main_product_id']]['discounts'][$type][$discount['discountType']][$discount['id']] = [
                    'value' => $discount['discount'],
                    'name' => $discount['badge'],
                    'color' => $discount['color'],
                    'darkerColor' => $this->makeDarker($discount['color'])
                ];

            }
        }

        $freeAddArticles = $this->freeAddArticlesService->getFreeProducts();

        foreach($products as $key => &$product) {
            $price = [
                'precalculated' => $product['price'],
                'postcalculated' => $product['price'],
                'cashback' => $product['price']
            ];

            // the order in which the discounts will be applied
            $order = [
                'precalculated' => [
                    // the order of units is different for precalculated since they're backtracked, so it's the other way round
                    'order' => ['%', '€'],
                    'action' => 'deApplyDiscount'
                ],
                'postcalculated' => [
                    'order' => ['€', '%'],
                    'action' => 'applyDiscount'
                ],
                'cashback' => [
                    'order' => ['€', '%'],
                    'action' => 'applyDiscount'
                ]
            ];


            foreach($order as $type => $typeOrder)
            {
                // apply or de-apply discount in order of units
                foreach($typeOrder['order'] as $unit)
                {
                    foreach($product['discounts'][$type][$unit] as &$discount)
                    {
                        if(isset($discount['value']))
                        {
                            $oldPrice = $price[$type];
                            $price[$type] = $this->calculateDiscount(
                                $typeOrder['action'],
                                $unit,
                                $discount['value'],
                                $price[$type]
                            );

                            $discount['absoluteValue'] = abs($oldPrice - $price[$type]);
                        }
                    }
                }

                // if this discount changes the actual price (not precalculated)
                if($type == 'postcalculated')
                    // then change the price onto which cashbacks are applied as well
                    $price['cashback'] = $price[$type];
            }


            // add free articles
            if($freeAddArticles[$key]) {
                // add price
                foreach($freeAddArticles[$key] as $article)
                    $price['precalculated'] += $article['price'];

                // add action product to object
                $product['freeAddArticles'] = $freeAddArticles[$key];
            }

            // 1. price that includes everything - precalculated discounts and free articles
            $product['prePrice'] = $price['precalculated'];

            // 2. price as it is in the system (without any discounts)
            $product['systemPrice'] = $product['price'];

            // 3. price with postcalculated discounts applied as well
            $product['payablePrice'] = $price['postcalculated'];

            // 4. price with cashbacks applied (the final price that the customer will have paid in the end)
            $product['postPrice'] = $price['cashback'];

        }

        return $products;
    }

    public function applyDiscountToProduct(array $article): array
    {
        $discounts = $this->getDiscounts();

        if(isset($discounts[$article['articleID']])) {
            $article['price'] = $discounts[$article['articleID']]['payablePrice'];
            $article['netprice'] = $article['price'] / (1 + $article['tax_rate'] / 100);
        }

        return $article;
    }

}
