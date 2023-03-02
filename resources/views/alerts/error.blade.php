<!doctype html>
<html lang="en">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="/admins/plugins/sweetalert2/sweetalert2.min.js"></script>
  <link rel="stylesheet" href="/admins/plugins/sweetalert2/sweetalert2.min.css">
<body>
  <script>
    Swal.fire({
        title: '{{$msg}}',
        icon: "error",
        confirmButtonText:'確認',
        allowOutsideClick: false
    }).then((result) => {
        if(result.value){
            location.href='{{$redirectURL}}'
        }
    })
  </script>
</body>

</html>