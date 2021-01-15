<?php
namespace App\Http\Controllers\Api;

use App\Http\Transformers\LocationTransformer;
use App\Http\Transformers\TicketTransformer;
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
 * Class LocationController
 * @package App\Http\Controllers\Api
 */
class GetMessagesFromTicketController
{
    /**
     * @type string
     */
    protected const ERROR_TITLE = 'Error';

    /**
     * @type string
     */
    protected const MESSAGES_NOT_FOUND = 'MESSAGES_NOT_FOUND';

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
     * GetMessagesFromTicketController constructor.
     * @param LoggerInterface $logger
     * @param ArrayResponseInterface $arrayResponse
     * @param HttpObject $httpObject
     * @param ErrorObject $errorObject
     * @param JsonApiResponseInterface $jsonApiResponse
     * @param DbUsersRepositoryInterface $dbUserRepository
     * @param JsonApiErrorsFormatter $jsonApiErrorsFormatter
     * @param DbTicketRepositoryInterface $dbTicketRepository
     */
    public function __construct(
        LoggerInterface $logger,
        ArrayResponseInterface $arrayResponse,
        HttpObject $httpObject,
        ErrorObject $errorObject,
        JsonApiResponseInterface $jsonApiResponse,
        DbUsersRepositoryInterface $dbUserRepository,
        JsonApiErrorsFormatter $jsonApiErrorsFormatter,
        DbTicketRepositoryInterface $dbTicketRepository
    ) {
        $this->logger = $logger;
        $this->arrayResponse = $arrayResponse;
        $this->httpObject = $httpObject;
        $this->errorObject = $errorObject;
        $this->jsonApiResponse = $jsonApiResponse;
        $this->dbUserRepository = $dbUserRepository;
        $this->jsonErrorFormat = $jsonApiErrorsFormatter;
        $this->dbTicketRepository = $dbTicketRepository;
    }

    /**
     * @param Request $request
     * @param int $ticketID
     * @return JsonResponse
     */
    public function __invoke(Request $request, int $ticketID): JsonResponse
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

            $data = $this->dbTicketRepository->findMessagesByTicket($ticketID);

            foreach ($data as $item){
                $senderDate = $item->sender_date;
                $item->sender_date = $this->formatDate($senderDate);
                $item->role = $this->formatHour($senderDate);
            }

            $this->httpObject->setCollection($data);

            return $this->arrayResponse->respondWithCollection(
                $this->httpObject,
                new TicketTransformer(),
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

    /**
     * @param string $date
     * @return string
     */
    private function formatDate(string $date): string
    {
        $date = explode(' ',$date);
        $hora = explode(':',$date[1]);
        if (!isset($hora[2])) $hora[2] = '00';
        $hora = date('g:i a',mktime($hora[0],$hora[1],$hora[2],0,0,0));
        $date = explode('-', $date[0]);
        $dias = array('Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado');
        $meses = array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
        $date = $dias[date('w',mktime(0,0,0,$date[1],$date[2],$date[0]))].', '.intval($date[2]).' '.$meses[intval($date[1])];
        return $date;
    }

    /**
     * @param string $date
     * @return string
     */
    private function formatHour(string $date): string
    {
        $date = explode(' ',$date);
        $hora = explode(':',$date[1]);
        if (!isset($hora[2])) $hora[2] = '00';
        $hora = date('g:i a',mktime($hora[0],$hora[1],$hora[2],0,0,0)); return $hora;
    }
}
