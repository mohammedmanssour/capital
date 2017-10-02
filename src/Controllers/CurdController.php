<?php

namespace Helilabs\Capital\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Http\Request;

use Helilabs\Capital\CURD\CurdFactoryContract;
use Helilabs\Capital\Repository\RepositoryContract;

Abstract Class CurdController extends BaseController{

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
	 * @var Helilabs\Capital\Repository\RepositoryContract
	 */
	public $sourceRepository;

	/**
	 * Handler to handle things after store is done successfully
	 * @var Helilabs\Capital\Helpers\CallbackHandler
	 */
	public $onStoreSuccessHandler;

	/**
	 * Handler to handle things after store failure
	 * @var Helilabs\Capital\Helpers\CallbackHandler
	 */
	public $onStoreFailureHandler;

	/**
	 * Handler to handle things after update is done successfully
	 * @var Helilabs\Capital\Helpers\CallbackHandler
	 */
	public $onUpdateSuccessHandler;

	/**
	 * Handler to handle things after update failure
	 * @var Helilabs\Capital\Helpers\CallbackHandler
	 */
	public $onUpdateFailureHandler;

	/**
	 * Handler to handle things after deletion is done
	 * @var Helilabs\Capital\Helpers\CallbackHandler
	 */
	public $deleteHandler;


	public function __construct(){

		$this->initStoreHandlers();

		$this->initUpdateHandlers();

		$this->initDeleteHandler();

	}

	/**
	 * This function was added to provide more flexibility when building the model facoty
	 * @param  Helilabs\Capital\Repository\RepositoryContract $sourceRepository used to prepare getting the data
	 * @return Helilabs\Capital\Repository\RepositoryContract $sourceRepository used to prepare getting the
	 */
	public function handleSourceRepository(){
		return $this->sourceRepository;
	}

	/**
	 * This function was added to provide more flexibility when building the model facoty
	 * @return Helilabs\Capital\CURD\CurdFactoryContract Model Facoty used on add & edit
	 */
	public function handleModelFactory( CurdFactoryContract $modelFactory ){
		return $modelFactory;
	}

	public function initStoreHandlers(){
		$this->onStoreSuccessHandler = new CallbackHandler;
		$this->onStoreFailureHandler = new CallbackHandler;
	}

	public function initUpdateHandlers(){
		$this->onUpdateSuccessHandler = new CallbackHandler;
		$this->onUpdateFailureHandler = new CallbackHandler;
	}

	public function initDeleteHandler(){
		$this->deleteHandler = new CallbackHandler;
	}


	/**
	 * Show all models provided with pagination
	 * @return View
	 */
	public function index(){

		return view($this->generateViewPath('index'), [
			'models' => $this->handleSourceRepository( )->get()
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
		return $this->handleModelFactory( $modelFactory )
					->setArgs( collect( $request->all() ) )
					->setAdditionalArgs(['action'=> 'new' ])
					->setSuccessHandler( $this->onStoreSuccessHandler )
					->setFailureHandler( $this->onStoreFailureHandler )
					->doTheJob();
	}

	public function edit( Request $request, $id ){
		$model = $this->findModel( $id );
		return view( $this->generateViewPath('edit'),[
			'model' => $model
		]);
	}

	public function update( Request $request, CurdFactoryContract $modelFactory, $id ){
		return $this->handleModelFactory( $modelFactory )
					->setArgs( collect( $request->all() ) )
					->setAdditionalArgs(['action'=> 'edit', 'id' => $id ])
					->setSuccessHandler( $this->onUpdateSuccessHandler )
					->setFailureHandler( $this->onUpdateFailureHandler )
					->doTheJob();
	}


	public function show( $id ){
		$model = $this->findModel( $id );
		return view( $this->generateViewPath('show'),[
			'model' => $model
		]);
	}


	public function destroy( $id ){
		$model = $this->findModel( $id );
		$model->delete();

		return $this->deleteHandler->handle();
	}

	public function generateViewPath( $targetViewFileName ){
		return $this->viewPath.'.'.$targetViewFileName;
	}

	/**
	 * Common function to Find wanted Model
	 * you can override this function to provide policies
	 * @param  int $id the target id
	 * @return Illuminate\Database\Eloquent\Model
	 */
	public abstract function findModel( $id );

}