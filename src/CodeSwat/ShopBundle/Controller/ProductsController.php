<?php

namespace CodeSwat\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CodeSwat\ShopBundle\Entity\Product;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ProductsController extends Controller
{
    public function indexAction()
    {
    	$products = $this->getDoctrine()->getRepository('CodeSwatShopBundle:Product')->findAll();
    	$categories = $this->getDoctrine()->getRepository('CodeSwatShopBundle:Category')->findAll();
        return $this->render('CodeSwatShopBundle:Products:index.html.twig',
			[
				'products' => $products,
			 	'categories' => $categories,
				'json_product_url' => $this->get('router')->generate('code_swat_shop_product_json'),
				'new_product_url' => $this->get('router')->generate('code_swat_shop_product_new'),
				'delete_product_url' => $this->get('router')->generate('code_swat_shop_product_index')
			]);
    }

	public function newAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();

		$product = new Product();
		$product->setName($request->request->get('name'));
		$product->setCategory($em->getRepository('CodeSwatShopBundle:Category')->find($request->request->get('category_id')));
		$product->setDescription($request->request->get('description'));
		$product->setPrice($request->request->get('price'));

		$validator = $this->get('validator');
		$errors = $validator->validate($product);
		if (count($errors) > 0) {
			$responseData = [
				'status' => 'error',
				'error' => (string)$errors
			];
		}
		else {
			$em->persist($product);
			$em->flush();

			$responseData = [
				'status' => 'success',
				'product' => [
					'id' => $product->getId(),
					'category_name' => $product->getCategory()->getName(),
					'name' => $product->getName(),
					'description' => $product->getDescription(),
					'price' => number_format($product->getPrice(), 2, '.', '')
				]
			];
		}

		return JsonResponse::create($responseData);
	}

	public function deleteAction(Product $product)
	{
		$productId = $product->getId();

		$em = $this->getDoctrine()->getManager();
		$em->remove($product);
		$em->flush();

		$responseData = [
			'status' => 'success',
			'product_id' => $productId
		];

		return JsonResponse::create($responseData);
	}

	public function jsonAction()
	{
		$responseData = [
			'status' => 'success',
			'products' => []
		];

		$em = $this->getDoctrine()->getManager();
		$products = $em->getRepository('CodeSwatShopBundle:Product')->findAll();

		foreach ($products as $product) {
			$responseData['products'][] = [
				'id' => $product->getId(),
				'category_name' => $product->getCategory()->getName(),
				'name' => $product->getName(),
				'description' => $product->getDescription(),
				'price' => $product->getPrice()
			];
		}

		return JsonResponse::create($responseData);
	}
}
