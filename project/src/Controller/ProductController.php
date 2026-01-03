<?php

namespace App\Controller;

use App\Product\API\ProductAPIInterface;
use App\Product\ServiceProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends AbstractController
{
    private readonly ProductAPIInterface $productAPI;
    public function __construct()
    {
        $this->productAPI = ServiceProvider::getAPI();
    }
    public function overview(): Response
    {
        $productsDatas = $this->productAPI->getProductsList();
        return $this->render(
            'product_list.html.twig',
            [
                'productsDatas' => $productsDatas
            ]
        );
    }

    public function getProduct(int $productId): Response
    {
        $productData = $this->productAPI->findProduct($productId);
        return $this->render(
            'product_page.html.twig',
            [
                'productData' => $productData
            ]
        );
    }
}