<!DOCTYPE html>
<html>
  <head>
    <title>忘記密碼通知</title>
  </head>
  <body>
    <h2>{{$admin->name}} 先生/小姐 您好：</h2>
    <br/>
    您的郵件地址為：<br>
    {{$admin->email}}<br><br>
    
    您的新密碼為：<strong>{{$password}}</strong><br>
    請您透過此密碼登錄後，再至<a href="{{$url}}">系統後台</a>內修改密碼
  </body>
</html>