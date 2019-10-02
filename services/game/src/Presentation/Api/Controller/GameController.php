<?php

declare(strict_types=1);

namespace App\Presentation\Api\Controller;

use App\Infrastructure\Repository\Game\GameRepository;
use App\Infrastructure\Service\Game\BufferGame\Create\Command;
use App\Application\Service\Game\BufferGame\Create\Handler;
use App\Presentation\Api\Entity\ApiMessage;
use App\Presentation\Api\Entity\BatchStatus;
use App\Presentation\Api\Service\ViolationFormatter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Swagger\Annotations as SWG;

/**
 * Class GameController
 * @package App\Presentation\Api\Controller
 * @Route("/games", name="game_")
 */
class GameController extends AbstractController
{
    private $serializer;
    private $validator;
    private $violationFormatter;

    public function __construct(
        ValidatorInterface $validator,
        ObjectNormalizer $objectNormalizer,
        ViolationFormatter $violationFormatter
    ) {
        $this->serializer = new Serializer([$objectNormalizer], [new JsonEncoder()]);
        $this->validator = $validator;
        $this->violationFormatter = $violationFormatter;
    }

    /**
     * @Route("", name="add", methods={"POST"})
     * @SWG\Post(
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     tags={"Game"},
     *     @SWG\Response(
     *          response=201,
     *          description="Create game",
     *     ),
     *     @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          required=true,
     *          format="application/json",
     *          @SWG\Schema(
     *               type="object",
     *               @SWG\Property(property="sport", type="string", example="Футбол"),
     *               @SWG\Property(property="league", type="string", example="Лига чемпионов"),
     *               @SWG\Property(property="host_team", type="string", example="Барселона"),
     *               @SWG\Property(property="guest_team", type="string", example="Реал Мадрид"),
     *               @SWG\Property(property="date", type="string", example="2019-01-01 01:01:00"),
     *               @SWG\Property(property="language", type="string", example="русский"),
     *               @SWG\Property(property="source", type="string", example="sportdata.com")
     *         )
     *     )
     * )
     * @param Request $request
     * @param Handler $handler
     * @return JsonResponse
     */
    public function add(Request $request, Handler $handler): JsonResponse
    {
        $gameData = json_decode($request->getContent(), true);
        $command = new Command($gameData);

        $violationList = $this->validator->validate($command);
        if ($violationList->count()) {
            $violations = $this->violationFormatter->format($violationList);

            return $this->json($violations, ApiMessage::ERROR_CODE);
        }

        $handler->handle($command);

        return new JsonResponse(
            [
                'status' => ApiMessage::SUCCESS_CREATED_CODE,
                'message' => ApiMessage::SUCCESS_MESSAGE
            ],
            ApiMessage::SUCCESS_CREATED_CODE,
        );
    }

    /**
     * @Route("", name="add_batch", methods={"PATCH"})
     * @SWG\Patch(
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     tags={"Game"},
     *     @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          required=true,
     *          format="application/json",
     *          @SWG\Schema(
     *               type="array",
     *               @SWG\Items(
     *                  @SWG\Property(property="sport", type="string", example="Футбол"),
     *                  @SWG\Property(property="league", type="string", example="Лига чемпионов"),
     *                  @SWG\Property(property="host_team", type="string", example="Барселона"),
     *                  @SWG\Property(property="guest_team", type="string", example="Реал Мадрид"),
     *                  @SWG\Property(property="date", type="string", example="2019-01-01 01:01:00"),
     *                  @SWG\Property(property="language", type="string", example="русский"),
     *                  @SWG\Property(property="source", type="string", example="sportdata.com")
     *               )
     *         )
     *     ),
     *     @SWG\Response(
     *          response=207,
     *          description="Create games",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="status", type="string", example="200"),
     *              @SWG\Property(property="message", type="string", example=""),
     *              @SWG\Property(property="body", type="array",
     *                  @SWG\Items(
     *                      @SWG\Property(property="sport", type="string", example="Футбол"),
     *                      @SWG\Property(property="league", type="string", example="Лига чемпионов"),
     *                      @SWG\Property(property="host_team", type="string", example="Барселона"),
     *                      @SWG\Property(property="guest_team", type="string", example="Реал Мадрид"),
     *                      @SWG\Property(property="date", type="string", example="2019-01-01 01:01:00"),
     *                      @SWG\Property(property="language", type="string", example="русский"),
     *                      @SWG\Property(property="source", type="string", example="sportdata.com")
     *                  )
     *              )
     *          )
     *      )
     * )
     * @param Request $request
     * @param Handler $handler
     * @return JsonResponse
     */
    public function addBatch(Request $request, Handler $handler): JsonResponse
    {
        $batch = json_decode($request->getContent(), true);
        $statuses = [];
        foreach ($batch as $gameData) {
            $command = new Command($gameData);
            $violationList = $this->validator->validate($command);

            $status = new BatchStatus();
            $status->body = $gameData;
            $status->status = ApiMessage::SUCCESS_CREATED_CODE;
            $status->message = ApiMessage::SUCCESS_MESSAGE;

            if ($violationList->count()) {
                $violations = $this->violationFormatter->format($violationList);
                $status->status = ApiMessage::ERROR_CODE;
                $status->message = $violations;
                $statuses[] = $status;
                continue;
            }

            try {
                $handler->handle($command);
            } catch (\Exception $exception) {
                $status->status = ApiMessage::ERROR_CODE;
                $status->message = $exception->getMessage();
            }

            $statuses[] = $status;
        }

        return new JsonResponse(
            $this->serializer->serialize($statuses, 'json'),
            ApiMessage::BATCH_SUCCESS_CODE,
            [],
            true
        );
    }

    /**
     * @Route("/random", name="get_random", methods={"GET"})
     * @SWG\Get(
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     tags={"Game"},
     *     @SWG\Response(
     *          response=200,
     *          description="Get random game",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="sport", type="string", example="Футбол"),
     *              @SWG\Property(property="league", type="string", example="Лига чемпионов"),
     *              @SWG\Property(property="host_team", type="string", example="Барселона"),
     *              @SWG\Property(property="guest_team", type="string", example="Реал Мадрид"),
     *              @SWG\Property(property="date", type="string", example="2019-01-01 01:01:00"),
     *              @SWG\Property(property="language", type="string", example="русский"),
     *              @SWG\Property(property="source", type="string", example="sportdata.com")
     *          )
     *     ),
     *     @SWG\Parameter(
     *          name="filters[source]",
     *          in="query",
     *          type="string",
     *          description="Game data source. Example: sportdata.com"
     *     ),
     *     @SWG\Parameter(
     *          name="filters[from]",
     *          in="query",
     *          type="string",
     *          description="Game date from filter. Example: 2019-01-01 00:01:00"
     *     ),
     *     @SWG\Parameter(
     *          name="filters[to]",
     *          in="query",
     *          type="string",
     *          description="Game date from filter. Example: 2019-01-01 05:01:00"
     *     )
     * )
     * @param Request $request,
     * @param GameRepository $gameRepository,
     * @return JsonResponse
     */
    public function getRandom(
        Request $request,
        GameRepository $gameRepository
    ): JsonResponse {
        $filters = $request->query->get('filters') ?? [];
        $game = $gameRepository->getRandom($filters);

        return $this->json(
            [
                'id' => $game->getId()->getValue(),
                'sport' => $game->getSport()->getName(),
                'league' => $game->getLeague()->getName(),
                'host_team' => $game->getHostTeam()->getName(),
                'guest_team' => $game->getGuestTeam()->getName(),
                'date' => $game->getDate()->format('Y-m-d H:i:s'),
                'language' => $game->getLanguage(),
                'source' => $game->getSource(),
                'buffer_games' => count($game->getBufferGames()),
            ]
        );
    }
}
