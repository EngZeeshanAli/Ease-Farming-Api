<?php

namespace App\Http\Controllers;

use App\Mail\ForgotPassEmail;
use App\Models\PasswordReset;
use App\Models\User;
use App\Utils\Constants;
use Exception;
use http\Env\Response;
use http\Exception\BadConversionException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use League\CommonMark\Extension\CommonMark\Node\Inline\Strong;
use Nette\Utils\Image;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\HttpKernel\Exception\HttpException;


class UserController extends Controller implements ShouldQueue
{

    public function getUserById(Request $request)
    {
        $validator = Validator::make($request->all(), [
            Constants::ID => Constants::REQUIRED,
        ]);

        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()]);
        } else {
            try {
                $id = $request->id;
                $user = User::find($id);
                return response()->json($user);
            } catch (Exception $e) {
                return response()->json(["error" => $e->getMessage()]);
            }
        }
    }

    public function loginUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            Constants::EMAIL => Constants::REQUIRED,
            Constants::PASSWORD => Constants::REQUIRED
        ]);

        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()]);
        } else {
            try {

                $userCredienttials = array(
                    Constants::EMAIL => $request->email,
                    Constants::PASSWORD => $request->password,
                );

                $attemp = Auth::attempt($userCredienttials);

                if ($attemp) {
                    return response()->json(Auth::user());
                } else {
                    return response()->json(["error" => "email or password is wrong."]);
                }

            } catch (Exception $e) {
                return response()->json(["error" => $e->getMessage()]);
            }
        }
    }

    public function registerNewUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            Constants::NAME => Constants::REQUIRED,
            Constants::EMAIL => ['unique:users', Constants::REQUIRED],
            Constants::PASSWORD => Constants::REQUIRED,
            Constants::MOBILE => Constants::REQUIRED,
            Constants::USER_TYPE => Constants::REQUIRED,
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        } else {
            try {
                $user = new User;
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                $user->mobile = $request->mobile;
                $user->type = $request->type;
                $user->save();
                return response()->json(["success" => "Registered Successfully"]);
            } catch (Exception $e) {
                return response()->json(["error" => $e->getMessage()]);
            }
        }
    }

    public function updateImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            Constants::ID => Constants::REQUIRED,
            Constants::IMAGE => Constants::REQUIRED
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        } else {
            try {
                $id = $request->id;
                $image = $request->img;  // your base64 encoded
//                $image = str_replace('data:image/png;base64,', '', $image);
//                $image = str_replace(' ', '+', $image);
                $imageName = $id . '.png';
                Storage::disk("local")->put($imageName, base64_decode($image));
                $user = User::find($id);
                $user->img = $imageName;
                $user->save();
                return response()->json(["success" => "Image Updated Successfully"]);
            } catch (Exception $e) {
                return response()->json(["error" => $e->getMessage()]);
            }
        }
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            Constants::NAME => Constants::REQUIRED,
            Constants::EMAIL => Constants::REQUIRED,
            Constants::MOBILE => Constants::REQUIRED,
        ]);

        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()]);
        } else {
            try {
                $id = $request->id;
                $user = User::find($id);
                $user->name = $request->name;
                $user->email = $request->email;
                $user->mobile = $request->mobile;
                $user->save();
                return response()->json(["success" => "Profile Updated Successfully", "user" => $user]);
            } catch (Exception $e) {
                return response()->json(["error" => $e->getMessage()]);
            }
        }
    }

    public function resetPasswordRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            Constants::EMAIL => Constants::REQUIRED,
        ]);
        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()]);
        } else {
            try {
                $email = $request->email;
                $user = User::where(Constants::EMAIL, $email)->limit(1)->first();
                if ($user != null) {
                    set_time_limit(10);
                    $otp = rand(100000, 999999);
                    $reset = new PasswordReset;
                    $reset->email = $user->first()->email;
                    $reset->token = $otp;
                    $reset->save();
                    Mail::to($user->first()->email)->send(new ForgotPassEmail($otp));
                    return response()->json(["success" => "Email Send check your inbox"]);
                } else {
                    return response()->json(["error" => "User Not Found"]);
                }
            } catch (Exception $e) {
                return response()->json(["error" => $e->getMessage()]);
            }
        }
    }


    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            Constants::EMAIL => Constants::REQUIRED,
            Constants::PASSWORD => Constants::REQUIRED,
        ]);
        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()]);
        } else {
            try {
                $email = $request->email;
                $password = $request->password;
                $token = $request->token;
                $passwordReset = PasswordReset::where([[Constants::EMAIL, $email], [Constants::TOKEN, $token]])->limit(1)->get();
                if ($passwordReset->count() > 0) {
                    $user = User::where(Constants::EMAIL, $email)->limit(1)->first();
                    $user->password = Hash::make($password);
                    $user->save();
                    PasswordReset::where(Constants::EMAIL, $email)->delete();
                    return response()->json(["success" => "Password Reset Successfully"]);
                } else {
                    return response()->json(["error" => "Pin not matched"]);
                }
            } catch (Exception $e) {
                return response()->json(["error" => $e->getMessage()]);
            }
        }
    }


    public function laboursIndex()
    {
        try {
            $users = User::where(Constants::USER_TYPE, Constants::GUARD_LABOUR)->get();
            return response()->json($users);
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
    }

}
