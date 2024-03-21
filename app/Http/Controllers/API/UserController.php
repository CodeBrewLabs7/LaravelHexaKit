<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDocuments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends BaseController
{
    public function getProfile(Request $request)
    {
        $user = Auth::user();

        return $this->sendResponse(
            $user,
            trans('Profile fetched successfully.', ['attribute' => 'Profile data'])
        );
    }

    public function editProfile(Request $request)
    {
        $data = [];

        $user = Auth::user();

        $rules = [
            'phone_number' => 'unique:users,phone_number,' . $user->id,
            'email' => 'unique:users,email,' . $user->id
        ];

        $validator = Validator::make($request->all(), $rules);

        $data = [];
        if ($validator->fails()) {
            return $this->sendError((object) $data, $validator->getMessageBag()->first());
        }

        DB::transaction(function () use ($request, $user) {

            User::where('id', $user->id)->update(
                $request->only([
                    'image',
                    'name',
                    'country_flag',
                    'country_code',
                    'phone_number',
                    'email',
                ])
            );
            // Hash the password if it's present in the request
            if ($request->has('password')) {
                $user->password = Hash::make($request->password);
                $user->save();
            }
            return $user;
        });

        if ($user) {
            $updateUser = User::find($user->id);

            return $this->sendResponse(
                $updateUser,
                trans('User profile updated successfully.', ['attribute' => 'User Profile Updated'])
            );
        }

        return $this->sendError(
            (object) $data,
            trans('Please try again!')
        );
    }

    public function uploadUserDocuments(Request  $request)
    {

        $user = Auth::user();

        $rules = [
            'documents' => 'required',
            'document_type' => ['required', Rule::in(['1', '2', '3', '4'])],
        ];
        $customMessages = [];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        $data = [];

        if ($validator->fails()) {
            return $this->sendError([], $validator->getMessageBag()->first());
        }

        $file = uploadToS3($request->file('documents'));

        if ($file) {
            // foreach ($documents as $doc) {
            $userDocuemnts = UserDocuments::create([
                'user_id' => $user->id,
                'document' => $file['orig_path_url'],
                'document_type' => $request->get('document_type'),
            ]);
            // }

            return $this->sendResponse(
                $userDocuemnts,
                trans('Document Uploaded Successfully', ['attribute' => 'Document Uploaded '])
            );
        } else {
            return $this->sendError(
                (object) $data,
                trans('Something went wrong. Please try again')
            );
        }
    }

    public function uploadFile(Request $request)
    {
        $user = Auth::user();
        $rules = [
            'file' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        $data = [];
        if ($validator->fails()) {
            return $this->sendError((object) $data, $validator->getMessageBag()->first());
        }

        try {
            $files = uploadToS3($request->file('file'));
        } catch (\Exception $ex) {
            return $this->sendError(
                (object) $data,
                trans('Something went wrong. Please try again')
            );
        }

        return $this->sendResponse(
            $files,
            trans('File uploaded successfully.', ['attribute' => 'File Uploaded'])
        );
    }
}
