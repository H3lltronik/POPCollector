<?php

namespace App\Controller;

use DateTime;
use DateTimeZone;
use App\Entity\Product;
use App\Entity\SeBusca;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
/**
 * @Route("/se-busca", name="se_busca")
 */
class SeBuscaController extends AbstractController {

    public function __construct(EntityManagerInterface $em, Security $security) {
        $this->em = $em;
        $this->security = $security;
    }
    
    /**
     * @Route("/create", name="_create")
     */ 
    public function create(Request $request) {
        return $this->render('se_busca/index.html.twig', [
            'controller_name' => 'SeBuscaController',
        ]);
    }

    /**
     * @Route("/list/", name="_list")
     */ 
    public function list( Request $request, PaginatorInterface $paginator) {
        $page = $request->query->get('page', 1);
        $query = $this->em->getRepository(SeBusca::class)->createQueryBuilder("product");
        $query->innerJoin("App\Entity\User", "publisher", Join::WITH, "publisher = product.publisher");
        $query->andWhere("publisher.isActive = 1");
        $query->orderBy('product.created_at', 'ASC');

        $pagination = $paginator->paginate($query, $page, 16);

        return $this->render('se_busca/list.html.twig', [
            'pagination' => $pagination,
            "category" => "Busquedas",
            'busquedas' => $pagination->getItems(),
        ]);
    }

    /**
     * @Route("/list/own", name="_list_own")
     */ 
    public function listOwn( Request $request, PaginatorInterface $paginator) {
        $user = $this->security->getUser();
        $page = $request->query->get('page', 1);
        $query = $this->em->getRepository(SeBusca::class)->createQueryBuilder("product");
        $query->orderBy('product.created_at', 'ASC');
        $query->andWhere('product.publisher = :val');
        $query->setParameter("val", $user->getId());

        $pagination = $paginator->paginate($query, $page, 16);

        return $this->render('se_busca/list.html.twig', [
            'pagination' => $pagination,
            "category" => "Busquedas",
            'busquedas' => $pagination->getItems(),
        ]);
    }

    /**
     * @Route("/id/{id}", name="_details")
     */ 
    public function details(int $id = 0) {
        $busqueda = $this->em->getRepository(SeBusca::class)->findOneBy(["id" => $id]);
        $user = $this->security->getUser();
        $visible = $busqueda->getIsVisible();

        dump($busqueda);

        return $this->render('se_busca/details.html.twig', [
            'busqueda' => $busqueda,
            'products' => $user->getProducts()->toArray(),
        ]);
    }

    /**
     * @Route("/create/image-upload", name="_image-upload", methods={"POST"})
     */ 
    public function images(Request $request) {
        $uploadedFile = $request->files->get('file');
        // $timestamp = (new DateTime())->getTimestamp();
        $uName = '/public/uploads/busquedas/';
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
     * @Route("/create/save", name="_save")
     */ 
    public function save(Request $request) {
        $title = $request->request->get('title', null);
        $description = $request->request->get('description', null);
        $images = json_decode($request->request->get('images', null));

        $busqueda = new SeBusca();
        $busqueda->setCreatedAt(new DateTime("now", new DateTimeZone('America/Mexico_City') ));
        $busqueda->setTitle($title);
        $busqueda->setDescription($description);
        $busqueda->setImages($images);
        $busqueda->setIsActive(true);
        $busqueda->setIsVisible(true);
        $user = $this->security->getUser();
        $busqueda->setPublisher($user);
        $this->em->persist($busqueda);
        $this->em->flush();

        return JsonResponse::create(["status" => "ok"]);

    }

    /**
     * @Route("/recommendation/save", name="_recommendation_save")
     */ 
    public function addRecommendation (Request $request) {
        $productID = $request->request->get('recommended', null);
        $seBuscaID = $request->request->get('sebuscaid', null);

        $product = $this->em->getRepository(Product::class)->findOneBy(["id" => $productID]);
        $seBusca = $this->em->getRepository(SeBusca::class)->findOneBy(["id" => $seBuscaID]);

        $seBusca->addRecommendation ($product);
        $this->em->persist($seBusca);
        $this->em->flush();

        return JsonResponse::create(["status" => "ok"]);
    }

    /**
     * @Route("/se-busca/cerrar", name="_close", methods={"POST"})
     */ 
    public function cerrar (Request $request) {
        $seBuscaID = $request->request->get('sebuscaid', null);
        $user = $this->security->getUser();
        $seBusca = $this->em->getRepository(SeBusca::class)->findOneBy(["id" => $seBuscaID]);
        dump($seBusca);
        dump($seBuscaID);
        
        if ($user->getId() == $seBusca->getPublisherID()) {
            $seBusca->setIsActive(false);
            $this->em->persist($seBusca);
            $this->em->flush();
        } else {
            return $this->json("NOT AUTHORIZED", 403);
        }
        return $this->forward('App\Controller\SeBuscaController::details', [
            'id'  => $seBuscaID,
        ]);
    }
}
