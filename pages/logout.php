<?php
unset($_SESSION['token']);
orsi_destroy();
echo '<script>
        $(function() {
            localStorage.removeItem("token");
            window.location.href = "/";
        });
    </script>';
?>