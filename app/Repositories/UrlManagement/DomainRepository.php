<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 4/03/2017
 * Time: 3:25 PM
 */

namespace App\Repositories\UrlManagement;


use App\Contracts\Repositories\UrlManagement\DomainContract;
use App\Models\Domain;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DomainRepository implements DomainContract
{
    protected $request;
    protected $domain;

    public function __construct(Domain $domain, Request $request)
    {
        $this->domain = $domain;
        $this->request = $request;
    }


    /**
     * Load all domains and filter them
     * @param array $data
     * @return mixed
     */
    public function filterAll(array $data)
    {
        $length = array_get($data, 'per_page', 25);
        $orderByColumn = array_get($data, 'orderBy', 'id');
        $orderByDirection = array_get($data, 'direction', 'asc');
        $builder = $this->domain;
        $builder = $builder->orderBy($orderByColumn, $orderByDirection);
        if (array_has($data, 'key') && !empty(array_get($data, 'key'))) {
            $key = array_get($data, 'key');
            $builder->where('id', 'LIKE', "%{$key}%");
            $builder->orWhere('full_path', 'LIKE', "%{$key}%");
            $builder->orWhere('name', 'LIKE', "%{$key}%");
            $builder->orWhere('created_at', 'LIKE', "%{$key}%");
            $builder->orWhere('updated_at', 'LIKE', "%{$key}%");
        }
        $domains = $builder->paginate($length);
        if ($domains->count() == 0) {
            $page = 1;
            $this->request->merge(compact(['page']));
            $domains = $builder->paginate($length);
        }
        return $domains;
    }

    /**
     * Load all domains
     * @return mixed
     */
    public function all()
    {
        // TODO: Implement all() method.
    }

    /**
     * Get domain by ID
     * @param $domain_id
     * @param bool $throw
     * @return mixed
     */
    public function get($domain_id, $throw = true)
    {
        if ($throw) {
            $domain = Domain::findOrFail($domain_id);
        } else {
            $domain = Domain::find($domain_id);
        }
        return $domain;
    }

    /**
     * Create new domain
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            $domain = $this->domain->create($data);
        } catch (Exception $exception) {
            DB::rollback();
            throw $exception;
        }
        DB::commit();
        return $domain;
    }

    /**
     * Update existing domain
     * @param Domain $domain
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public function update(Domain $domain, array $data)
    {
        DB::beginTransaction();
        try {
            $domain->update($data);
        } catch (Exception $exception) {
            DB::rollback();
            throw $exception;
        }
        DB::commit();
        return $domain;
    }

    /**
     * Remove an existing domain
     * @param Domain $domain
     * @return mixed
     * @throws Exception
     */
    public function destroy(Domain $domain)
    {
        DB::beginTransaction();
        try {
            $result = $domain->delete();
        } catch (Exception $exception) {
            DB::rollback();
            throw $exception;
        }
        DB::commit();
        return $result;
    }
}