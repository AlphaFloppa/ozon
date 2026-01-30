<?php

namespace App\Controller;

use App\Product\API\ProductAPIInterface;
use App\Product\Domain\Models\Product;
use App\Product\Infrastructure\Forms\CreateProductType;
use App\ServiceProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

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
            $productsData = $this->productAPI->getProductsList();
            return $this->render(
                'product_list.html.twig',
                [
                    'imagesDirectory' => 'product_images',
                    'productsData' => $productsData
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
                    'imagesDirectory' => 'product_images',
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

    public function createProduct(Request $request): Response
    {
        try {
            $product = new Product();
            $form = $this->createForm(CreateProductType::class, $product);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->productAPI->saveProduct($product);
                return $this->redirectToRoute(
                    'catalog'
                );
            }

            return $this->render(
                'create_product.html.twig',
                [
                    'form' => $form
                ]
            );
        } catch (Throwable $excp) {
            return new Response(
                $excp->getMessage(),
                500
            );
        }
    }

    public function updateProduct(Request $request): Response
    {
        try {
            $id = $request->attributes->get('productId');
            $productData = $this->productAPI->findProduct($id);
            $product = new Product(
                $productData->getId(),
                $productData->getName(),
                $productData->getPrice(),
                $productData->getDescription(),
            );
            $form = $this->createForm(CreateProductType::class, $product);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->productAPI->saveProduct($product);
                return $this->redirectToRoute(
                    'catalog'
                );
            }

            return $this->render(
                'create_product.html.twig',
                [
                    'form' => $form
                ]
            );
        } catch (Throwable $excp) {
            return new Response(
                $excp->getMessage(),
                500
            );
        }
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