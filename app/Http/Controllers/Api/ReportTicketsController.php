<?php
namespace App\Http\Controllers\Api;

use App\Http\Transformers\ReportUsersTicketsTransformer;
use App\Libraries\Responders\Contracts\ArrayResponseInterface;
use App\Libraries\Responders\ErrorObject;
use App\Libraries\Responders\HttpObject;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Psr\Log\LoggerInterface;
use Illuminate\Http\Request;

/**
 * Class ReportTicketsController
 * @package App\Http\Controllers\Api
 */
class ReportTicketsController
{
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
     * ReportNewUsersByRoleController constructor.
     * @param LoggerInterface $logger
     * @param ArrayResponseInterface $arrayResponse
     * @param HttpObject $httpObject
     * @param ErrorObject $errorObject
     */
    public function __construct(
        LoggerInterface $logger,
        ArrayResponseInterface $arrayResponse,
        HttpObject $httpObject,
        ErrorObject $errorObject
    ) {
        $this->logger = $logger;
        $this->arrayResponse = $arrayResponse;
        $this->httpObject = $httpObject;
        $this->errorObject = $errorObject;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $data = DB::select(
            "SELECT
                    tickets.status,
                    SUM(CASE WHEN tickets.status = 'Cerrado' THEN 1 ELSE 0 END) as closed,
                    SUM(CASE WHEN tickets.status = 'Pendiente Administrador' THEN 1 ELSE 0 END) as admin_pending,
                    SUM(CASE WHEN tickets.status = 'Pendiente Usuario' THEN 1 ELSE 0 END) as user_pending
                    FROM tickets
                    GROUP BY tickets.status"

        );

        $response = [
            'closed' => '0',
            'admin_pending' => '0',
            'user_pending' => '0',
        ];

        foreach ($data as $item) {
            $status = $item->status;

            if ($status === 'Pendiente Administrador') {
                $response['admin_pending'] = $item->admin_pending;
            }

            if ($status === 'Pendiente Usuario') {
                $response['user_pending'] = $item->user_pending;
            }

            if ($status === 'Cerrado') {
                $response['closed'] = $item->closed;
            }
        }

        $this->httpObject->setItem($response);

        return $this->arrayResponse->responseWithItem(
            $this->httpObject,
            new ReportUsersTicketsTransformer(),
            'data'
        );
    }
}
