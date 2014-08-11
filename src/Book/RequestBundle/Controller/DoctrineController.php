<?php

namespace Book\RequestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Book\RequestBundle\Entity\Category;
use Book\RequestBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Response;

class DoctrineController extends Controller
{

    public function indexAction($example) {
        return new Response('<html><body>Doctrine '.$example.'</body></html>');
    }

    public function createProductAction($example, $name, $price, $description, $category)
    {
        switch ($example) {
            case '1':
                $product = new Product();
                $product->setName($name);
                $product->setPrice($price);
                $product->setDescription($description);

                $em = $this->getDoctrine()->getManager();
                $em->persist($product);
                $em->flush();
                return new Response('<html><body>Created product Id ' . $product->getId().'</body></html>');
                break;
            case '2':
                $categ = new Category();
                $categ->setName($category);

                $product = new Product();
                $product->setName($name);
                $product->setPrice($price);
                $product->setDescription($description);
                $product->setCategory($categ);

                $em = $this->getDoctrine()->getManager();
                $em->persist($categ);
                $em->persist($product);

                $em->flush();

                return new Response('<html><body>Created product Id ' . $product->getId() . ' and category id: ' . $categ->getId() .'</body></html>');
                break;
            default:
                return new Response('<html><body>Example ' . $example.'</body></html>');
                break;
        }
    }


    public function showProductAction($id, $example)
    {
        switch ($example) {
            case '1':
                $repository = $this->getDoctrine()->getRepository('BookRequestBundle:Product');
                $product = $repository->find($id);

//        $product = $repository->findOneById($id);
//        $product = $repository->findOneByName('foo');
//        $product = $repository->findAll();
//        $product = $repository->findByPrice(19.99);
//        $product = $repository->findOneBy(
//            array('name' => 'foo', 'price' => 19.99)
//        );
//        $product = $repository->findBy(
//            array('name' => 'foo'),
//            array('price' => 'ASC')
//        );

                if (!$product) {
                    throw $this->createNotFoundException('No product found for id ' . $id);
                }

                $categoryName = $product->getCategory()->getName();

                return new Response('<html><body>Found product Id ' . $id . ' name ' . $product->getName().' categ ' . $categoryName . '</body></html>');
                break;
            case '2':
                $repository = $this->getDoctrine()->getRepository('BookRequestBundle:Product');
                $product = $repository->findOneByIdJoinedToCategory($id);

                if (!$product) {
                    throw $this->createNotFoundException('No product found for id ' . $id);
                }

                $categoryName = $product->getCategory()->getName();

                return new Response('<html><body> ' . $id . ' name ' . $product->getName().' categ ' . $categoryName . '</body></html>');
                break;
            default:
                return new Response('<html><body>Example ' . $example.'</body></html>');
                break;
        }

    }

    public function updateProductAction($id, $name)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('BookRequestBundle:Product')->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        $product->setName($name);
        $em->flush();

        return $this->redirect($this->generateUrl('book_request_doctrine_product_show', array('id' => $id)));
    }

    public function removeProductAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('BookRequestBundle:Product')->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        $em->remove($product);
        $em->flush();

        return new Response('<html><body>Product Id ' . $id . ' removed.' . '</body></html>');
    }

    public function queryProductAction($example)
    {
        $repository = $this->getDoctrine()->getRepository('BookRequestBundle:Product');
        $em = $this->getDoctrine()->getManager();
        switch ($example) {
            case '1':
                $query = $repository->createQueryBuilder('p')
                    ->where('p.price < :price')
                    ->setParameter('price', 19.99)
                    ->orderBy('p.price','ASC')
                    ->getQuery();
                $dql = $query->getDql();
                $products = $query->getResult();

//                $products = $query->getSingleResult();
//                $products = $query->getOneOrNullResult();

                break;
            case '2':
                $query = $em->createQuery(
                    'SELECT p FROM BookRequestBundle:Product p WHERE p.price < :price ORDER BY p.price ASC'
                )->setParameter('price', 19.99);
                $dql = $query->getDql();
                $products = $query->getResult();
                break;
            case '3':
                $products = $em->getRepository('BookRequestBundle:Product')->findAllOrderedByName();
                $dql ='';
                break;
        }
        return new Response('<html><body>Products ' . var_export($dql, true) . '<br />' . var_export($products, true) . '</body></html>');

    }

}