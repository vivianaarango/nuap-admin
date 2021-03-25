<?php
namespace App\Http\Transformers;

use League\Fractal\TransformerAbstract;

/**
 * Class ReportUsersTicketsTransformer
 * @package App\Http\Transformers
 */
class ReportUsersTicketsTransformer extends TransformerAbstract
{
    /**
     * @param array $item
     * @return array
     */
    public function transform(array $item): array
    {
        return [
            'closed' => $item['closed'],
            'admin_pending' => $item['admin_pending'],
            'user_pending' => $item['user_pending']
        ];
    }
}
