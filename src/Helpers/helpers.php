<?php

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Helilabs\Capital\Factory\ModelFactory;
use Helilabs\Capital\Helpers\CallbackHandler;
use Helilabs\Capital\Exceptions\JsonException;

if (!function_exists('generate_meta')) {
    /**
     * generate response meta according to status
     * @param  mixed $status
     * @param  string $errorMessage
     * @return array
     */
    function generate_meta($status, $errorMessage = 'failure')
    {
        if (empty($status) || $status === 'failure' || (method_exists($status, 'isEmpty') && $status->isEmpty())) {
            return [
                'code' => 0,
                'message' => $errorMessage
            ];
        }

        return [
            'code' => 1,
            'message' => 'success'
        ];
    }
}

if (!function_exists('modelFactory')) {
    /**
     * excute simple model factory logic
     * @param  Illuminate\Http\Request      $request
     * @param  Helilabs\Capital\Factory\ModelFactory $modelFactory
     * @param  Illuminate\Database\Eloquent\Model        $model
     * @param  array        $additionalArgs
     * @return array
     */
    function modelFactory(Request $request, ModelFactory $modelFactory, $scenario ,Model $model, $additionalArgs = [], $successHandler = null, $failureHandler = null)
    {
        $successHandler = $successHandler ?: (new CallbackHandler);
        if(!$successHandler->hasDoneCallback()){
            $successHandler->registerDoneCallback(function ($factory) {
                return response()->json(['meta' => generate_meta('success')], 200);
            });
        }

        $failureHandler = $failureHandler ?: (new CallbackHandler);
        if(!$failureHandler->hasDoneCallback()){
            $failureHandler->registerDoneCallback(function ($factory, $exception) {
                if ($exception instanceof JsonException) {
                    $message = $exception->getDecodedMessage();
                } else {
                    $message = $exception->getMessage();
                }
                return response()->json(['meta' => generate_meta('failure', $message)], 200);
            });
        }

        $modelFactory->setArgs($request->all())
            ->setAdditionalArgs($additionalArgs)
            ->setSuccessHandler($successHandler)
            ->setFailureHandler($failureHandler);

        if( $scenario == 'deleting' ){
            $modelFactory->deleteModel($model);
        }else{
            $modelFactory->saveModel($model);
        }

        return $modelFactory->doTheJob();
    }
}