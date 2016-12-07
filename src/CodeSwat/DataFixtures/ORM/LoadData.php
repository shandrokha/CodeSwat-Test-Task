<?php

namespace CodeSwat\ShopBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CodeSwat\ShopBundle\Entity\Product;
use CodeSwat\ShopBundle\Entity\Category;

class LoadData implements FixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$data = [
			[
				'category' => [
					'name' => 'Computers',
					'description' => 'Desktops and laptops, new and used',
				],
				'products' => [
					[
						'name' => 'ASUS K501UX Laptop',
					 	'description' => 'One of the most successfull ASUS laptops',
						'price' => 800.00,
					],
					[
						'name' => 'Sony Vaio PCG-61111V Laptop',
						'description' => 'Best selling laptop',
						'price' => 830.00,
					]
				]
			],
			[
				'category' => [
					'name' => 'Peripherals',
					'description' => 'Monitors, printes, scanners, keyboards, mouses',
				],
				'products' => [
					[
						'name' => 'Viewsonic VA2231WA',
						'description' => 'Brand new',
						'price' => 90.00,
					]
				]
			],
			[
				'category' => [
					'name' => 'Smartphones',
					'description' => 'Androids, iPhones, Windows Phones',
				],
				'products' => [
					[
						'name' => 'iPhone 7',
						'description' => 'Top ranged smartphone from Apple',
						'price' => 780.00,
					]
				]
			]
		];

		foreach ($data as $block) {
			$category = new Category();
			$category->setName($block['category']['name']);
			$category->setDescription($block['category']['description']);
			$manager->persist($category);

			foreach ($block['products'] as $productDetails) {
				$product = new Product();
				$product->setCategory($category);
				$product->setName($productDetails['name']);
				$product->setDescription($productDetails['description']);
				$product->setPrice($productDetails['price']);
				$manager->persist($product);
			}
		}

		$manager->flush();
	}
}