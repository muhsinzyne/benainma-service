<?php
namespace MuhsinZyne\BenainmaService\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use MuhsinZyne\BenainmaService\Repositories\LicenseRepository;
use Toastr;

class LicenseController extends Controller
{
    protected $repo;
    protected $request;

    public function __construct(LicenseRepository $repo, Request $request)
    {
        $this->middleware('auth');
        $this->repo    = $repo;
        $this->request = $request;
    }

    public function revoke()
    {
        $ac = Storage::exists('.app_installed') ? Storage::get('.app_installed') : null;
        if (!$ac) {
            return redirect()->route('service.install');
        }

        abort_if(auth()->user()->role_id != 1, 403);

        $this->repo->revoke();

        return redirect()->route('service.install');
    }

    public function revokeModule(Request $request)
    {
        $ac = Storage::exists('.app_installed') ? Storage::get('.app_installed') : null;
        if (!$ac) {
            return redirect()->route('service.install');
        }

        abort_if(auth()->user()->role_id != 1, 403);

        $this->repo->revokeModule($request->all());
        Toastr::success('Your module license revoke successfull');

        return redirect()->back();
    }
}
