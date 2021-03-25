<?php
namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;
use stdClass;

/**
 * Class ReportUserLoginTransformer
 * @package App\Http\Transformers
 */
class ReportUserLoginTransformer extends TransformerAbstract
{
    /**
     * @param stdClass $item
     * @return array
     */
    public function transform(stdClass $item): array
    {
        return [
            'role' => $item->user_type,
            'january' => $item->Ene,
            'february' => $item->Feb,
            'march' => $item->Mar,
            'april' => $item->Abr,
            'may' => $item->May,
            'june' => $item->Jun,
            'july' => $item->Jul,
            'august' => $item->Ago,
            'september' => $item->Sep,
            'october' => $item->Oct,
            'november' => $item->Nov,
            'december' => $item->Dic
        ];
    }
}
