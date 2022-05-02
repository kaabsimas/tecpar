<?php

namespace App\Controller;

use App\Service\BlockService;
use Pagerfanta\Exception\LessThan1CurrentPageException;
use Pagerfanta\Exception\OutOfRangeCurrentPageException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ResultsController extends AbstractController
{
    public function index(Request $request, BlockService $blockService)
    {
        try{
            $page = $request->query->get('page') ?? 1;
            $filter = $request->query->get('tryeslt') ?? $request->query->get('tryesmt') ?? null;

            $items = $blockService->paginate($page, $filter);

            return new JsonResponse($items, JsonResponse::HTTP_OK);
        } catch(OutOfRangeCurrentPageException $exception) {
            return new JsonResponse(["mensagem" => $exception->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        } catch(LessThan1CurrentPageException $exception) {
            return new JsonResponse(["mensagem" => $exception->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
}