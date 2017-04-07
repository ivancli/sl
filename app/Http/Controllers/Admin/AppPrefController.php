<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Repositories\Admin\AppPrefContract;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppPrefController extends Controller
{
    protected $request;
    protected $appPrefRepo;

    public function __construct(Request $request,
                                AppPrefContract $appPrefContract)
    {
        $this->request = $request;
        $this->appPrefRepo = $appPrefContract;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($this->request->ajax()) {
            $appPrefs = $this->appPrefRepo->all();
            $status = true;
        }
        if ($this->request->ajax()) {
            return compact(['status', 'appPrefs']);
        } else {
            return view('app.admin.app_pref.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        /*TODO add validation to appPrefs*/
        foreach ($this->request->get('appPrefs') as $appPref) {
            $pref = $this->appPrefRepo->store($appPref);
        }
        $status = true;
        return compact(['status']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
