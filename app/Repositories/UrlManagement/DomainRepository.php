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
use Illuminate\Http\Request;

class DomainRepository implements DomainContract
{
    var $request;
    var $domain;

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
    public function filterAll(Array $data)
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
     */
    public function store(Array $data)
    {

    }

    /**
     * Update existing domain
     * @param Domain $domain
     * @param array $data
     * @return mixed
     */
    public function update(Domain $domain, Array $data)
    {
        $domain->name = $data['name'];
        $domain->save();
        return $domain;
    }

    /**
     * Remove an existing domain
     * @param Domain $domain
     * @return mixed
     */
    public function destroy(Domain $domain)
    {
        // TODO: Implement destroy() method.
    }
}