<?php

namespace Modules\TrainingCategory\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ServiceContainer;
use App\Services\CommonService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\TrainingCategory\Models\TrainingCategory;
use Modules\TrainingCategory\Repositories\ModelRepository;

class TrainingCategoryController extends Controller
{
    public function __construct(
        public ServiceContainer $services,
        public CommonService $commonService,
        public ModelRepository $repository
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $q = $request->q;
        if ($q) {
            $items = $this->repository->search($q, 10);
        } else {
            $items = $this->repository->all(10);
        }
        $activeLangsCount = $this->repository->all_active()->count();
        return view('trainingcategory::index', compact('items', 'activeLangsCount', 'q'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $languages = $this->services->langRepository->all_active();
        return view('trainingcategory::create', compact('languages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        return $this->commonService->executeSafely(function () use ($request) {
            $this->services->crudService->create(new TrainingCategory(), $request);
            return redirect()->route('trainingcategory.index')->with('status', 'Kateqoriya uğurla əlavə edildi');
        }, 'trainingcategory.index');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('lang::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, TrainingCategory $trainingcategory)
    {
        $languages = $this->services->langRepository->all_active();
        return view('trainingcategory::edit', compact('trainingcategory', 'languages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TrainingCategory $trainingcategory): RedirectResponse
    {
        return $this->commonService->executeSafely(function () use ($request, $trainingcategory) {
            $this->services->crudService->update($trainingcategory, $request);
            return redirect()->route('trainingcategory.index')->with('status', 'Kateqoriya uğurla yeniləndi');
        }, 'trainingcategory.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Burada destroy metodunu implement edə bilərsiniz
    }

    public function changeStatusTrue($id)
    {
        return $this->commonService->changeStatus($id, $this->repository, $this->services->statusService, new TrainingCategory(), true, 'trainingcategory.index');
    }

    public function changeStatusFalse($id)
    {
        return $this->commonService->changeStatus($id, $this->repository, $this->services->statusService, new TrainingCategory(), false, 'trainingcategory.index');
    }

    public function delete_selected_items(Request $request)
    {
        return $this->commonService->deleteSelectedItems($this->repository, $request, $this->services->removeService, 'trainingcategory.index');
    }
      public function changeOrderUp($id) {
        $this->services->orderService->up(new TrainingCategory, $id);
        return redirect()->back();
    }

    public function changeOrderDown($id) {
        $this->services->orderService->down(new TrainingCategory, $id);
        return redirect()->back();
    }
}
