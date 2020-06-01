<?php

namespace App\Controller;

use DateTime;
use DateTimeZone;
use App\Entity\Product;
use App\Entity\ProductType;
use App\Entity\ProductFormat;
use App\Entity\Verifications;
use App\Entity\ProductEdition;
use App\Services\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/product", name="product")
 */
class ProductController extends AbstractController {

    public function __construct(Security $security) {
        $this->security = $security;
    }
    /**
     * @Route("/", name="")
     */
    public function index() {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    /**
     * @Route("/create", name="_create")
     */
    public function create(Request $request, EntityManagerInterface $em) {
        $productTypeID = $request->query->get('idType', null);
        $productID = $request->query->get('productID', null);
        $productType = $em->getRepository(ProductType::class)->findOneBy(["id" => $productTypeID]);

        dump($productType);

        $params = [
            "productType" => $productType
        ];

        $bookGenres = ["Terror", "Thriller", "Drama"];
        $movieGenres = ["Terror", "Thriller", "Drama"];
        $seriesGenres = ["Terror", "Thriller", "Drama"];
        $cdsGenres = ["Pop", "Rock", "Metal", "Monas chinas"];
        $vinylGenres = ["Pop", "Rock", "Metal", "Monas chinas"];

        switch ($productType->getName()) {
            case "Libros": {
                $params["genres"] = $bookGenres;
                $params["distribuitorLabel"] = "Editorial";
                break;
            }
            case "Peliculas": {
                $params["genres"] = $movieGenres;
                $params["authorLabel"] = "Director";
                break;
            }
            case "Series": {
                $params["genres"] = $seriesGenres;
                $params["authorLabel"] = "Director";
                break;
            }
            case "CD's": {
                $params["genres"] = $cdsGenres;
                $params["nameLabel"] = "Nombre del album";
                $params["distribuitorLabel"] = "Banda o artista";
                $params["authorLabel"] = "Distribuidor";
                break;
            }
            case "Vinilos": {
                $params["genres"] = $vinylGenres;
                $params["nameLabel"] = "Nombre del album";
                break;
            }
        } 

        $productType = $em->getRepository(ProductType::class)->findOneBy(["id" => $productTypeID]);
        $entity = $em->getRepository(Product::class)->findOneBy(["id" => $productID]);

        $params["entity"] = $entity;

        $params["formats"] = $productType->getProductFormats();
        $params["editions"] = $productType->getProductEditions();
        $params["productID"] = $productID;
        
        dump($params);

        return $this->render('product/form.html.twig', $params);
    }

    /**
     * @Route("/create/categories", name="_create_categories")
     */
    public function categories (EntityManagerInterface $em) {
        $categories = $em->getRepository(ProductType::class)->findAll();
        

        return $this->render('product/bridge.html.twig', [
            "categories" => $categories
        ]);
    }

    /**
     * @Route("/id/{id}", name="_details")
     */
    public function product(int $id = 0, EntityManagerInterface $em, ProductService $productService) {
        $user = $this->security->getUser();
        $product = $em->getRepository(Product::class)->findOneBy(["id" => $id]);
        $visible = $product->getIsVisible();

        $product->setClicks($product->getClicks() + 1);
        $em->persist($product);
        $em->flush();

        $relatedProducts = $productService->getRelatedProducts 
            ($product->getProductType()->getName(), 6, $product->getId());

        $roles = ($user)? $user->getRoles():[];

        return $this->render('product/index.html.twig', [
            "product" => $visible? $product : null,
            "related" => $relatedProducts,
            "verificator" => in_array("ROLE_VERIFICATOR", $roles),
        ]);
    }

    /**
     * @Route("/all/{category}", name="_all")
     */
    public function all (string $category = null, Request $request, ProductService $productService, 
                        PaginatorInterface $paginator, EntityManagerInterface $em) {
        $page = $request->query->get('page', 1);
        $searchParam = $request->query->get('searchParam', null);

        if (isset($category)) {
            $query = $productService->getProductsByCategory($category);
        } else {
            $query = $productService->getAllProducts();
        }

        if (isset($searchParam)) {
            $query = $em->getRepository(Product::class)
            ->addfilterByLike($query, "product", ["title", "description"], $searchParam);
        }

        $pagination = $paginator->paginate($query, $page, 16);

        dump($pagination->getItems());

        return $this->render('product/list.html.twig', [
            "pagination" => $pagination,
            "category" => $category,
            "products" => $pagination->getItems(),
        ]);
    }

    /**
     * @Route("/wishlist", name="_wishlist", methods={"GET"})
     */
    public function wishlist (Request $request, ProductService $productService) {
        $productID = $request->query->get('idProduct', 1);
        $productService->addProductToWishlist($productID);
        
        return JsonResponse::create(["status" => "ok"]);
    }

    /**
     * @Route("/save", name="_save", methods={"POST"})
     */
    public function save(Request $request, ProductService $productService) {
        $productID = $request->request->get('productID', null);
        $values = [];        
        
        $values['title'] = $request->request->get('name', null);
        $values['distribuitor'] = $request->request->get('distribuitor', null);
        $values['stock'] = $request->request->get('stock', null);
        $values['price'] = $request->request->get('price', null);
        $values['author'] = $request->request->get('author', null);
        $values['year'] = $request->request->get('year', null);
        $values['generos'] = json_decode($request->request->get('genres', null));
        $values['description'] = $request->request->get('description', null);
        $values['images'] = json_decode($request->request->get('images', null));

        $values['productType'] = $request->request->get('productType', null);
        $values['productFormat'] = $request->request->get('format', null);
        $values['productEdition'] = $request->request->get('edition', null);

        if (isset($productID)) {
            $productService->editProduct($productID, $values);
        } else {
            $productService->createProduct($values);
        }
        

        return JsonResponse::create(["status" => "ok"]);
    }

    /**
     * @Route("/product/image-upload", name="_image-upload", methods={"POST"})
     */
    public function imageUpload(Request $request) {
        $uploadedFile = $request->files->get('file');
        // $timestamp = (new DateTime())->getTimestamp();
        $uName = '/public/uploads/products/';
        $destination = $this->getParameter('kernel.project_dir').$uName;

        $filename = uniqid().".".$uploadedFile->guessExtension();
        $uploadedFile->move($destination, $filename);

        return JsonResponse::create([
            "status" => "ok",
            "folder" => $uName,
            "filename" => $filename,
            ]);
    }

    /**
     * @Route("/product/check-clicks", name="_check-clicks")
     */
    public function checkProductClicks() {
        
    }

    /**
     * @Route("/product/verification", name="_verification", methods={"POST"})
     */
    public function requestVerification(Request $request, EntityManagerInterface $em) {
        $productID = $request->query->get('idProduct', 1);
        $verification = new Verifications ();
        $verification->setCreatedAt(new DateTime("now", new DateTimeZone('America/Mexico_City') ));
        $verification->setProduct($em->getReference(Product::class, $productID));

        try {
            $em->persist($verification);
            $em->flush();
        }
        catch (UniqueConstraintViolationException $e) {
            return $this->json(["status" => "duplicated"], 409);
        }

        return JsonResponse::create(["status" => "ok"]);
    }
}
