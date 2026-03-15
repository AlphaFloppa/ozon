<?php

declare (strict_types = 1);

namespace App\Controller;

use App\Product\API\ProductAPIInterface;
use App\Product\App\Model\Product;
use App\Product\App\Model\SaveProductData;
use App\ServiceProvider;
use App\User\API\UserAPI;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends AbstractController
{
    private ProductAPIInterface $productAPI;

    /**
     * @param ServiceProvider $serviceProvider
     */
    public function __construct(
        ServiceProvider $serviceProvider
    )
    {
        $this->productAPI = $serviceProvider->getProductAPI();
    }

    /**
     * @param Request $request данные формы создания
     * @return Response redirect на страницу каталога
     */
    public function createProduct(Request $request): Response
    {
        $sellerId = $this->getUser()->getUserIdentifier();

        $product = new SaveProductData(
            $request->get('title'),
            (float) $request->get('price'),
            $request->get('description'),
            $request->files->get('image'),
            (string) $sellerId
        );
        $this->productAPI->saveProduct($product);

        return $this->redirectToRoute('catalog');
    }

    /**
     * @return Response html форма создания
     */
    public function createProductView(Request $request): Response
    {
        return $this->render(
            'create_product_form.html.twig',
            [
                'imagesDirectory' => 'product_images/actual',
                'productData' => null,
            ]
        );
    }

    /**
     * @param int $productId
     * @return Response redirect на каталог
     */
    public function deleteProduct(int $productId): Response
    {
        $productData = $this->productAPI->findProduct($productId);
        if (! $productData)
        {
            return new Response('', Response::HTTP_NOT_FOUND);
        }
        $currentUser = $this->getUser();
        if (
            ! UserAPI::isUserCreatorOf($currentUser, $productData) &&
            ! $this->isGranted('ROLE_ADMIN')
        )
        {
            return new Response('', Response::HTTP_FORBIDDEN);
        }
        $this->productAPI->deleteProduct($productId);

        return $this->redirectToRoute(
            'catalog'
        );
    }

    /**
     * @param int $productId
     * @return Response html страницы товара
     */
    public function getProduct(int $productId): Response
    {
        $currentUser = $this->getUser();
        $productData = $this->productAPI->findProduct($productId);
        $isReadonly = ! UserAPI::isUserCreatorOf($currentUser, $productData) &&
        ! $this->isGranted('ROLE_ADMIN');

        return $this->render(
            'product_page.html.twig',
            [
                'imagesDirectory' => 'product_images/actual',
                'productData' => $productData,
                'isReadonly' => $isReadonly,
            ]
        );
    }

    /**
     * @return Response html страницы каталога
     */
    public function overview(): Response
    {
        $productsData = $this->productAPI->getProductList();

        return $this->render(
            'product_list.html.twig',
            [
                'productsData' => $productsData,
                'imagesDirectory' => 'product_images/actual',
            ]
        );
    }

    /**
     * @param Request $request данные формы обновления товара
     * @return Response redirect на страницу каталога
     */
    public function updateProduct(Request $request): Response
    {
        $productId = (int) $request->attributes->get('productId');
        $actualProductVersion = $this->productAPI->findProduct($productId);
        if ($actualProductVersion === null)
        {
            return new Response('', Response::HTTP_NOT_FOUND);
        }
        $currentUser = $this->getUser();
        if (
            ! UserAPI::isUserCreatorOf($currentUser, $actualProductVersion) &&
            ! $this->isGranted('ROLE_ADMIN')
        )
        {
            return new Response('', Response::HTTP_FORBIDDEN);
        }
        $product = new Product(
            $productId,
            $request->get('title'),
            (float) $request->get('price'),
            $request->get('description'),
            $request->files->get('image') ?? new File(
                __DIR__ . '/../../public/product_images/actual/' . $actualProductVersion['image']
            ),
            $actualProductVersion['seller_id']
        );
        $this->productAPI->updateProduct($product);

        return $this->redirectToRoute(
            'catalog'
        );
    }

    /**
     * @param int $productId
     * @return Response html форма обновления товара
     */
    public function updateProductView(int $productId): Response
    {
        $productData = $this->productAPI->findProduct($productId);
        if (! $productData)
        {
            return new Response('', Response::HTTP_NOT_FOUND);
        }
        $currentUser = $this->getUser();
        if (
            ! UserAPI::isUserCreatorOf($currentUser, $productData) &&
            ! $this->isGranted('ROLE_ADMIN')
        )
        {
            return new Response('', Response::HTTP_FORBIDDEN);
        }

        return $this->render(
            'update_product_form.html.twig',
            [
                'imagesDirectory' => 'product_images/actual',
                'productData' => $productData,
            ]
        );
    }
}
