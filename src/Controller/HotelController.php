<?php

namespace App\Controller;

use App\Repository\HotelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class HotelController extends AbstractController
{
    private $hotelRepository;

    public function __construct(HotelRepository $hotelRepository)
    {
        $this->hotelRepository = $hotelRepository;
    }

    /**
     * @Route("/hoteles/add", name="add_hotel", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $nombre = $data['nombre'];
        $precio = $data['precio'];


        if (empty($nombre) || empty($precio)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $this->hotelRepository->saveHotel($nombre, $precio);

        return new JsonResponse(['status' => 'Hotel created!'], Response::HTTP_CREATED);
    }
    /**
     * @Route("/hotel/list",name="hoteles")
     */
    public function list(){
        /* $response = new Response();
        $response->setContent('<div>Hola Mundo!</div>'); */
        $response=new JsonResponse();
        $response->setData([
            'succes'=> true,
            'data'=> [
                ['id'=>1,'titulo'=>'roman holiday']
            ]
        ]);
        return $response;
    }
}