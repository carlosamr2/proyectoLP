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
     * @Route("/hoteles/demo",name="hoteles")
     */
    public function list(){
        /* $response = new Response();
        $response->setContent('<div>Hola Mundo!</div>'); */
        $response=new JsonResponse();
        $response->setData([
            'succes'=> true,
            'data'=> [
                ['id'=>1,'titulo'=>'rwby: roman holiday']
            ]
        ]);
        return $response;
    }
    /**
     * @Route("/hoteles/{id}", name="get_one_hotel", methods={"GET"})
     */
    public function get($id): JsonResponse
    {
        $hotel = $this->hotelRepository->findOneBy(['id' => $id]);

        $data = [
            'id' => $hotel->getId(),
            'nombre' => $hotel->getNombre(),
            'precio' => $hotel->getPrecio()
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }
    /**
     * @Route("/hoteles", name="get_all_hoteles", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $hoteles = $this->hotelRepository->findAll();
        $data = [];

        foreach ($hoteles as $hotel) {
            $data[] = [
                'id' => $hotel->getId(),
                'nombre' => $hotel->getNombre(),
                'precio' => $hotel->getPrecio()
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }
    /**
     * @Route("/hoteles/{id}", name="update_hotel", methods={"PUT"})
     */
    public function update($id, Request $request): JsonResponse
    {
        $hotel = $this->hotelRepository->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), true);

        empty($data['nombre']) ? true : $hotel->setNombre($data['nombre']);
        empty($data['precio']) ? true : $hotel->setPrecio($data['precio']);

        $updatedHotel = $this->hotelRepository->updateHotel($hotel);

        return new JsonResponse($updatedHotel->toArray(), Response::HTTP_OK);
    }
    /**
     * @Route("/hoteles/{id}", name="delete_hotel", methods={"DELETE"})
     */
    public function delete($id): JsonResponse
    {
        $hotel = $this->hotelRepository->findOneBy(['id' => $id]);

        $this->hotelRepository->removeHotel($hotel);

        return new JsonResponse(['status' => 'Hotel deleted'], Response::HTTP_NO_CONTENT);
    }
}