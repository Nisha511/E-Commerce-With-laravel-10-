<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password Email</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
            line-height: 1.6;
            color: #444;
        }
        h1 {
            color: #333;
        }
    </style>
</head>
<body>   
    <p>Hello, {{$formdata['user']->name}}</p>

    <h1>You have requested to change password</h1>

    <p>Please click on belowe link to reset password.</p>

    <a href="{{route('account.resetPassword',$formdata['token'])}}">Click Here</a>

    <p>Thanks</p>
    
</body>
</html>
