<?php

namespace App\Http\Controllers;

use App\Enums\Module;
use App\Helpers\RequestParameterParser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;
use Throwable;

abstract class AbstractController extends Controller
{
    /**
     * Arbitrary data storage for the controller.
     */
    protected array $data = [];

    /**
     * The module associated with the controller.
     */
    protected Module $module;

    /**
     * Request parameter parser instance.
     */
    protected RequestParameterParser $parser;

    public function __construct()
    {
        $this->parser = new RequestParameterParser();
    }

    /**
     * Get the module name.
     */
    protected function getModuleName(): string
    {
        return ($this->module ?? Module::UNKNOWN)->value;
    }

    /**
     * Get the view and route prefix based on the module name.
     */
    protected function getViewPrefix(): string
    {
        return Str::plural($this->getModuleName());
    }

    /**
     * Get the data class from the repository.
     */
    protected function getDataClass()
    {
        return $this->repository->getDataClass();
    }

    /**
     * Get the store data class from the repository.
     */
    protected function getStoreDataClass()
    {
        return $this->repository->getStoreDataClass();
    }

    /**
     * Get the update data class from the repository.
     */
    protected function getUpdateDataClass()
    {
        return $this->repository->getUpdateDataClass();
    }

    /**
     * List resources.
     *
     * @throws RepositoryException
     */
    public function index(Request $request): InertiaResponse
    {
        $dataClass = $this->getDataClass();
        $limit = $request->integer('limit', 15);
        $only = $this->parser->getOnly($request);

        $collection = $dataClass::collect(
            $this->repository->paginate($limit > 0 ? $limit : 15)
        );

        if (! empty($only)) {
            $collection = $collection->only(...$only);
        }

        return Inertia::render($this->getViewPrefix() . '/index', [
            $this->getViewPrefix() => $collection,
            'filters' => $request->only(['limit', 'page', 'sort', 'where', 'orWhere']),
        ]);
    }

    /**
     * Show resource creation view.
     */
    public function create(): InertiaResponse
    {
        return Inertia::render($this->getViewPrefix() . '/create');
    }

    /**
     * Store a new resource.
     *
     * @throws ValidatorException|Throwable
     */
    public function store(Request $request): RedirectResponse
    {
        $dataClass = $this->getStoreDataClass();
        $input = $dataClass::validate($request->all());

        $this->repository->create($input);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __(ucfirst($this->getModuleName()) . ' created.')
        ]);

        return to_route($this->getViewPrefix() . '.index');
    }

    /**
     * Show a resource.
     *
     * @throws RepositoryException
     */
    public function show(Request $request, int $id): InertiaResponse
    {
        $item = $this->repository->find($id);
        $only = $this->parser->getOnly($request);

        $data = $item->getData();
        if (! empty($only)) {
            $data = $data->only(...$only);
        }

        return Inertia::render($this->getViewPrefix() . '/show', [
            $this->getModuleName() => $data,
        ]);
    }

    /**
     * Edit a resource.
     *
     * @throws RepositoryException
     */
    public function edit(Request $request, int $id): InertiaResponse
    {
        $item = $this->repository->find($id);

        return Inertia::render($this->getViewPrefix() . '/edit', [
            $this->getModuleName() => $item->getData(),
        ]);
    }

    /**
     * Update a resource.
     *
     * @throws ValidatorException|Throwable
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $item = $this->repository->find($id);
        $dataClass = $this->getUpdateDataClass();
        $payload = [
            ...$request->except(['updated_at', 'created_at', 'deleted_at']),
            'id' => $item->getKey(),
        ];

        $payload = $dataClass::validate($payload);

        $this->repository->update($payload, $item->getKey());

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __(ucfirst($this->getModuleName()) . ' updated.')
        ]);

        return to_route($this->getViewPrefix() . '.index');
    }

    /**
     * Destroy a resource.
     *
     * @throws Throwable
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->repository->delete($id);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __(ucfirst($this->getModuleName()) . ' deleted.')
        ]);

        return to_route($this->getViewPrefix() . '.index');
    }
}
