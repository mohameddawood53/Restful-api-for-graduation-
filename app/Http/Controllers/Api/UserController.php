<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\registerRequest;
use App\Models\comments;
use App\Models\images;
use App\Models\mixtuers;
use App\Models\posts;
use App\Models\postsImages;
use App\Models\reply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $login = $request->validate([
            "email" => "email|required",
            "password" => "required"
        ]);


        if(!auth()->attempt($login))
        {
            return response(["message" => "invalid credentials!"]);
        }

        $accessToken = auth()->user()->createToken("authToken")->accessToken;
        $userup = auth()->user()->signed_in = 1;
//        $userup->save();

        return response([
            "user" => auth()->user(),
            "access_token" => $accessToken
        ]);
    }

    public function show(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "id" => "required"
        ]);
        if ($validator->fails())
        {
            return response([
                "message" => "error",
                "errors" => $validator->errors()
            ]);
        }

        $user = User::find($request->id);

        if($user)
        {
            return response([
                "message" => "success",
                "user" => $user
            ]);
        }else{
            return response([
                "message" => "error",
                "errors" => "there is not such user"
            ]);
        }

    }

    public function deluser(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "id" => "required"
        ]);
        if ($validator->fails())
        {
            return response([
                "message" => "error",
                "errors" => $validator->errors()
            ]);
        }

        $user = User::find($request->id);

        if($user)
        {
            $user->delete();
            return response([
                "message" => "success"
            ]);
        }else{
            return response([
                "message" => "error",
                "errors" => "there is not such user"
            ]);
        }

    }

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(),[
            "name" => "required|max:55",
            "email" => "required|email|unique:users",
            "password" => "required",
            "address" => "required|max:255",
            "phone" => "required|unique:users"
        ]);
//        $validator = $request->validate([
//            "name" => "required|max:55",
//            "email" => "required|email|unique:users",
//            "password" => "required|confirmed",
//            "address" => "required|max:255",
//            "phone" => "required|unique:users"
//        ]);

//        $validator['password'] = bcrypt($validator["password"]);

        if ($validator->fails())
        {
            return response([
                "message" => "error",
                "errors" => $validator->errors()
            ]);
        }

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => bcrypt($request->password),
            "address" => $request->address,
            "phone" => $request->phone
        ]);


        $accessToken = $user->createToken("authToken")->accessToken;

        return response([
            "user" => $user,
            "access_token" => $accessToken
            ]);
    }

    public function logout($id)
    {
        $user = User::find($id);
        $user->signed_in = 0;
        $user->save();
        return response([
            'message' => "logout",
            "user" => $user
        ]);
    }

    public function userMix(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "user_id" => "required|numeric",
            "mx_date" => "required|date",
            "mx_time" => "required|date_format:H:i"
        ]);

        if ($validator->fails())
        {
            return response([
                "message" => "error",
                "errors" => $validator->errors()
            ]);
        }
//        $validator = $request->validate([
//
//        ]);

        $user = User::find($request->user_id);
        $mix = mixtuers::create([
            "user_id" => $request->user_id,
            "mx_date" => $request->mx_date,
            "mx_time" => $request->mx_time
        ]);

        $user->mixtures()->save($mix);
        $mix->user()->associate($user);
        $mix->save();
        return response([
            "message" => "mix inserted successfully",
            "data" => [
                "id" => $user->id,
                "name" => $user->name,
                "job" => $user->job,
                "address" => $user->address,
                "signed_in" => $user->signed_in,
                "email" => $user->email,
                "phone" => $user->phone,
                "sick" => $user->sick,
                "user_mix" => $user->mixtures
            ]
        ]);
    }

    public function user_mix($id)
    {
        $user = User::find($id);
        return response([
            "message" => "success",
            "data" => [
                "id" => $user->id,
                "name" => $user->name,
                "job" => $user->job,
                "address" => $user->address,
                "signed_in" => $user->signed_in,
                "email" => $user->email,
                "phone" => $user->phone,
                "sick" => $user->sick,
                "user_mix" => $user->mixtures
            ]
        ]);

    }

    public function update_mix(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "id" => "required",
            "mx_date" => "required|date",
            "mx_time" => "required|date_format:H:i"
        ]);
//        $validator = $request->validate([
//
//        ]);
        if ($validator->fails())
        {
            return response([
                "message" => "error",
                "errors" => $validator->errors()
            ]);
        }

        $mix = mixtuers::find($request->id);
        $mix->mx_date = $request->mx_date;
        $mix->mx_time = $request->mx_time;
        $mix->save();

        return response([
            "message" => "success",
            "data" => [
                "user_id" => $mix->user_id,
                "mx_date" => $mix->mx_date,
                "mx_time" => $mix->mx_time,
                "mx_user" => $mix->user
            ]
        ]);

    }

    public function newpost(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "image" => "required",
            "title" => "required",
            "body" => "required|max:255",
            "user_id" => "required|numeric",
            "desc" => "required|max:255",
        ]);

        if ($validator->fails())
        {
            return response([
                "message" => "error",
                "errors" => $validator->errors()
            ]);
        }
        $user = User::find($request->user_id);
        $post = $user->posts()->create([
            "title" => $request->title,
            "body" => $request->body,
            "desc" => $request->desc
        ]);
        if($request->hasFile("image"))
        {
            $files = $request->file("image");
            foreach ($files as $file)
            {
                $name = time() . $file->getClientOriginalName();
                $file->move(public_path().'/images/', $name);
                $post->image()->create([
                    "image" => $name
                ]);


            }
        }

        return response([
            "message" => "success",
            "post" => $post,
            "post_user" => $post->user,
            "images" => $post->image
        ]);

    }
    public function newdonation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "amount" => "required|numeric",
            "title" => "required",
            "desc" => "required",
            "user_id" => "required",
            "image" => "required"
        ]);

        if ($validator->fails())
        {
            return response([
                "message" => "error",
                "errors" => $validator->errors()
            ]);
        }

        $user = User::find($request->user_id);
//        return response([
//            $user
//        ]);
        $donation = $user->donations()->create([
            "amount" => $request->amount,
            "title" => $request->title,
            "desc" => $request->desc
        ]);

        if($request->hasFile("image"))
        {
            $files = $request->file("image");
            foreach ($files as $file)
            {
                $name = time() . $file->getClientOriginalName();
                $file->move(public_path().'/images/', $name);
                $donation->images()->create([
                    "image" => $name
                ]);


            }
        }

        return response([
            "message" => "success",
            "donation" => $donation,
            "donation_user" => $donation->user,
            "images" => $donation->images
        ]);

    }

    public function deletepost(Request $request)
    {


    }

    public function newcomment(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "user_id" => "required",
            "p_id" => "required",
            "comment" => "required"
        ]);

        if ($validator->fails())
        {
            return response([
                "message" => "error",
                "errors" => $validator->errors()
            ]);
        }
        $errors = array();
//
        $user = User::find($request->user_id);
        $post = posts::find($request->p_id);
//        dd($post->desc);

        if ($user and $post)
        {
            $comment = comments::create([
                "user_id" => $request->user_id,
                "posts_id" => $request->p_id,
                "comment" => $request->comment
            ]);

            return response([
                "message" => "success",
                "comment" => $comment,
                "comment_user" => $user,
                "comment_post" => $post
            ]);
        }

        if (!$user)
        {
            $errors[] = [
                "user_is" => "there is not such user"
            ];
        }

        if (!$post)
        {
            $errors[] = [
                "posts_is" => "there is not such post"
            ];
        }

        if (!empty($errors))
        {
            return response([
                "message" => "error",
                "errors" => $errors
            ]);
        }

    }
    public function viewPostCmnts(Request $request)
    {
        $errors = array();
        $validator = Validator::make($request->all(),[
            "post_id" => "required"
        ]);

        if ($validator->fails())
        {
            return response([
                "message" => "error",
                "errors" => $validator->errors()
            ]);
        }
        $post = posts::find($request->post_id);
        $comments = $post->comments;
        if ($post)
        {
            return response([
                "comments" => $comments,
                "comment_replies" => $comments->replies
            ]);
        }

        if (!$post)
        {
            $errors[] = [
                "post_id" => "there is not such post"
            ];
        }

        if (!empty($errors))
        {
            return response([
                "message" => "error",
                "errors" => $errors
            ]);
        }
    }

    public function delPost(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "post_id" => "required"
        ]);

        if ($validator->fails())
        {
            return response([
                "message" => "error",
                "errors" => $validator->errors()
            ]);
        }
        $post = posts::find($request->post_id);
        if ($post)
        {
            $post->delete();

            return response([
                "message" => "success"
            ]);
        }else{
            return response([
                "message" => "error",
                "errors" => "there is not such post"
            ]);
        }


    }

    public function showComment(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "comment_id" => "required"
        ]);

        if ($validator->fails())
        {
            return response([
                "message" => "error",
                "errors" => $validator->errors()
            ]);
        }

        $comment = comments::find($request->comment_id);

        if($comment)
        {
            return response([
                "comment" => $comment,
                "comment_user" => $comment->user,
                "comment_replies" => $comment->replies
            ]);
        }else{
            return response([
                "message" => "error",
                "errors" => "this comment not found"
            ]);
        }


    }

    public function addreply(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "comments_id" => "required|numeric",
            "user_id" => "required|numeric",
            "comment" => "required|max:255"
        ]);

        if ($validator->fails())
        {
            return response([
                "message" => "error",
                "errors" => $validator->errors()
            ]);
        }

        $user = User::find($request->user_id);
        $comment = comments::find($request->comments_id);
        $errors = array();

        if($user and $comment)
        {
            $reply = reply::create([
                "comments_id" => $request->comments_id,
                "user_id" => $request->user_id,
                "comment" => $request->comment
            ]);

            return response([
                "message" => "success",
                "reply" => $reply,
                "reply_comment" => $reply->comment,
                "reply_user" => $reply->user
            ]);
        }

        if(!$user){
            $errors[] = [
                "user_id" => "this user is not exists"
            ];
        }

        if(!$comment){
            $errors[] = [
                "comments_id" => "this comment is not exists"
            ];
        }

        if(!empty($errors))
        {
            return response([
                "message" => "error",
                "errors" => $errors
            ]);
        }
    }

    public function delreplpy(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "reply_id" => "required"
        ]);

        if ($validator->fails())
        {
            return response([
                "message" => "error",
                "errors" => $validator->errors()
            ]);
        }


        $reply = reply::find($request->reply_id);

        if($reply)
        {
            $reply->delete();

            return response([
                "message" => "success"
            ]);
        }else{
            return response([
                "message" => "error",
                "errors" => "there is not such reply"
            ]);
        }

    }

    public function delcmnt(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "comments_id" => "required"
        ]);

        if ($validator->fails())
        {
            return response([
                "message" => "error",
                "errors" => $validator->errors()
            ]);
        }


        $comment = comments::find($request->comments_id);

        if($comment)
        {
            $comment->delete();

            return response([
                "message" => "success"
            ]);
        }else{
            return response([
                "message" => "error",
                "errors" => "there is not such comment"
            ]);
        }

    }








        public function index()
        {
            return view("index");
        }
}


