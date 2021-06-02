<?php
namespace App\Http\Transformers;

use App\Models\BankAccount;
use League\Fractal\TransformerAbstract;

/**
 * Class BankAccountTransformer
 * @package App\Http\Transformers
 */
class BankAccountTransformer extends TransformerAbstract
{
    /**
     * @param BankAccount $account
     * @return array
     */
    public function transform(BankAccount $account): array
    {
        return [
            'id' => $account->id,
            'owner_name' => $account->owner_name,
            'owner_document' => $account->owner_document,
            'owner_document_type' => $account->owner_document_type,
            'account' => $account->account,
            'account_type' => $account->account_type,
            'bank' => $account->bank,
            'certificate' => $account->certificate,
            'status' => $account->status
        ];
    }
}
