<?php

namespace Modules\Auth\Traits;

trait ApiResponser
{

    protected function successResponse($data, $message = null, $code = 200)
	{
		return response()->json([
			'status' => 'Success',
			'message' => $message,
			'data' => $data
		], $code);
	}

    protected function errorResponse($message = null, $code, $data = null)
	{
		$validCodes = range(100, 599);

		if (!is_int($code) || !in_array($code, $validCodes)) {
			$code = 500;
		}
		return response()->json([
			'status' => 'Error',
			'message' => $message,
			'data' => $data,
			'code' => $code
		], $code);
	}

}