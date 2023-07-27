<!DOCTYPE html>
<html>
<head>
    <title>User Online Real-Time</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Mengupdate jumlah pengguna online setiap 1 detik
            setInterval(function() {
                $.ajax({
                    url: 'online_users.php',
                    dataType: 'json',
                    success: function(response) {
                        $('#total_users_online').text(response.total_users_online);
                    }
                });
            }, 1000);
        });
    </script>
</head>
<body>
    <h1>Jumlah Pengguna Online: <span id="total_users_online"></span></h1>
</body>
</html>
