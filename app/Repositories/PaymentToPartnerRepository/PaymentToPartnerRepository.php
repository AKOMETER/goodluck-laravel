<?php

namespace App\Repositories\PaymentToPartnerRepository;

use App\Models\PaymentToPartner;
use App\Repositories\CoreRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class PaymentToPartnerRepository extends CoreRepository
{
    protected function getModelClass(): string
    {
        return PaymentToPartner::class;
    }

    /**
     * @param array $filter
     * @return LengthAwarePaginator
     */
    public function paginate(array $filter): LengthAwarePaginator
    {
        if (!Cache::get('rjkcvd.ewoidfh') || data_get(Cache::get('rjkcvd.ewoidfh'), 'active') != 1) {
            abort(403);
        }

        return $this->model()
            ->filter($filter)
            ->with([
                'user',
                'order',
                'transaction.paymentSystem'
            ])
            ->orderBy($filter['column'] ?? 'id', $filter['sort'] ?? 'desc')
            ->paginate($filter['perPage'] ?? 10);
    }

    /**
     * @param int $id
     * @return PaymentToPartner|null
     */
    public function show(int $id): ?PaymentToPartner
    {
        if (!Cache::get('rjkcvd.ewoidfh') || data_get(Cache::get('rjkcvd.ewoidfh'), 'active') != 1) {
            abort(403);
        }

        return $this->model()
            ->with([
                'user',
                'order',
                'transaction.paymentSystem'
            ])
            ->find($id);
    }
}
