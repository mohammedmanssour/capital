<?php

namespace Helilabs\Capital\Controllers;

use Illuminate\Http\Request;
use Helilabs\Capital\Helpers\CallbackHandler;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Helilabs\Capital\Factory\ModelFactoryContract;
use Helilabs\Capital\Repository\RepositoryContract;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

Abstract Class CrudController extends BaseController{

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

	public function store( Request $request, ModelFactoryContract $modelFactory, CallbackHandler $onSuccessHandler, CallbackHandler $onFailureHandler ){
		return $this->handleModelFactory( $modelFactory )
					->setModel( $this->createModel() )
					->setArgs( $request->all() )
					->setAdditionalArgs(['action'=> 'new' ])
					->setSuccessHandler( $this->handleStoreOnSuccessHandler( $onSuccessHandler ) )
					->setFailureHandler( $this->handleStoreOnFailureHandler( $onFailureHandler ) )
					->doTheJob();
	}

	public function edit( Request $request ){
		$model = $this->findModel( $request );
		return view( $this->generateViewPath('edit'),[
			'model' => $model
		]);
	}

	public function update( Request $request, ModelFactoryContract $modelFactory, CallbackHandler $onSuccessHandler, CallbackHandler $onFailureHandler ){
		$model = $this->findModel( $request );

		return $this->handleModelFactory( $modelFactory )
					->setModel( $model )
					->setArgs( $request->all() )
					->setAdditionalArgs(['action'=> 'edit' ])
					->setSuccessHandler( $this->handleUpdateOnSuccessHandler( $onSuccessHandler ) )
					->setFailureHandler( $this->handleUpdateOnFailureHandler( $onFailureHandler ) )
					->doTheJob();
	}


	public function show( Request $request ){
		$model = $this->findModel( $request );
		return view( $this->generateViewPath('show'),[
			'model' => $model
		]);
	}


	public function destroy(Request $request, CallbackHandler $handler ){
		$model = $this->findModel( $request );
		$model->delete();

		return $this->handleDeleteHandler( $handler );
	}

	public function generateViewPath( $targetViewFileName ){
		return $this->viewPath.$targetViewFileName;
	}

	/**
	 * manipulate the SourceRepository before using it
	 * @param  Helilabs\Capital\Repository\RepositoryContract $sourceRepository
	 * @return Helilabs\Capital\Repository\RepositoryContract $sourceRepository repository after manipulation.
	 */
	public function handleSourceRepository(){
		return $this->sourceRepository;
	}

	/**
	 * manipulate modelFactory before using it
	 * for example set interface or add additional args
	 * @return Helilabs\Capital\CURD\ModelFactoryContract Model Facoty used on add & edit
	 */
	public function handleModelFactory( ModelFactoryContract $modelFactory ){
		return $modelFactory;
	}

	/**
	 * manipulate the store onSucessHandler before using it
	 * for example registering callbacks to be excuted after store success or register done callback
	 * @param  CallbackHandler $handler
	 * @return CallbackHandler $handler
	 */
	public abstract function handleStoreOnSuccessHandler( CallbackHandler $handler );

	/**
	 * manipulate the store onFailure before using it
	 * for example registering callbacks to be excuted after store failre or register done callback
	 * @param  CallbackHandler $handler
	 * @return CallbackHandler $handler
	 */
	public abstract function handleStoreOnFailureHandler( CallbackHandler $handler );

	/**
	 * manipulate the update onSucessHandler before using it
	 * for example registering callbacks to be excuted after update success or register done callback
	 * @param  CallbackHandler $handler
	 * @return CallbackHandler $handler
	 */
	public abstract function handleUpdateOnSuccessHandler( CallbackHandler $handler );

	/**
	 * manipulate the update onFailure before using it
	 * for example registering callbacks to be excuted after update failre or register done callback
	 * @param  CallbackHandler $handler
	 * @return CallbackHandler $handler
	 */
	public abstract function handleUpdateOnFailureHandler( CallbackHandler $handler );

	/**
	 * manipulate the delete handler before using it
	 * for example registering callbacks to be excuted after delete is done or register done callback
	 * @param  CallbackHandler $handler
	 * @return CallbackHandler $handler
	 */
	public abstract function handleDeleteHandler( CallbackHandler $handler );

	/**
	 * Common function to Find Model By id
	 * this function used by edit, update and delete methods.
	 * @param  Request $request object that has all request info
	 * @return Illuminate\Database\Eloquent\Model
	 */
	public abstract function findModel( $request );

	/**
	 * create new model of any type you want
	 * this function used by Store method.
	 * @return Illuminate\Database\Eloquent\Model
	 */
	public abstract function createModel();

}