<!DOCTYPE html>
<html>
<head>
    <title>ItsolutionStuff.com</title>
    <style>
        .parent-div{
            background-color: #c9b9b92e;
            font-family: Open Sans, sans-serif;
            font-size: 15px;
            font-weight: 400;
            line-height: 1.4;
            color: #000;
            margin-right: 200px;
            margin-left: 200px;
            padding: 35px 35px 35px 35px;
        }
     </style>
</head>
<body >
    <div class="parent-div">
        <p>{!! $body !!}</p>
        <p>Kind regards,<br>
            {{$app_name}}
        </p>
    </div>
</body>
</html>