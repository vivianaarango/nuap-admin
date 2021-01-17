<?php
namespace App\Http\Controllers\Api;

use App\Http\Transformers\ListTicketTransformer;
use App\Libraries\Responders\Contracts\ArrayResponseInterface;
use App\Libraries\Responders\Contracts\JsonApiResponseInterface;
use App\Libraries\Responders\ErrorObject;
use App\Libraries\Responders\HttpObject;
use App\Libraries\Responders\JsonApiErrorsFormatter;
use App\Repositories\Contracts\DbTicketRepositoryInterface;
use App\Repositories\Contracts\DbUsersRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Psr\Log\LoggerInterface;
use Illuminate\Http\Request;
use Exception;

/**
 * Class GetTicketByUserController
 * @package App\Http\Controllers\Api
 */
class GetTicketByUserController
{
    /**
     * @type string
     */
    protected const ERROR_TITLE = 'Error';

    /**
     * @type string
     */
    protected const TICKET_NOT_FOUND = 'TICKET_NOT_FOUND';

    /**
     * @type string
     */
    protected const USER_NOT_FOUND = 'USER_NOT_FOUND';

    /**
     * @type string
     */
    protected const GENERAL_ERROR = 'GENERAL_ERROR';

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var HttpObject
     */
    private $httpObject;

    /**
     * @var ErrorObject
     */
    private $errorObject;

    /**
     * @var ArrayResponseInterface
     */
    private $arrayResponse;

    /**
     * @var JsonApiResponseInterface
     */
    private $jsonApiResponse;

    /**
     * @var DbUsersRepositoryInterface
     */
    private $dbUserRepository;

    /**
     * @var DbTicketRepositoryInterface
     */
    private $dbTicketRepository;

    /**
     * @var JsonApiErrorsFormatter
     */
    private $jsonErrorFormat;

    /**
     * GetTicketByUserController constructor.
     * @param LoggerInterface $logger
     * @param ArrayResponseInterface $arrayResponse
     * @param HttpObject $httpObject
     * @param ErrorObject $errorObject
     * @param JsonApiResponseInterface $jsonApiResponse
     * @param DbUsersRepositoryInterface $dbUserRepository
     * @param DbTicketRepositoryInterface $dbTicketRepository
     * @param JsonApiErrorsFormatter $jsonApiErrorsFormatter
     */
    public function __construct(
        LoggerInterface $logger,
        ArrayResponseInterface $arrayResponse,
        HttpObject $httpObject,
        ErrorObject $errorObject,
        JsonApiResponseInterface $jsonApiResponse,
        DbUsersRepositoryInterface $dbUserRepository,
        DbTicketRepositoryInterface $dbTicketRepository,
        JsonApiErrorsFormatter $jsonApiErrorsFormatter
    ) {
        $this->logger = $logger;
        $this->arrayResponse = $arrayResponse;
        $this->httpObject = $httpObject;
        $this->errorObject = $errorObject;
        $this->jsonApiResponse = $jsonApiResponse;
        $this->dbUserRepository = $dbUserRepository;
        $this->dbTicketRepository = $dbTicketRepository;
        $this->jsonErrorFormat = $jsonApiErrorsFormatter;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $token = $request->header('Authorization');
            $token = explode(' ',$token)[1];

            $user = $this->dbUserRepository->getUserByToken($token);

            if (is_null($user)) {
                $error = new ErrorObject();
                $error->setCode(self::USER_NOT_FOUND)
                    ->setTitle(self::ERROR_TITLE)
                    ->setDetail('No se encontroó el usuario.')
                    ->setStatus((string) Response::HTTP_BAD_REQUEST);
                $this->jsonErrorFormat->add($error);

                return $this->jsonApiResponse->respondError($this->jsonErrorFormat, Response::HTTP_BAD_REQUEST);
            }

            $tickets = $this->dbTicketRepository->findByUserID($user->id);

            if (!count($tickets)) {
                $error = new ErrorObject();
                $error->setCode(self::TICKET_NOT_FOUND)
                    ->setTitle(self::ERROR_TITLE)
                    ->setDetail('No se encontraron tickets para este usuario.')
                    ->setStatus((string) Response::HTTP_BAD_REQUEST);
                $this->jsonErrorFormat->add($error);

                return $this->jsonApiResponse->respondError($this->jsonErrorFormat, Response::HTTP_BAD_REQUEST);
            }

            $this->httpObject->setCollection($tickets);

            return $this->arrayResponse->respondWithCollection(
                $this->httpObject,
                new ListTicketTransformer(),
                'data'
            );
        } catch (Exception $exception) {
            $error = new ErrorObject();
            $error->setCode(self::GENERAL_ERROR)
                ->setTitle(self::ERROR_TITLE)
                ->setDetail('Ha ocurrido un error inesperado')
                ->setStatus((string) Response::HTTP_INTERNAL_SERVER_ERROR);
            $this->jsonErrorFormat->add($error);

            return $this->jsonApiResponse->respondError($this->jsonErrorFormat, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}
