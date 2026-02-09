<?php

declare(strict_types=1);

namespace App\Controller;

use App\Product\API\ProductAPIInterface;
use App\Product\App\Models\Product;
use App\ServiceProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Symfony\Component\HttpFoundation\File\File;

class ProductController extends AbstractController
{
    private ProductAPIInterface $productAPI;

    public function __construct(
        ServiceProvider $serviceProvider
    ) {
        $this->productAPI = $serviceProvider->getProductAPI();
    }

    public function overview(): Response
    {
        try {
            $productsData = $this->productAPI->getProductList();
            return $this->render(
                'product_list.html.twig',
                [
                    'productsData' => array_map(
                        fn($productData) => $productData,
                        $productsData
                    ),
                    'imagesDirectory' => 'product_images/actual'
                ]
            );
        } catch (Throwable $excp) {
            return new Response(
                $excp->getMessage(),
                500
            );
        }
    }

    public function getProduct(int $productId): Response
    {
        try {
            $productData = $this->productAPI->findProduct($productId);
            return $this->render(
                'product_page.html.twig',
                [
                    'imagesDirectory' => 'product_images/actual',
                    'productData' => $productData
                ]
            );
        } catch (Throwable $excp) {
            return new Response(
                $excp->getMessage(),
                500
            );
        }
    }

    public function createProductView(Request $request): Response
    {
        return $this->render(
            'create_product_form.html.twig',
            [
                'imagesDirectory' => 'product_images/actual',
                'productData' => null
            ]
        );
    }

    public function createProduct(Request $request): Response
    {
        $product = new Product(
            null,
            $request->get('title'),
            (float) $request->get('price'),
            $request->get('description'),
            $request->files->get('image')
        );
        $this->productAPI->saveProduct($product);
        return $this->redirectToRoute('catalog');
    }

    public function updateProductView(int $productId): Response
    {
        $productData = $this->productAPI->findProduct($productId);
        return $this->render(
            'update_product_form.html.twig',
            [
                'imagesDirectory' => 'product_images/actual',
                'productData' => $productData
            ]
        );
    }

    public function updateProduct(Request $request): Response
    {
        $id = (int) $request->attributes->get('productId');
        $deprecatedProductVersion = $this->productAPI->findProduct($id);
        $product = new Product(
            $id,
            $request->get('title'),
            (float) $request->get('price'),
            $request->get('description'),
            $request->files->get('image') ?? new File(
                __DIR__ . '/../../public/product_images/actual' . $deprecatedProductVersion['image']
            )
        );
        $this->productAPI->updateProduct($product);
        return $this->redirectToRoute('catalog');
    }

    public function deleteProduct(int $productId): Response
    {
        try {
            $this->productAPI->deleteProduct($productId);
            return $this->redirectToRoute(
                'catalog'
            );
        } catch (Throwable $excp) {
            return new Response(
                $excp->getMessage(),
                500
            );
        }
    }
}