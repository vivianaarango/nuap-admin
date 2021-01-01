<?php
namespace App\Http\Controllers\Api;

use App\Http\Transformers\ReportUserLoginTransformer;
use App\Http\Transformers\ReportUserRoleTransformer;
use App\Libraries\Responders\Contracts\ArrayResponseInterface;
use App\Libraries\Responders\ErrorObject;
use App\Libraries\Responders\HttpObject;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Psr\Log\LoggerInterface;
use Illuminate\Http\Request;

/**
 * Class ReportLoginUsersController
 * @package App\Http\Controllers\Api
 */
class ReportLoginUsersController
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
        $year = (string) date("Y");
        $data = DB::select(
            "SELECT
                    session_logs.user_type,
                    SUM(CASE WHEN MONTH(session_logs.login_date) = 1 THEN 1 ELSE 0 END) as Ene,
                    SUM(CASE WHEN MONTH(session_logs.login_date) = 2 THEN 1 ELSE 0 END) as Feb,
                    SUM(CASE WHEN MONTH(session_logs.login_date) = 3 THEN 1 ELSE 0 END) as Mar,
                    SUM(CASE WHEN MONTH(session_logs.login_date) = 4 THEN 1 ELSE 0 END) as Abr,
                    SUM(CASE WHEN MONTH(session_logs.login_date) = 5 THEN 1 ELSE 0 END) as May,
                    SUM(CASE WHEN MONTH(session_logs.login_date) = 6 THEN 1 ELSE 0 END) as Jun,
                    SUM(CASE WHEN MONTH(session_logs.login_date) = 7 THEN 1 ELSE 0 END) as Jul,
                    SUM(CASE WHEN MONTH(session_logs.login_date) = 8 THEN 1 ELSE 0 END) as Ago,
                    SUM(CASE WHEN MONTH(session_logs.login_date) = 9 THEN 1 ELSE 0 END) as Sep,
                    SUM(CASE WHEN MONTH(session_logs.login_date) = 10 THEN 1 ELSE 0 END) as Oct,
                    SUM(CASE WHEN MONTH(session_logs.login_date) = 11 THEN 1 ELSE 0 END) as Nov,
                    SUM(CASE WHEN MONTH(session_logs.login_date) = 12 THEN 1 ELSE 0 END) as Dic
                    FROM session_logs
                    WHERE session_logs.login_date BETWEEN '" .$year. "-01-01' AND '" .$year. "-12-31 23:59'
                    GROUP BY session_logs.user_type"

        );

        $this->httpObject->setCollection($data);

        return $this->arrayResponse->respondWithCollection(
            $this->httpObject,
            new ReportUserLoginTransformer(),
            'data'
        );
    }
}
