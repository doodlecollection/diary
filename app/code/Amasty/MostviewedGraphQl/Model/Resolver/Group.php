<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_MostviewedGraphQl
 */


declare(strict_types=1);

namespace Amasty\MostviewedGraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Catalog\Block\Product\ReviewRendererInterface;

class Group implements ResolverInterface
{
    /**
     * @var \Amasty\Mostviewed\Block\Widget\Related
     */
    private $related;

    /**
     * @var \Magento\Framework\App\State
     */
    private $appState;

    public function __construct(
        \Amasty\Mostviewed\Block\Widget\Related $related,
        \Magento\Framework\App\State $appState
    ) {
        $this->related = $related;
        $this->appState = $appState;
    }

    /**
     * @param Field $field
     * @param \Magento\Framework\GraphQl\Query\Resolver\ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array|\Magento\Framework\GraphQl\Query\Resolver\Value|mixed
     * @throws \Exception
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        try {
            $this->related->setPosition($args['position']);
            $this->related->setEntityId($args['id']);
            $this->related->setEntityType($args['type']);
        } catch (\Exception $e) {
            return ['error' => 'Wrong parameters.'];
        }
        $productsCollection = $this->related->getProductCollection();
        $productItems = $productsCollection->getItems();
        $data = ['items' => []];
        if ($productsCollection) {
            $data = [
                'items' => $productsCollection ? $productsCollection->toArray() : [],
            ];

            foreach ($productItems as $key => $product) {
                $data['items'][$key]['model'] = $product;
            }
        }

        foreach ($data['items'] as $key => $product) {
            $data['items'][$key]['product_url'] = $this->related->escapeUrl(
                $this->related->getProductUrl($productItems[$key])
            );
            $data['items'][$key]['add_to_cart_url'] = $this->related->getAddToCartUrl($productItems[$key]);
            $data['items'][$key]['add_to_wishlist'] = $this->related->getAddToWishlistParams($productItems[$key]);
            $data['items'][$key]['image'] = $this->appState->emulateAreaCode(
                \Magento\Framework\App\Area::AREA_FRONTEND,
                [$this, 'getProductImage'],
                [$productItems[$key]]
            );
            $data['items'][$key]['reviews_summary'] = $this->appState->emulateAreaCode(
                \Magento\Framework\App\Area::AREA_FRONTEND,
                [$this, 'getReviewsSummary'],
                [$productItems[$key]]
            );
        }

        return $data;
    }

    /**
     * @param $product
     * @return string
     */
    public function getProductImage($product)
    {
        return $this->related->getImage($product, 'related_products_content')->toHtml();
    }

    /**
     * @param $product
     * @return string
     */
    public function getReviewsSummary($product)
    {
        return $this->related->getReviewsSummaryHtml($product, ReviewRendererInterface::SHORT_VIEW);
    }
}
