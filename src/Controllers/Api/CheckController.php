<?php
namespace MuhsinZyne\BenainmaService\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use MuhsinZyne\BenainmaService\Repositories\InitRepository;

class CheckController extends Controller
{
    private $initRepo;
    private $request;

    public function __construct(InitRepository $initRepo, Request $request)
    {
        $this->initRepo = $initRepo;
        $this->request  = $request;
    }

    public function index()
    {
        if (!$this->request->wantsJson()) {
            return 'invalid api call';
        }

        return response()->json($this->initRepo->apiCheck());
    }
}
