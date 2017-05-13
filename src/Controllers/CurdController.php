<?php

namespace Helilabs\HDH\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Http\Request;

use Helilabs\HDH\CURD\CurdFactoryContract;
use Helilabs\HDH\Repository\RepositoryContract;

Class CurdController extends BaseController{

	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	/**
	 * View Path
	 * @var string
	 */
	public $viewPath;

	/**
	 * used to prepare getting the data
	 * You can manipulate this in counstruct and in handleSourceRepository function
	 * Tip: use handleSourceRepository to manipulate for auth condition because construct has no auth in it
	 * @var Helilabs\HDH\Repository\RepositoryContract
	 */
	public $sourceRepository;

	/**
	 * Success Message to Show when stored, updated successfully
	 * @var string
	 */
	public $successMessage;


	public $afterDeleteCallbacks;

	public function addAfterDeleteCallBack( $callback ){
		$this->afterDeleteCallbacks->push( $callback );
	}

	public function __construct(){
		$this->afterDeleteCallbacks = collect([]);
	}

	/**
	 * This function was added to provide more flexibility when building the model facoty
	 * @param  Helilabs\HDH\Repository\RepositoryContract $sourceRepository used to prepare getting the data
	 * @return Helilabs\HDH\Repository\RepositoryContract $sourceRepository used to prepare getting the
	 */
	public function handleSourceRepository(){
		return $this->sourceRepository;
	}

	/**
	 * This function was added to provide more flexibility when building the model facoty
	 * @return Helilabs\HDH\CURD\CurdFactoryContract Model Facoty used on add & edit
	 */
	public function handleModelFactory( CurdFactoryContract $modelFactory ){
		return $modelFactory;
	}


	/**
	 * Show all models provided with pagination
	 * @return View
	 */
	public function index(){

		return view($this->generateViewPath('index'), [
			'models' => $this->handleSourceRepository( $this->sourceRepository )->get()
		]);
	}


	/**
	 * get the create page view
	 * @return View
	 */
	public function create(){

		return view( $this->generateViewPath( 'create' ) );
	}

	public function store( Request $request, CurdFactoryContract $modelFactory ){
		$redirect = $this->handleModelFactory( $modelFactory )
					->setRequest( $request )
					->setArgs(['action'=> 'new' ])
					->setSuccessMessage( $this->successMessage )
					->doTheJob();

		//TODO: add after store callbacks
		return $redirect;
	}

	public function edit( Request $request, $id ){
		$model = $this->findModel( $id );
		return view( $this->generateViewPath('edit'),[
			'model' => $model
		]);
	}

	public function update( Request $request, CurdFactoryContract $modelFactory, $id ){
		$redirect = $this->handleModelFactory( $modelFactory )
					->setRequest( $request )
					->setArgs(['action'=> 'edit', 'id' => $id ])
					->setSuccessMessage( $this->successMessage )
					->doTheJob();

		//TODO: add after update callbacks
		return $redirect;
	}


	public function show( $id ){
		$model = $this->sourceRepository->where(['id'=>$id])->first();
		return view( $this->generateViewPath('show'),[
			'model' => $model
		]);
	}


	public function destroy( $id ){
		$model = $this->findModel( $id );
		$model->delete();

		$model->delete();

		// exutece after delete callbacks
		if( $this->afterDeleteCallbacks->isEmpty() ){
			return redirect()->back();
		}
		foreach( $this->afterDeleteCallbacks as $callback ){
			$callback();
		}

		return redirect()->back();
	}

	/**
	 * Common function to Find wanted Model
	 * you can override this function to provide policies
	 * @param  int $id the target id
	 * @return ModelInstance     target
	 */
	public function findModel( $id ){
		$model = $this->handleSourceRepository()->where(['id' => $id])->first();

		if( !$model ){
			abort(404);
		}

		return $model;
		
	}

	public function generateViewPath( $targetViewFileName ){
		return $this->viewPath.'.'.$targetViewFileName;
	}

}