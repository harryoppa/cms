<?php

namespace TVHung\Api\Http\Controllers;

use ApiHelper;
use App\Http\Controllers\Controller;
use TVHung\Api\Http\Resources\UserResource;
use TVHung\Base\Http\Responses\BaseHttpResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use RvMedia;
use Illuminate\Support\Facades\Schema;

class ProfileController extends Controller
{
    /**
     * Get the user profile information.
     *
     * @group Profile
     * @authenticated
     *
     * @param Request $request
     * @param BaseHttpResponse $response
     *
     * @return BaseHttpResponse
     */
    public function getProfile(Request $request, BaseHttpResponse $response)
    {
        return $response->setData(new UserResource($request->user()));
    }

    /**
     * Update Avatar
     *
     * @bodyParam avatar file required Avatar file.
     *
     * @group Profile
     * @authenticated
     *
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function updateAvatar(Request $request, BaseHttpResponse $response)
    {
        $validator = Validator::make($request->all(), [
            'avatar' => RvMedia::imageValidationRule(),
        ]);

        if ($validator->fails()) {
            return $response
                ->setError()
                ->setCode(422)
                ->setMessage(__('Data invalid!') . ' ' . implode(' ', $validator->errors()->all()) . '.');
        }

        try {
            $file = RvMedia::handleUpload($request->file('avatar'), 0, 'users');
            if (Arr::get($file, 'error') !== true) {
                $user = $request->user();

                // check user has column avatar_id
                if (Schema::hasColumn($user->getTable(), 'avatar_id')) {
                    $user->avatar_id = $file['data']->id;
                    $user->save();
                } else {
                    $user->avatar = $file['data']->url;
                    $user->save();
                }
            }

            return $response
                ->setData([
                    'avatar' => $file['data']->url,
                ])
                ->setMessage(__('Update avatar successfully!'));
        } catch (Exception $ex) {
            return $response
                ->setError()
                ->setMessage($ex->getMessage());
        }
    }

    /**
     * Update profile
     *
     * @bodyParam first_name string required First name.
     * @bodyParam last_name string required Last name.
     * @bodyParam email string Email.
     * @bodyParam dob string required Date of birth.
     * @bodyParam gender string Gender
     * @bodyParam description string Description
     * @bodyParam phone string required Phone.
     *
     * @group Profile
     * @authenticated
     *
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function updateProfile(Request $request, BaseHttpResponse $response)
    {
        $userId = $request->user()->id();

        $validator = Validator::make($request->input(), [
            'first_name' => 'required|max:120|min:2',
            'last_name' => 'required|max:120|min:2',
            'phone' => 'required|max:15|min:8',
            // 'dob' => 'required|max:15|min:8',
            'gender' => 'nullable',
            'description' => 'nullable',
            'email' => 'nullable|max:60|min:6|email|unique:' . ApiHelper::getTable() . ',email,' . $userId,
        ]);

        if ($validator->fails()) {
            return $response
                ->setError()
                ->setCode(422)
                ->setMessage(__('Data invalid!') . ' ' . implode(' ', $validator->errors()->all()) . '.');
        }

        try {
            $user = $request->user()->update($request->input());

            return $response
                ->setData($user)
                ->setMessage(__('Update profile successfully!'));
        } catch (Exception $ex) {
            return $response
                ->setError()
                ->setMessage($ex->getMessage());
        }
    }

    /**
     * Update password
     *
     * @bodyParam password string required The new password of user.
     * @bodyParam password_confirmation string required The new password confirmation of user.
     * @bodyParam current_password string required The current password of user.
     *
     * @group Profile
     * @authenticated
     *
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function updatePassword(Request $request, BaseHttpResponse $response)
    {
        $validator = Validator::make($request->input(), [
            'password'              => 'required|min:6|max:60',
            'password_confirmation' => 'required|same:password',
            'current_password'      => 'required',
        ]);

        if ($validator->fails()) {
            return $response
                ->setError()
                ->setCode(422)
                ->setMessage(__('Data invalid!') . ' ' . implode(' ', $validator->errors()->all()) . '.');
        }

        if (!Hash::check($request->input('current_password'), $request->user()->getAuthPassword())) {
            return $response
                ->setError()
                ->setCode(422)
                ->setMessage(__('Current password is incorrect!'));
        }

        $request->user()->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return $response->setMessage(trans('core/acl::users.password_update_success'));
    }
}
